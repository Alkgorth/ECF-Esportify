<?php

// indique où ce situe le fichier
namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventImage;

class EventRepository extends MainRepository
{
    //Affichage des derniers évènements ajouté en base de données
    public function homeDisplay()
    {
        $query = $this->pdo->prepare('SELECT
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.description AS description,
            pl.name AS plateforme_name,
            u.pseudo AS organisateur
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            ORDER BY RAND()
            LIMIT 4');

        $query->execute();

        $event = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $event;
    }

    //Affichage des informations global pour un évènement
    public function findGlobal(int $id)
    {

        $query = $this->pdo->prepare('SELECT
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombrede_joueurs AS joueurs,
            e.description AS description,
            e.visibility AS visibilite,
            pl.name AS plateforme,
            u.pseudo AS organisateur,
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            WHERE e.id_event = :id
          ');

        $query->bindParam(':id', $id, $this->pdo::PARAM_INT);

        $query->execute();
        $global = $query->fetch($this->pdo::FETCH_ASSOC);
        if ($global) {

            return $global;
        }
        return false;
    }

    //Récupération de toutes les plateformes en base de données
    public function getAllPlateformes()
    {
        $query = $this->pdo->prepare('SELECT id_plateforme, name FROM plateforme');
        $query->execute();
        $plateformes = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $plateformes;

    }


    //Création d'un évènement en base de données
    public function persistEvent(Event $event, array $diapoImage = []){

        //requête pour mettre à jour l'évènement
        if ($event->getIdEvent() !== null) {
            $queryEvent = $this->pdo->prepare(
                "UPDATE event SET cover_image_path = :cover_image_path,
                                name_event = :name_event,
                                name_game = :name_game,
                                fk_id_plateforme = :fk_id_plateforme,
                                date_hour_start = :date_hour_start,
                                date_hour_end = :date_hour_end,
                                nombre_de_joueurs = :nombre_de_joueurs,
                                description = :description,
                                visibility = :visibility,
                                status = :status
                                WHERE id_event = :id"
            );
            $queryEvent->bindValue(':id', $event->getIdEvent(), $this->pdo::PARAM_INT);
            $queryEvent->bindValue(':status', $event->getStatus(), $this->pdo::PARAM_STR);

        } else {
            $queryEvent = $this->pdo->prepare(
                "INSERT INTO event (cover_image_path, name_event, name_game,
                                    fk_id_plateforme, date_hour_start,
                                    date_hour_end, nombre_de_joueurs,
                                    description, visibility, fk_id_user, status)
                VALUES (:cover_image_path, :name_event,
                        :name_game, :fk_id_plateforme,
                        :date_hour_start, :date_hour_end,
                        :nombre_de_joueurs, :description,
                        :visibility, :fk_id_user, :status)"
            );
            $queryEvent->bindValue(':statut', $event->getStatus(), $this->pdo::PARAM_STR);
            $queryEvent->bindValue('fk_id_user', $event->getFkIdUser(), $this->pdo::PARAM_INT);
        }
        $queryEvent->bindValue(':cover_image_path', htmlspecialchars(trim($event->getCoverImagePath())), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':name_event', htmlspecialchars($event->getNameEvent()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':name_game', htmlspecialchars($event->getNameGame()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':fk_id_plateforme', $event->getFkIdPlateforme(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':date_hour_start', $event->getDateHourStart()->format('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':date_hour_end', $event->getDateHourEnd()->format('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue('nombre_de_joueurs', $event->getNombreDeJoueurs(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':description', htmlspecialchars(trim($event->getDescription()), ENT_QUOTES, 'UTF-8'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':visibility', htmlspecialchars(trim($event->getVisibility()->value), ENT_QUOTES, 'UTF-8'), $this->pdo::PARAM_STR);
        

        $queryEvent->execute();

        $eventId = $this->pdo->lastInsertId();

        if($event->getIdEvent() !== null && !empty ($diapoImage)) {
            $queryImage = $this->pdo->prepare(
                "INSERT INTO event_image (fk_id_event, image_path, image_order)
                VALUES (:fk_id_event, :image_path, :image_order)"
            );
        }
        foreach ($diapoImage as $order => $imagePath) {
            $queryImage->bindValue(':fk_id_event', $eventId, $this->pdo::PARAM_INT);
            $queryImage->bindValue(':image_path', htmlspecialchars(trim($imagePath)), $this->pdo::PARAM_STR);
            $queryImage->bindValue(':image_order', $order + 1, $this->pdo::PARAM_INT);
            $queryImage->execute();
        }
        
        return $eventId;
    }
}
