<?php

return [
    'devUrl' => env('DEV_URL', url('administrator')), 
    'assetsUrl' => env('ASSETS_URL', url('storage')),
    'jk_tenaga' => array("Perempuan","Laki - Laki"), 
    'status_tenaga' => array("Belum Bekerja","Interview","Aktif Bekerja"),
    
    'roles' => [
        1 => 'Bendesa Adat',
        2 => 'Kelian Adat',
        3 => 'Unit Usaha',
        4 => 'Admin Sistem',
        5 => 'Ticket Counter',
    ],
    
    'level' => [
        'bendesa'        => 1,
        'kelian'         => 2,
        'usaha'          => 3,
        'admin'          => 4,
        'ticket_counter' => 5,
    ]
];
