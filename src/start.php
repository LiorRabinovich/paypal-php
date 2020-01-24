<?php

use PayPal\Api\OpenIdUserinfo;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

session_start();

if(!defined("PP_CONFIG_PATH")) {
    define("PP_CONFIG_PATH", 'config/');
}

$_SESSION['user_id'] = 1;

require __DIR__ . '/../vendor/autoload.php';

// API
$api = new ApiContext(
    new OAuthTokenCredential(
        'Ac8yye5F-qCwO7at2DUWQujpFpdQhjLgEgnsNyar4mcVKVMtTPCU-DIduINmjSi_XKhoa0yxAWLZTryK',
        'EKCWURAgxcN50NockW1c9srTIkDshoGaN7eSdIIH6JKL_HIFregMhBvtT7sll1RnWjdGvgGkqAFlAhXf'
    )
);

$api->setConfig([
    'mode' => 'sandbox',
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE',
    'validation.level' => 'log'
]);

$db = new PDO('mysql:host=localhost;dbname=paypal', 'root', '');

$user = $db->prepare('
    SELECT * FROM users
    WHERE id = :user_id
');

$user->execute(['user_id' => $_SESSION['user_id']]);

$user = $user->fetchObject();