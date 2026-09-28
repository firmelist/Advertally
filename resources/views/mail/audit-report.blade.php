<x-mail::message>
# Your website scored {{ $report->score }}/100

Hi {{ explode(' ', $report->lead?->name ?? 'there')[0] }},

Your full audit report for **{{ parse_url($report->url, PHP_URL_HOST) }}** is attached as a PDF. We found **{{ collect($report->checks)->where('status', '!=', 'pass')->count() }} areas to improve**.

<x-mail::button :url="route('audit.show', $report)">
View report online
</x-mail::button>

Most of these fixes are quick wins. If you'd like our team to handle them — or just talk through what matters most — reply to this email or book a free call.

<x-mail::button :url="config('advertally.booking_url')" color="success">
Book a free review call
</x-mail::button>

Team {{ setting('company_name', 'Advertally') }}
</x-mail::message>
