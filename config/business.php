<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Business Information
    |--------------------------------------------------------------------------
    |
    | This file contains your business information used throughout the
    | application, particularly in receipts and reports.
    |
    */

    'name' => env('BUSINESS_NAME', 'iPharma Mart'),

    'address' => env('BUSINESS_ADDRESS', '123 Main Street, Barangay Sample, City Name, Province, Philippines'),

    'phone' => env('BUSINESS_PHONE', '+63 912 345 6789'),

    'email' => env('BUSINESS_EMAIL', 'info@ipharmamart.com'),

    'tin' => env('BUSINESS_TIN', 'XXX-XXX-XXX-XXX'),

    'permit_number' => env('BUSINESS_PERMIT', 'BUS-PERMIT-XXXX-XXXX'),

    'tagline' => env('BUSINESS_TAGLINE', 'Your Health, Our Priority'),

    // Optional: FDA License (for pharmacy)
    'fda_license' => env('BUSINESS_FDA_LICENSE', 'FDA-LICENSE-XXXX'),

    // Optional: Mayor's Permit
    'mayors_permit' => env('BUSINESS_MAYORS_PERMIT', 'PERMIT-XXXX-XXXX'),
];
