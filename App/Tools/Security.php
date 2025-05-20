<?php
namespace App\Tools;

use App\Repository\UserRepository;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

//Load Composer's autoloader
require 'vendor\autoload.php';

class Security
{
    //Sécurisation des champs pouvant être envoyé par un utilisateur.
    public static function secureInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    //Hashage du mot de passe
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //Vérification du mot de passe
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    //Vérification si un utilisateur est enregistré en session
    public static function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function csrfToken()
    {
        if (! empty($_POST)) {
            if (! isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
                die('Token CSRF invalide');
            }
        }
        return $_COOKIE['csrf_token'];
    }

}
