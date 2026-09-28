<?php

require_once __DIR__.'/../../app/helpers.php';

it('formats rupees in the Indian numbering system', function () {
    expect(inr(125000))->toBe('₹1,25,000')
        ->and(inr(9999))->toBe('₹9,999')
        ->and(inr(12345678))->toBe('₹1,23,45,678')
        ->and(inr(500))->toBe('₹500');
});
