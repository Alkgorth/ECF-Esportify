<?php

namespace App\Tools;

use App\Entity\Event;

class EventValidator extends Event
{
    public static function validate(Event $event): array
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
}