<?php
$routes = [
    'GET' => [
        '/'         => 'HomeControl@index',
        '/login'    => 'AuthControl@loginView',
        '/registro' => 'AuthControl@registroView',
        '/normas'   => 'HomeControl@normas'
    ],
    'POST' => [
        '/login'    => 'AuthControl@login',
        '/registro' => 'AuthControl@registrar'
    ]
];
?>