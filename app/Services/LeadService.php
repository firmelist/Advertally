<?php

namespace App\Services;

use App\Jobs\NotifyNewLead;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LeadService
{
    public function __construct(private LeadScorer $scorer) {}

    /**
     * Create a lead from validated form data + request context (attribution, device).
     */
    public function capture(array $data, Request $request): Lead
    {
        $attribution = (array) $request->session()->get('attribution', []);
        $ua = (string) $request->userAgent();

        $lead = new Lead([
            ...Arr::only($data, ['name', 'phone', 'email', 'company', 'city', 'website', 'business_size', 'industry', 'budget', 'services', 'form_type']),
            'message' => $this->composeMessage($data),
            'source_page' => $data['source_page'] ?? $request->headers->get('referer'),
            'landing_page' => $attribution['landing_page'] ?? null,
            'referrer' => $attribution['referrer'] ?? null,
            ...Arr::only($attribution, ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid']),
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr($ua, 0, 500),
            'device' => $this->device($ua),
            'pages_viewed' => (int) $request->session()->get('pages_viewed', 1),
            'consent' => true,
            'status' => 'new',
        ]);

        $lead->score = $this->scorer->score($lead);
        $lead->assigned_to = $this->nextAssignee()?->id;
        $lead->save();

        NotifyNewLead::dispatch($lead);

        return $lead;
    }

    /** Fold form-specific extras (hire / plan builder) into a readable message. */
    private function composeMessage(array $data): ?string
    {
        $extras = array_filter([
            'Role needed' => $data['role'] ?? null,
            'Experience' => $data['experience'] ?? null,
            'Engagement' => $data['engagement'] ?? null,
            'Start date' => $data['start_date'] ?? null,
            'Selected plan' => $data['plan_summary'] ?? null,
            'Estimated total' => $data['plan_total'] ?? null,
        ]);

        $lines = collect($extras)->map(fn ($v, $k) => "{$k}: {$v}")->values();

        if (filled($data['message'] ?? null)) {
            $lines->prepend($data['message']);
        }

        return $lines->isEmpty() ? null : $lines->implode("\n");
    }

    private function device(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/tablet|ipad/i', $ua) => 'tablet',
            (bool) preg_match('/mobile|android|iphone/i', $ua) => 'mobile',
            default => 'desktop',
        };
    }

    /** Round-robin assignment across active sales users (falls back to unassigned). */
    private function nextAssignee(): ?User
    {
        $sales = User::query()->where('role', 'sales')->where('is_active', true)->orderBy('id')->get();
        if ($sales->isEmpty()) {
            return null;
        }

        $lastAssigned = Lead::query()->whereIn('assigned_to', $sales->pluck('id'))->latest('id')->value('assigned_to');
        $index = $lastAssigned ? $sales->search(fn ($u) => $u->id === $lastAssigned) : -1;

        return $sales->get(($index + 1) % $sales->count());
    }
}
