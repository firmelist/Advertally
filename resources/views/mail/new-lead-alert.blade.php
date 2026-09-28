<x-mail::message>
# New {{ strtoupper($lead->temperature) }} lead — score {{ $lead->score }}/100

**{{ $lead->name }}**{{ $lead->company ? ' · '.$lead->company : '' }}

<x-mail::table>
| Field | Details |
|:------|:--------|
| Phone | {{ $lead->phone }} |
| Email | {{ $lead->email ?: '—' }} |
| City | {{ $lead->city ?: '—' }} |
| Business size | {{ config('advertally.business_sizes')[$lead->business_size] ?? '—' }} |
| Budget | {{ config('advertally.budgets')[$lead->budget] ?? '—' }} |
| Interested in | {{ $lead->services_label ?: '—' }} |
| Form | {{ \App\Models\Lead::FORM_TYPES[$lead->form_type] ?? $lead->form_type }} |
| Source | {{ $lead->utm_source ?: 'Direct / organic' }}{{ $lead->utm_campaign ? ' · '.$lead->utm_campaign : '' }} |
| Page | {{ $lead->source_page ?: '—' }} |
| Assigned to | {{ $lead->assignee?->name ?? 'Unassigned' }} |
</x-mail::table>

@if ($lead->message)
**Message:**
{{ $lead->message }}
@endif

<x-mail::button :url="$lead->whatsapp_url" color="success">
WhatsApp {{ explode(' ', $lead->name)[0] }} now
</x-mail::button>

<x-mail::button :url="url('/admin/leads/'.$lead->id)">
Open in CRM
</x-mail::button>

Speed matters: leads contacted within 5 minutes are far more likely to convert.
</x-mail::message>
