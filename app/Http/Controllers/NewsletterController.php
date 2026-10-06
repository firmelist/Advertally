<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Models\NewsletterSubscriber;
use App\Services\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function store(NewsletterRequest $request): RedirectResponse
    {
        $subscriber = NewsletterSubscriber::query()->firstOrNew(['email' => $request->validated('email')]);
        $subscriber->fill([
            'status' => 'subscribed',
            'unsubscribed_at' => null,
            'source' => $subscriber->source ?? mb_substr((string) $request->validated('source_page'), 0, 120),
        ])->save();

        return back()->with('newsletter', 'You are subscribed to the AI Growth Intelligence Brief.')->withFragment('newsletter');
    }

    public function unsubscribe(string $token, Seo $seo): View
    {
        $subscriber = NewsletterSubscriber::query()->where('token', $token)->firstOrFail();
        $subscriber->update(['status' => 'unsubscribed', 'unsubscribed_at' => now()]);

        $seo->page('Unsubscribed')->noindex();

        return view('pages.unsubscribed');
    }
}
