<?php

require_once __DIR__.'/config_const.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'httponly' => true
]);

session_start();

if (!isset($_COOKIE['csrf_token'])) {
    $token = bin2hex(random_bytes(32));
    setcookie(
        'csrf_token',
        $token,
        time() + 3600,
        '/',
        '',
        false,
        true
    );
    $_COOKIE['csrf_token'] = bin2hex(random_bytes(32));
}

define('_ROOTPATH_', __DIR__);
define('_TEMPLATEPATH_', __DIR__.'/templates');
spl_autoload_register();

use App\Controller\Controller;
use App\Entity\User;


$controller = new Controller;
$controller->route();

?>