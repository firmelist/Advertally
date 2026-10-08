<x-mail::message>
Hi {{ str($application->name)->before(' ') }},

Thank you for applying for the **{{ $application->internship?->title ?? 'internship' }}** at Advertally. We have your application and résumé.

Our team reviews every application personally. If your profile is a good fit, we will contact you to arrange a short conversation.

Meanwhile, you can explore how we think about growth in our [Insights]({{ route('insights.index') }}) and the [AI Search Lab]({{ route('lab.index') }}).

Best wishes,<br>
The Advertally team

<small>You are receiving this because you applied on {{ parse_url(config('app.url'), PHP_URL_HOST) }}.</small>
</x-mail::message>
