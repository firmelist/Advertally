<?php

namespace App\Services;

use App\Jobs\NotifyNewLead;
use App\Models\ContactSubmission;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Single entry point for every public form: stores the raw submission, creates or enriches the lead
 * with attribution, scores it, assigns an owner and fans out notifications.
 */
class LeadService
{
    private const LEAD_FIELDS = [
        'name', 'company', 'email', 'phone', 'website', 'job_title', 'industry', 'service_interest',
        'challenge', 'objective', 'budget', 'message',
    ];

    public function __construct(private Attribution $attribution) {}

    public function capture(array $data, string $formType, Request $request): Lead
    {
        $first = $this->attribution->firstTouch($request);
        $last = $this->attribution->lastTouch($request);
        $ua = (string) $request->userAgent();

        $lead = new Lead([
            ...Arr::only(array_filter($data, fn ($v) => filled($v)), self::LEAD_FIELDS),
            'form_type' => $formType,
            'source' => $last['source'] ?? $first['source'] ?? 'direct',
            'source_page' => mb_substr((string) ($data['source_page'] ?? $request->headers->get('referer')), 0, 500) ?: null,
            'landing_page' => $last['landing_page'] ?? $first['landing_page'] ?? null,
            'referrer' => $last['referrer'] ?? null,
            ...Arr::only($last, [...Attribution::UTM, ...Attribution::CLICK_IDS]),
            'first_touch_source' => $first['source'] ?? null,
            'first_touch_medium' => $first['utm_medium'] ?? null,
            'first_touch_campaign' => $first['utm_campaign'] ?? null,
            'first_touch_landing_page' => $first['landing_page'] ?? null,
            'first_touch_at' => isset($first['at']) ? rescue(fn () => now()->parse($first['at']), null, false) : null,
            'last_touch_source' => $last['source'] ?? null,
            'device' => $this->device($ua),
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr($ua, 0, 500),
            'consent' => (bool) ($data['consent'] ?? false),
            'status' => 'new',
        ]);

        $lead->score = $this->score($lead);
        $lead->assigned_to = $this->nextAssignee()?->id;
        $lead->save();

        ContactSubmission::query()->create([
            'lead_id' => $lead->id,
            'form' => $formType,
            'payload' => Arr::except($data, ['consent', 'source_page']),
            'page_url' => $lead->source_page,
            'ip_address' => $request->ip(),
        ]);

        NotifyNewLead::dispatch($lead);

        return $lead;
    }

    /**
     * 0–100 fit score: business email, company website, budget and intent signals.
     * Used only to prioritise follow-up, never shown to the visitor.
     */
    public function score(Lead $lead): int
    {
        $score = 10;
        $domain = strtolower((string) str($lead->email)->after('@'));
        $freeMail = in_array($domain, ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'icloud.com', 'rediffmail.com', 'proton.me'], true);

        $score += $freeMail ? 0 : 20;
        $score += filled($lead->company) ? 10 : 0;
        $score += filled($lead->website) ? 10 : 0;
        $score += filled($lead->phone) ? 5 : 0;
        $score += match ($lead->budget) {
            '10l-plus' => 25, '3l-10l' => 20, '1l-3l' => 12, 'project' => 8, default => 0,
        };
        $score += in_array($lead->industry, ['technology', 'saas', 'it-services', 'consulting', 'professional-services', 'financial-services', 'recruitment', 'b2b-services'], true) ? 10 : 0;
        $score += $lead->form_type === 'contact' ? 10 : 5;

        return min(100, $score);
    }

    private function device(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/tablet|ipad/i', $ua) => 'tablet',
            (bool) preg_match('/mobile|android|iphone/i', $ua) => 'mobile',
            default => 'desktop',
        };
    }

    /** Round-robin across active users who are allowed to work leads. */
    private function nextAssignee(): ?User
    {
        $owners = User::query()
            ->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('name', 'growth-consultant'))
            ->orderBy('id')
            ->get();

        if ($owners->isEmpty()) {
            return null;
        }

        $last = Lead::query()->whereIn('assigned_to', $owners->pluck('id'))->latest('id')->value('assigned_to');
        $index = $last ? $owners->search(fn ($u) => $u->id === $last) : -1;

        return $owners->get(($index + 1) % $owners->count());
    }
}
