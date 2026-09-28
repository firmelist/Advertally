<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Read a site setting managed from the admin panel (cached).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('inr')) {
    /**
     * Format a number in Indian currency style: ₹1,25,000
     */
    function inr(int|float|null $amount, bool $symbol = true): string
    {
        if ($amount === null) {
            return '';
        }

        $amount = (int) round($amount);
        $negative = $amount < 0;
        $num = (string) abs($amount);

        if (strlen($num) > 3) {
            $last3 = substr($num, -3);
            $rest = substr($num, 0, -3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $num = $rest.','.$last3;
        }

        return ($negative ? '-' : '').($symbol ? '₹' : '').$num;
    }
}

if (! function_exists('whatsapp_link')) {
    /**
     * Click-to-chat link with a pre-filled message that includes the page name.
     */
    function whatsapp_link(?string $context = null): string
    {
        $number = preg_replace('/\D/', '', (string) setting('whatsapp_number', '919999999999'));
        $text = 'Hi Advertally, I would like to know more'.($context ? " about {$context}" : '').'.';

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }
}

if (! function_exists('tel_link')) {
    function tel_link(): string
    {
        return 'tel:'.preg_replace('/[^\d+]/', '', (string) setting('phone', '+91 99999 99999'));
    }
}
