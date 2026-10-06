<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permission::AREAS as $area => $label) {
            foreach (Permission::ACTIONS as $action => $actionLabel) {
                Permission::query()->updateOrCreate(['name' => "{$area}.{$action}"], ['label' => "{$actionLabel} — {$label}", 'group' => $area]);
            }
        }

        $grant = function (Role $role, array $areas, array $actions = ['view', 'manage', 'delete']) {
            $role->permissions()->sync(Permission::query()
                ->whereIn('group', $areas)
                ->where(fn ($q) => collect($actions)->each(fn ($a) => $q->orWhere('name', 'like', "%.{$a}")))
                ->pluck('id'));
        };

        Role::query()->updateOrCreate(['name' => 'super-admin'], ['label' => 'Super Admin', 'description' => 'Full access to everything, including users, roles and settings.', 'is_super' => true]);

        $grant(Role::query()->updateOrCreate(['name' => 'content-editor'], ['label' => 'Content Editor', 'description' => 'Manages website content, SEO and media. No access to leads.']),
            ['services', 'industries', 'case_studies', 'insights', 'research', 'testimonials', 'pages', 'media', 'seo']);

        $grant(Role::query()->updateOrCreate(['name' => 'growth-consultant'], ['label' => 'Growth Consultant', 'description' => 'Works assigned leads and audit requests. Receives round-robin lead assignment.']),
            ['leads', 'audits'], ['view', 'manage']);

        $grant(Role::query()->updateOrCreate(['name' => 'analyst'], ['label' => 'Analyst', 'description' => 'Read-only access to leads, audits and activity for reporting.']),
            ['leads', 'audits', 'activity'], ['view']);

        User::query()->updateOrCreate(
            ['email' => config('advertally.admin.email')],
            ['name' => 'Advertally Admin', 'password' => config('advertally.admin.password'), 'is_active' => true,
                'role_id' => Role::query()->where('name', 'super-admin')->value('id')],
        );
    }
}
