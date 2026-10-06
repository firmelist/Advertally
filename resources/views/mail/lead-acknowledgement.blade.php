<x-mail::message>
Hi {{ str($lead->name)->before(' ') }},

Thank you for contacting Advertally. We have your request{{ $lead->company ? ' for '.$lead->company : '' }} and a senior strategist will review it before we respond — within one business day.

@if ($lead->form_type === 'growth_score' || $lead->form_type === 'ai_audit')
Your report is ready now. When we speak, we will validate it against your live presence and focus on the moves with the highest revenue impact.
@else
To make our first conversation as useful as possible, you can benchmark your business in about four minutes:

<x-mail::button :url="route('growth-score')">
Get your Growth Score
</x-mail::button>
@endif

Speak soon,<br>
The Advertally team

<small>You are receiving this because you submitted a form on {{ parse_url(config('app.url'), PHP_URL_HOST) }}. If this was not you, simply ignore this email.</small>
</x-mail::message>
