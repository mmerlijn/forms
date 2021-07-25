<?php

return [
    'steps' => [
        10 => "onderzoek",
        20 => "upload",
        30 => "analyse",
        40 => "beoordeling",
        42 => "beoordeling2",
        50 => "behandeladvies",
        60 => "controle",
        100 => "printen",
        110 => "verzenden",
        200 => "afgerond",
    ],
    'models'=>[
        'user' => \App\Models\User::class,
        'messages' => \App\Models\msg\Message::class,
        'patient' => \App\Models\msg\Patient::class,
        'appointment' => \App\Models\pa\Appointment::class,
        'room' => \App\Models\pa\Room::class,
        'location' => \App\Models\pa\Location::class,
        'requester' => \App\Models\tool\Aanvrager::class,
    ],
    'tables'=>[
        'test' => 'frm_tests',
        'testdetails' => 'frm_testdetails',
    ],
    'questions'=>[
        //name => question class
        'patient' => \mmerlijn\forms\Classes\Questions\Patient::class,
        'onderzoek' => \mmerlijn\forms\Classes\Questions\Onderzoek::class,
        'beoordeling' => \mmerlijn\forms\Classes\Questions\Beoordeling::class,
        'requester' => \mmerlijn\forms\Classes\Questions\Requester::class,
        'requester_cc' => \mmerlijn\forms\Classes\Questions\RequesterCc::class,
        'tropicamide' => \mmerlijn\forms\Classes\Questions\Fundus\Tropicamide::class,
    ],
    'tests' => [
        'example' => [
            'anonymous'=>false,
            'name' => 'example',
            'steps' => [10, 40, 100, 110, 200],
            'questions' => [
                10 => ['onderzoek','patient','requester','requester_cc'],
                40 => ['beoordeling','tropicamide']
            ],
        ]
    ],
];