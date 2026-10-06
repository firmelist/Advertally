@php app(\App\Services\Seo::class)->faq($data['items'] ?? []); @endphp
<x-faq :items="$data['items'] ?? []" :title="$data['title'] ?? 'Questions business owners ask'" />
