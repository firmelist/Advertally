<x-mail::message>
# New lead: {{ $lead->name }}

**{{ $lead->label('form_type') }}** · fit score **{{ $lead->score }}/100**

<x-mail::table>
| | |
|:--|:--|
| Company | {{ $lead->company ?: '—' }} |
| Email | {{ $lead->email }} |
| Phone | {{ $lead->phone ?: '—' }} |
| Website | {{ $lead->website ?: '—' }} |
| Industry | {{ $lead->label('industry') ?: '—' }} |
| Interest | {{ $lead->label('service_interest') ?: '—' }} |
| Challenge | {{ $lead->label('challenge') ?: '—' }} |
| Objective | {{ $lead->label('objective') ?: '—' }} |
| Budget | {{ $lead->label('budget') ?: '—' }} |
| Source | {{ $lead->source ?: 'direct' }}{{ $lead->utm_campaign ? ' · '.$lead->utm_campaign : '' }} |
| First touch | {{ $lead->first_touch_source ?: '—' }} |
</x-mail::table>

@if ($lead->message)
**Message**

{{ $lead->message }}
@endif

<x-mail::button :url="url('/admin/leads/'.$lead->id)">
Open in admin
</x-mail::button>
</x-mail::message>
