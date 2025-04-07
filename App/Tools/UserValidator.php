<?php

namespace App\Tools;

use App\Entity\User;
use App\Repository\UserRepository;

class UserValidator extends User
{
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

    // public static function isLoggedIn(): bool
    // {
    //     // on vérifie si il y a un user en session, puis on vérifie si cette utilisateur a le rôle user
    //     return isset($_SESSION['user']) && $_SESSION['user']['role'] !== null;
    // }

    

    public static function isLoggedIn(): bool {
        // Vérifie si la session est définie et si l'ID de l'utilisateur est présent
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            return false;
        }
    
        // Vérifie l'expiration de session (exemple : 30 minutes d'inactivité)
        if (isset($_SESSION['user']['last_activity']) && (time() - $_SESSION['user']['last_activity'] > 1800)) { 
            self::logout(); // Déconnecte l'utilisateur si la session a expiré
            return false;
        }
    
        // Met à jour le timestamp de la dernière activité
        $_SESSION['user']['last_activity'] = time();
    
        // Vérifie le rôle de l'utilisateur en récupérant les rôles depuis la base de données
        return self::isRoleValid($_SESSION['user']['role'] ?? '');
    }
    
    private static function isRoleValid(string $role): bool {
        // Récupère les rôles autorisés depuis la base de données (exemple)
        $rolesAutorises = Database::getRolesAutorises(); // Remplacez par votre logique
    
        // Vérifie si le rôle est valide
        if (!in_array($role, $rolesAutorises)) {
            // Journalisation ou gestion des erreurs
            error_log("Rôle invalide détecté : " . $role);
            return false;
        }
        return true;
    }
    
    public static function logout(): void {
        // Détruit la session et supprime les cookies de session
        session_unset();
        session_destroy();
    }




    public static function isJoueur(): bool
    {
        // on vérifie si il y a un user en session, puis on vérifie si cette utilisateur a le rôle user
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'joueur';
    }

    public static function isOrga(): bool
    {
        // on vérifie si il y a un admin en session, puis on vérifie si cette utilisateur a le rôle admin
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'organisateur';
    }

    public static function isAdmin(): bool
    {
        // on vérifie si il y a un admin en session, puis on vérifie si cette utilisateur a le rôle admin
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'administrateur';
    }

}
