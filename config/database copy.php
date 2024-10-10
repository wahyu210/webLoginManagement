<?php
// namespace MochamadWahyu\Phpmvc\Config;

function getDatabaseConfig():array{
    return [
        'database' => [
            'test' => [
                'url' => 'mysql:host=localhost:3308;dbname=php_login_management_test',
                'username' => "root",
                "password" => "pwdpwd8",
            ], 'prod' => [
                    'url' => 'mysql:host=localhost:3308;dbname=php_login_management',
                    'username' => "root",
                    "password" => "pwdpwd8",
                ],
        ],
    ];
}