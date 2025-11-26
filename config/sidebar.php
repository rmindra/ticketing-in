<?php

return [
    'menu' => [
        [ 'header' => 'MAIN NAVIGATION' ],
        // User menu
        [
            'text' => 'My Tickets',
            'route' => 'tickets.index',
            'icon' => 'fas fa-ticket-alt',
            'can' => 'role:user'
        ],
        [
            'text' => 'Create Ticket',
            'route' => 'tickets.create',
            'icon' => 'fas fa-plus',
            'can' => 'role:user'
        ],
        [
            'text' => 'Profile',
            'route' => 'profile.show',
            'icon' => 'fas fa-user',
            'can' => 'role:user',
            'can' => 'role:admin'
        ],
        [
            'text' => 'Users',
            'route' => 'admin.users.index',
            'icon' => 'fas fa-users',
            'can' => 'role:admin'
        ],
    ],
];
