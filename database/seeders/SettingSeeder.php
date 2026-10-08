<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Company facts used across the site and in Organization schema. Leave blank until verified — blank values are hidden.
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['general', 'company_name', 'Company name', 'text', 'Advertally'],
            ['general', 'legal_name', 'Legal entity name', 'text', 'Advertally'],
            ['general', 'company_description', 'Company description (schema & llms.txt)', 'textarea', 'Advertally is an AI-native growth and revenue partner. It helps ambitious businesses become discoverable, trusted and chosen across search, AI platforms and every digital touchpoint that drives revenue.'],
            ['general', 'area_served', 'Area served', 'text', 'India and international markets'],
            ['contact', 'email', 'Public email', 'email', 'hello@advertally.com'],
            ['contact', 'careers_email', 'Internship applications email (comma-separated; blank = lead alert emails)', 'text', null],
            ['contact', 'phone', 'Public phone', 'text', null],
            ['contact', 'address', 'Office address', 'textarea', null],
            ['contact', 'country', 'Country code', 'text', 'IN'],
            ['social', 'linkedin_url', 'LinkedIn URL', 'url', null],
            ['social', 'youtube_url', 'YouTube URL', 'url', null],
            ['social', 'x_url', 'X (Twitter) URL', 'url', null],
            ['social', 'x_handle', 'X handle (e.g. @advertally)', 'text', null],
            ['social', 'instagram_url', 'Instagram URL', 'url', null],
            ['social', 'facebook_url', 'Facebook URL', 'url', null],
            ['seo', 'default_meta_title', 'Default meta title', 'text', 'Advertally — AI-Native Growth & Revenue Partner'],
            ['seo', 'default_meta_description', 'Default meta description', 'textarea', 'Advertally builds AI-ready growth systems that help businesses get discovered, trusted and chosen across search, AI platforms and every digital touchpoint that drives revenue.'],
            ['seo', 'default_og_image', 'Default social share image (path or URL)', 'text', null],
        ];

        foreach ($settings as [$group, $key, $label, $type, $value]) {
            Setting::query()->firstOrCreate(['key' => $key], compact('group', 'label', 'type', 'value'));
        }
    }
}
