<?php

return [
    // Secret key mode test, contoh: xnd_development_xxxxxxxxxxxxxxxxxxxxx
    'secret_key' => env('XENDIT_SECRET_KEY'),

    // Verification token untuk validasi webhook (Settings > Webhooks di dashboard Xendit)
    'callback_token' => env('XENDIT_CALLBACK_TOKEN'),

    'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
];
