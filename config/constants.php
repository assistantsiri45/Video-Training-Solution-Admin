<?php

return [
    'sms' => [
        'driver_class' => env('SMS_DRIVER_CLASS', 'K3SmsChannel')
    ],

    //Start MAK
    /*
    |--------------------------------------------------------------------------
    | Backend Constants
    |--------------------------------------------------------------------------
    */

    'backend_url_prefix' => 'quiz',
    'uploads' => [
        'image' => 'uploads/images',
        'pdf' => 'uploads/pdf',
        'video' => 'uploads/videos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Backend Crud Messages
    |--------------------------------------------------------------------------
    */

    'message' => [
        'save' => 'Successfully save the information',
        'update' => 'Successfully update the information',
        'delete' => 'Successfully delete the information',
    ],

    /*
    |--------------------------------------------------------------------------
    | Backend App Setting
    |--------------------------------------------------------------------------
    */
    'app' => [
        'creator' => [
            'name' => 'Mohit Jindal',
            'email' => 'mohit@datavoice.co.in',
            'url' => 'https://github.com/mvishu405',
        ],
        'developers' => [
            'name' => 'Datavoice Solution PVT LTD',
            'email' => 'mohit@datavoice.co.in',
            'url' => 'http://www.datavoice.co.in',
        ],
        'url' => env('APP_URL', 'javascript:void(0);'),
        'logo' => [
            'logo' => 'http://www.placehold.it/200x70',
            'mini' => 'http://www.placehold.it/50x50',
            'lg' => 'http://www.placehold.it/50x50',
            'profile' => 'http://placehold.it/200x200',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backend Route Setting
    |--------------------------------------------------------------------------
    */

    'register' => true,

    'qt' => [
        'SCQ' => 'Multiple Choice Single Select',
        'MCQ' => 'Multiple Choice Multiple Select',
    ],

    'ct' => [
        'Text'     => 'Text',
        'Image'    => 'Image',
        'Video'    => 'Video',
        'Audio'    => 'Audio',
        'Document' => 'Document',
    ],

    'difficult' => [
        'Easy'     => 'Easy',
        'Medium'    => 'Medium',
        'Hard'    => 'Hard',
    ],

    'et' => [
        // 'Practice'     => 'Practice test',
        // 'Quiz'    => 'Live Quiz',
        // 'Olympiad'    => 'Olympiad',
        'Competition'    => 'Competition',
    ],

    'eat' => [
        'public'     => 'public',
        'private'    => 'private',
    ],

    'atq' => [
        'Question'     => 'Question',
        // 'Practice set' => 'Practice set',
        // 'Live Quiz'    => 'Live Quiz',
        // 'Olympiad'     => 'Olympiad',
    ],
    // End MAK
];
