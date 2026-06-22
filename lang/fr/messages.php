<?php

declare(strict_types=1);

return [
    'age' => [
        'months' => '1 mois|:count mois',
        'years' => '1 an|:count ans',
    ],
    'access' => [
        'required' => 'Choisis un profil ou continue en invité.',
    ],
    'session' => [
        'profile_selected' => 'Profil :name sélectionné.',
        'guest_enabled' => 'Accès invité activé.',
        'closed' => 'Session fermée.',
    ],
    'profile' => [
        'created' => 'Profil créé.',
        'updated' => 'Profil modifié.',
        'deleted' => 'Profil :name supprimé.',
    ],
    'gift' => [
        'created' => ':title ajouté.',
        'updated' => ':title modifié.',
        'deleted' => 'Cadeau supprimé.',
        'already_reserved' => 'Ce cadeau est déjà réservé.',
        'list_not_reservable' => 'Une liste externe ne peut pas être réservée.',
        'reserved' => ':title réservé.',
        'reservation_cancelled' => 'Réservation annulée.',
    ],
];
