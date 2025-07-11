<?php

namespace App\Tools;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tools\Security;

class UserValidator extends User
{
    private const MIN_PASSWORD_LENGHT = 12;

    public static function validate(User $user): array
    {
        $error = [];

        if(empty($user->getLastName())){
            $error[] = "Veuillez renseigner votre nom";
        }
        if(empty($user->getFirstName())){
            $error[] = "Veuillez renseigner votre prénom";
        }
        if(empty($user->getMail())){
            $error[] = "Veuillez renseigner votre email";
        } else if (!filter_var($user->getMail(), FILTER_VALIDATE_EMAIL)) {
            $error[] = "Veuillez renseigner un email valide";
        }
        if(empty($user->getPseudo())){
            $error[] = "Veuillez renseigner votre pseudo";
        }
        if(empty($user->getPassword())){
            $error[] = "Veuillez renseigner un mot de passe";
        } else {
            $password = Security::secureInput($user->getPassword());
            $passwordLenght = mb_strlen($password);
            
            if ($passwordLenght < self::MIN_PASSWORD_LENGHT){
                $error[] = "Le mot de passe doit contenir au moins" . self::MIN_PASSWORD_LENGHT . "caractères.";
            }
            if (preg_match('/^(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{12,}$/', $password)){
                $error[] = "Le mot de passe doit contenir au moins un caractère spécial.";
            }
        }
        if ($_POST['password'] !== $_POST['passwordConfirm']) {
            $error[] = "Les mots de passe ne correspondent pas.";
        }
        return $error;
    }

    public static function validatePasswords($password, $passwordConfirm) {
        $error = [];
        if ($password !== $passwordConfirm) {
            $error[] = "Les mots de passe ne correspondent pas.";
        }
        return $error;
    }

    public static function getCurrentUserMail(): int|bool
    {
        if((isset($_SESSION['user']) && isset($_SESSION['user']['mail']))) {
            return $_SESSION['user']['mail'];
        } else {
            return false;
        }
    }

    public static function getCurrentUserId(): int|bool
    {
        if((isset($_SESSION['user']) && isset($_SESSION['user']['id']))) {
            return $_SESSION['user']['id'];
        } else {
            return false;
        }
    }

    // Déconnecte l'utilisateur si la session a expiré
    public static function timerActivity(): bool {
        if (isset($_SESSION['user']['last_activity']) && (time() - $_SESSION['user']['last_activity'] > 1800)) { 
            self::logout();
        }
        return false;
    }
    
    // Détruit la session et supprime les cookies de session
    public static function logout(): void {
        session_unset();
        session_destroy();
    }

    // Vérification si un utilisateur est en Session et de son rôle
    public static function isJoueur(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'joueur';
    }

    // Vérification si un utilisateur est en Session et de son rôle
    public static function isOrga(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'organisateur';
    }

    // Vérification si un utilisateur est en Session et de son rôle
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'administrateur';
    }
}
