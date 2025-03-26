<?php

namespace App\Tools;

use App\Entity\Event;

class EventValidator extends Event
{
    public static function validate($event): array
    {
        $error = [];

        if(empty($event->getNameEvent())){
            $error[] = "Veuillez renseigner un titre pour l'évènement";
        }
        if(empty($event->getNameGame())){
            $error[] = "Veuillez renseigner titre de jeux";
        }
        if(empty($event->getDateHourStart())){
            $error[] = "Veuillez renseigner une date et une heure à laquelle commence l'évènement";
        }
        if(empty($event->getDateHourEnd())){
            $error[] = "Veuillez renseigner une date et une heure à laquelle ce termine l'évènement";
        }
        if(empty($event->getNombreDeJoueurs())){
            $error[] = "Veuillez renseigner un nombre de joueurs";
        }
        if(empty($event->getDescription())){
            $error[] = "Veuillez apporter une description à l'évènement";
        }
        
        return $error;
    }

    public static function secureInput(string $input): string
    {
        return htmlspecialchars(trim($input));
    }

    public static function secureImage(string $input)
    {
        return filter_var(trim($input), FILTER_SANITIZE_URL);
    }

    /* vérification des caractères dans le nom du fichier, utili aussi pour les input titre, nom de jeu
    if (!preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\(\)\[\]:]{3,}$/', $gameName)) {
        $errors['game_name'] = 'Le nom n\'est pas valide.';
      }

      !preg_match() signifie que seuls les caractères indiqués sont autorisés, tandis que preg_match() sans ! détecte les caractères interdits  
      */
}