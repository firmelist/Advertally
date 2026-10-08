<x-mail::message>
# New internship application

**{{ $application->name }}** applied for **{{ $application->internship?->title ?? 'an internship' }}**.

<x-mail::table>
| | |
|:--|:--|
| Email | {{ $application->email }} |
| Phone | {{ $application->phone ?: '—' }} |
| City | {{ $application->city ?: '—' }} |
| College / course | {{ $application->education ?: '—' }} |
| Graduation year | {{ $application->graduation_year ?: '—' }} |
| Availability | {{ $application->availability ?: '—' }} |
| LinkedIn | {{ $application->linkedin_url ?: '—' }} |
| Portfolio | {{ $application->portfolio_url ?: '—' }} |
</x-mail::table>

**Why this internship**

{{ $application->motivation }}

<x-mail::button :url="url('/admin/internship-applications')">
Review in admin (résumé download)
</x-mail::button>
</x-mail::message>
