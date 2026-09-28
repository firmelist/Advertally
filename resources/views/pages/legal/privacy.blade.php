@extends('pages.legal._layout', ['heading' => 'Privacy Policy', 'updated' => '24 September 2026'])
@section('title', 'Privacy Policy | Advertally')
@section('legal')
<p><strong>Template notice:</strong> This policy is a starting template aligned with India's Digital Personal Data Protection Act, 2023 (DPDP Act). Have it reviewed by your legal advisor before publishing.</p>
<h2>1. Who we are</h2>
<p>{{ setting('company_name', 'Advertally') }} ("we", "us") is the data fiduciary for personal data collected through this website. Contact: <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>, {{ setting('address') }}.</p>
<h2>2. What we collect</h2>
<ul>
    <li>Details you submit in forms: name, phone/WhatsApp number, email, company, website, business size, budget and message.</li>
    <li>Website audit inputs: the website address you ask us to analyse.</li>
    <li>Technical data: IP address, device type, browser, pages visited and campaign parameters (UTM, gclid, fbclid).</li>
    <li>Cookies: essential cookies always; analytics and advertising cookies only with your consent.</li>
</ul>
<h2>3. Why we use it</h2>
<ul>
    <li>To respond to your enquiry by call, WhatsApp or email and send you proposals you requested.</li>
    <li>To deliver services you purchase and send invoices.</li>
    <li>To measure and improve our website and marketing (with consent).</li>
</ul>
<h2>4. Consent and your rights</h2>
<p>We process your data based on the consent you give when submitting a form. You may withdraw consent, request access, correction or erasure of your data, or nominate another person to exercise your rights, by writing to <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>. We will respond within the timelines required by law.</p>
<h2>5. Sharing</h2>
<p>We do not sell your data. We share it only with service providers who help us operate (hosting, email, WhatsApp Business API, CRM), under contracts that protect your data, or when required by law.</p>
<h2>6. Retention and security</h2>
<p>We keep enquiry data for up to 24 months after our last interaction unless you become a client or ask us to delete it sooner. We use encryption in transit, access controls and regular backups.</p>
<h2>7. Grievance officer</h2>
<p>For any concerns, contact our Grievance Officer at <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>.</p>
@endsection
