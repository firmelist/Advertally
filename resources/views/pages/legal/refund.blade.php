@extends('pages.legal._layout', ['heading' => 'Refund & Cancellation Policy', 'updated' => '24 September 2026'])
@section('title', 'Refund Policy | Advertally')
@section('legal')
<p><strong>Template notice:</strong> Have this policy reviewed by your legal advisor before publishing.</p>
<h2>Monthly services</h2>
<p>Monthly fees cover work already scheduled for that month and are non-refundable once the month has started. You may cancel future months as per the notice period in your agreement.</p>
<h2>Projects</h2>
<p>Project advances are refundable in full if cancelled before work begins. After work begins, refunds are pro-rated to the milestones not yet delivered.</p>
<h2>Dedicated resources</h2>
<p>If you are not satisfied during the 7-day trial, you may end the engagement and pay only for hours worked, or request a free replacement.</p>
<h2>How to request</h2>
<p>Email <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a> with your invoice number. Approved refunds are processed to the original payment method within 7–10 working days.</p>
@endsection
