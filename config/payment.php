<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bank Transfer Details
    |--------------------------------------------------------------------------
    | Shown to customers on the payment page. Snapshotted into each order
    | at time of checkout so historical orders remain accurate.
    */

    'bank_name'    => env('PAYMENT_BANK_NAME', 'BCA'),
    'bank_account' => env('PAYMENT_BANK_ACCOUNT', '1234567890'),
    'bank_holder'  => env('PAYMENT_BANK_HOLDER', 'PT Supplier MBG'),

    /*
    |--------------------------------------------------------------------------
    | File Upload Constraints
    |--------------------------------------------------------------------------
    */

    'proof_max_size_kb'  => 5120,   // 5 MB
    'proof_allowed_mime' => ['image/jpeg', 'image/png', 'application/pdf'],
    'proof_allowed_ext'  => ['jpg', 'jpeg', 'png', 'pdf'],
];
