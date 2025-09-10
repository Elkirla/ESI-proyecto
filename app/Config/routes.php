<?php
$routes = [
    'GET' => [
        "/"         => "HomeControl@index",
        "/normas"   => "HomeControl@normas",
        "/exitoregistro" => "HomeControl@exitoregistro",
        "/login"    => "AuthControl@loginView",
        "/registro" => "AuthControl@registroView",

    ],
    "POST" => [
        "/login"    => "AuthControl@login",
        "/registro" => "AuthControl@registrar"
    ]
];
?>
