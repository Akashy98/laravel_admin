<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Roles
    |--------------------------------------------------------------------------
    |
    | This file contains the role constants used throughout the application.
    | These constants define the different user roles and their corresponding IDs.
    |
    */

    'ROLES' => [
        'ADMIN' => 1,
        'CUSTOMER' => 2,
        'ASTROLOGER' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Names (for display purposes)
    |--------------------------------------------------------------------------
    |
    | Human-readable names for each role
    |
    */

    'ROLE_NAMES' => [
        1 => 'Admin',
        2 => 'Customer',
        3 => 'Astrologer',
    ],

    // Dropdown enums and fixed values
    'ADDRESS_TYPES' => [
        'birth' => 'Birth',
        'current' => 'Current',
        'permanent' => 'Permanent',
        'temporary' => 'Temporary',
    ],
    'CONTACT_TYPES' => [
        'phone' => 'Phone',
        'emergency' => 'Emergency',
        'whatsapp' => 'WhatsApp',
        'telegram' => 'Telegram',
    ],
    'MARITAL_STATUSES' => [
        'single' => 'Single',
        'married' => 'Married',
        'divorced' => 'Divorced',
        'widowed' => 'Widowed',
        'separated' => 'Separated',
    ],
    'TIMEZONES' => [
        'Asia/Kolkata' => 'India Standard Time (IST)',
        'Asia/Delhi' => 'Delhi',
        'Asia/Mumbai' => 'Mumbai',
        'Asia/Chennai' => 'Chennai',
        'Asia/Kolkata' => 'Kolkata',
        'Asia/Calcutta' => 'Calcutta',
        'Asia/Dubai' => 'Dubai',
        'Asia/Singapore' => 'Singapore',
        'Asia/Kathmandu' => 'Kathmandu',
        'Asia/Dhaka' => 'Dhaka',
        'Asia/Karachi' => 'Karachi',
        'Asia/Colombo' => 'Colombo',
        'Asia/Bangkok' => 'Bangkok',
        'Asia/Tokyo' => 'Tokyo',
        'Asia/Hong_Kong' => 'Hong Kong',
        'Asia/Shanghai' => 'Shanghai',
        'Asia/Jakarta' => 'Jakarta',
        'Asia/Kuala_Lumpur' => 'Kuala Lumpur',
        'Asia/Seoul' => 'Seoul',
        'Asia/Manila' => 'Manila',
        'Asia/Riyadh' => 'Riyadh',
        'Asia/Tehran' => 'Tehran',
        'Asia/Tashkent' => 'Tashkent',
        'Asia/Yangon' => 'Yangon',
        'Asia/Baku' => 'Baku',
        'Asia/Baghdad' => 'Baghdad',
        'Asia/Beirut' => 'Beirut',
        'Asia/Damascus' => 'Damascus',
        'Asia/Jerusalem' => 'Jerusalem',
        'Asia/Kuwait' => 'Kuwait',
        'Asia/Muscat' => 'Muscat',
        'Asia/Qatar' => 'Qatar',
        'Asia/Rangoon' => 'Rangoon',
        'Asia/Saigon' => 'Saigon',
        'Asia/Tbilisi' => 'Tbilisi',
        'Asia/Thimphu' => 'Thimphu',
        'Asia/Ulaanbaatar' => 'Ulaanbaatar',
        'Asia/Vientiane' => 'Vientiane',
        'Asia/Yekaterinburg' => 'Yekaterinburg',
        'Asia/Yerevan' => 'Yerevan',
    ],
];
