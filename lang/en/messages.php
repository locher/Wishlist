<?php

declare(strict_types=1);

return [
    'age' => [
        'months' => '1 month|:count months',
        'years' => '1 year|:count years',
    ],
    'access' => [
        'required' => 'Choose a profile or continue as a guest.',
    ],
    'session' => [
        'profile_selected' => ':name profile selected.',
        'guest_enabled' => 'Guest access enabled.',
        'closed' => 'Session closed.',
    ],
    'profile' => [
        'created' => 'Profile created.',
        'updated' => 'Profile updated.',
        'deleted' => ':name profile deleted.',
    ],
    'gift' => [
        'created' => ':title added.',
        'updated' => ':title updated.',
        'deleted' => 'Gift deleted.',
        'already_reserved' => 'This gift is already reserved.',
        'list_not_reservable' => 'External lists cannot be reserved.',
        'reserved' => ':title reserved.',
        'reservation_cancelled' => 'Reservation cancelled.',
    ],
];
