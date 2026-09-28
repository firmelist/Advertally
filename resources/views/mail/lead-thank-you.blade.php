<x-mail::message>
# Thanks, {{ explode(' ', $lead->name)[0] }}!

We've received your enquiry and a strategist from our team will call or WhatsApp you within **2 working hours** ({{ setting('business_hours') }}).

**What happens next**

1. We review your website, market and competitors.
2. We call you to understand your goals and budget.
3. You receive a clear plan with targets and a fixed quote.

Want to skip the wait? Pick a time that suits you:

<x-mail::button :url="config('advertally.booking_url')">
Book a free 30-minute call
</x-mail::button>

Or simply reply to this email or WhatsApp us at {{ setting('phone') }}.

Warm regards,<br>
Team {{ setting('company_name', 'Advertally') }}
</x-mail::message>
