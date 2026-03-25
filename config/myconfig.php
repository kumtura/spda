<?php

return [
    'devUrl' => env('DEV_URL', url('administrator')), 
    'assetsUrl' => env('ASSETS_URL', url('public')),
    'jk_tenaga' => array("Perempuan","Laki - Laki"), 
    'status_tenaga' => array("Belum Bekerja","Interview","Aktif Bekerja"),
    
    'roles' => [
        1 => 'Bendesa Adat',
        2 => 'Kelian Adat',
        3 => 'Unit Usaha',
    ],
    
    'level' => [
        'bendesa' => 1,
        'kelian'  => 2,
        'usaha'   => 3,
    ]
];
