<?php

namespace App\Repository;

use App\Entity\Event;

class EventRepository extends MainRepository
{
    //Affichage des derniers évènements ajouté en base de données
    public function homeDisplay()
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.description AS description,
            e.cover_image_path AS cover,
            pl.name AS plateforme_name,
            u.pseudo AS organisateur
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            ORDER BY RAND()
            LIMIT 8');

        $query->execute();

        $event = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $event;
    }

    //Afficher les évènements de l'utilisateur
    public function myEvents(int $userId): array
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.description AS description,
            e.cover_image_path AS cover,
            e.visibility AS visibility,
            e.status AS status,
            pl.name AS plateforme_name,
            u.id_user AS user_id_db,
            u.pseudo AS organisateur,
            GROUP_CONCAT(DISTINCT ei.image_path SEPARATOR ", ") AS diaporama 
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            LEFT JOIN event_image ei ON ei.fk_id_event = e.id_event
            WHERE e.fk_id_user = :userId
            GROUP BY e.id_event
            ORDER BY e.date_hour_start DESC
        ');

        $query->execute([':userId' => $userId]);

        $event = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $event;
    }

    //Récupération d'un évènement en base de données par son id
    public function findEventById(int $id)
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.description AS description,
            e.cover_image_path AS cover,
            e.visibility AS visibility,
            e.status AS status,
            pl.name AS plateforme_name,
            u.pseudo AS organisateur,
            GROUP_CONCAT(DISTINCT ei.image_path SEPARATOR ", ") AS diaporama             
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            LEFT JOIN event_image ei ON ei.fk_id_event = e.id_event
            WHERE e.id_event = :id
            GROUP BY e.id_event
        ');

        $query->bindParam(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();

        $eventDetail = $query->fetch($this->pdo::FETCH_ASSOC);

        return $eventDetail;
    }

    //Affichage des informations global pour un évènement au statut validé
    public function findValidate()
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.description AS description,
            e.visibility AS visibilite,
            e.cover_image_path AS cover,
            pl.name AS plateforme,
            u.pseudo AS organisateur,
            GROUP_CONCAT(DISTINCT ei.image_path SEPARATOR ", ") AS diaporama
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            LEFT JOIN event_image ei ON ei.fk_id_event = e.id_event
            WHERE e.status = :status_valide
            GROUP BY e.id_event
            ORDER BY e.date_hour_start DESC
          ');

        $statusValide = 'validé';
        $query->bindParam(':status_valide', $statusValide, $this->pdo::PARAM_STR);

        $query->execute();
        $events = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $events;
    }

    //Récupération d'un évènement pour inscription
    public function findOneById(int $eventId)
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.status AS status
            FROM event AS e
            WHERE e.id_event = :eventId
            ');

        $query->bindParam(':eventId', $eventId, $this->pdo::PARAM_STR);

        $query->execute();
        $event = $query->fetch($this->pdo::FETCH_ASSOC);

        return $event;
    }

    public function findEventAdmin()
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.name_game AS game_name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.description AS description,
            e.visibility AS visibilite,
            e.cover_image_path AS cover,
            pl.name AS plateforme,
            u.pseudo AS organisateur,
            GROUP_CONCAT(DISTINCT ei.image_path SEPARATOR ", ") AS diaporama
            FROM event AS e
            INNER JOIN plateforme pl ON e.fk_id_plateforme  = pl.id_plateforme
            INNER JOIN user u ON e.fk_id_user = u.id_user
            LEFT JOIN event_image ei ON ei.fk_id_event = e.id_event
            GROUP BY e.id_event
            ORDER BY e.status ASC, e.date_hour_start DESC
          ');

        $query->execute();
        $events = $query->fetchAll($this->pdo::FETCH_ASSOC);

            return $events;
    }

    //Récupération de toutes les plateformes en base de données
    public function getAllPlateformes()
    {
        $query = $this->pdo->prepare('SELECT id_plateforme, name FROM plateforme');
        $query->execute();
        $plateformes = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $plateformes;

    }

    //Insertion des données de l'évènement dans la base de données
    public function insertEvent(Event $event): ?int
    {
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
        $queryEvent->bindValue(':cover_image_path', htmlspecialchars(trim($event->getCoverImagePath())), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':name_event', htmlspecialchars($event->getNameEvent()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':name_game', htmlspecialchars($event->getNameGame()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':fk_id_plateforme', $event->getFkIdPlateforme(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':date_hour_start', $event->getDateHourStart()->format('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':date_hour_end', $event->getDateHourEnd()->format('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue('nombre_de_joueurs', $event->getNombreDeJoueurs(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':description', trim($event->getDescription()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':visibility', htmlspecialchars(trim($event->getVisibility()->value), ENT_QUOTES, 'UTF-8'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue('fk_id_user', $event->getFkIdUser(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':status', $event->getStatus()->value, $this->pdo::PARAM_STR);

        $executeResult = $queryEvent->execute();

        if ($executeResult) {
            return (int) $this->pdo->lastInsertId();
        } else {
            return null;
        }
    }

    //Mise à jour des données de l'évènement dans la base de données
    public function updateEvent(Event $event): bool
    {
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
        $queryEvent->bindValue(':status', $event->getStatus()->value, $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':cover_image_path', htmlspecialchars(trim($event->getCoverImagePath())), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':name_event', htmlspecialchars($event->getNameEvent()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':name_game', htmlspecialchars($event->getNameGame()), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':fk_id_plateforme', $event->getFkIdPlateforme(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':date_hour_start', $event->getDateHourStart()->format('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':date_hour_end', $event->getDateHourEnd()->format('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue('nombre_de_joueurs', $event->getNombreDeJoueurs(), $this->pdo::PARAM_INT);
        $queryEvent->bindValue(':description', htmlspecialchars(trim($event->getDescription()), ENT_QUOTES, 'UTF-8'), $this->pdo::PARAM_STR);
        $queryEvent->bindValue(':visibility', htmlspecialchars(trim($event->getVisibility()->value), ENT_QUOTES, 'UTF-8'), $this->pdo::PARAM_STR);

        return $queryEvent->execute();
    }

    //Insertion des images du diaporama dans la table event_image
    public function insertEventImage(int $eventId, array $uploadedDiapoImages = []): void
    {
        if (! empty($uploadedDiapoImages)) {
            $queryImage = $this->pdo->prepare(
                "INSERT INTO event_image (fk_id_event, image_path, image_order)
                VALUES (:fk_id_event, :image_path, :image_order)"
            );
            $queryImage->bindValue(':fk_id_event', $eventId, $this->pdo::PARAM_INT);

            foreach ($uploadedDiapoImages as $order => $imagePath) {
                $queryImage->bindValue(':image_path', htmlspecialchars(trim($imagePath)), $this->pdo::PARAM_STR);
                $queryImage->bindValue(':image_order', $order + 1, $this->pdo::PARAM_INT);
                $queryImage->execute();
            }
        }
    }

    //Mise à jour des images du diaporama dans la table event_image
    public function updateEventImage(int $eventId, array $uploadedDiapoImages = []): void
    {
        $queryUpdateImage = $this->pdo->prepare(
            "DELETE FROM event_image WHERE fk_id_event = :fk_id_event"
        );
        $queryUpdateImage->bindValue(':fk_id_event', $eventId, $this->pdo::PARAM_INT);
        $queryUpdateImage->execute();

        $this->insertEventImage($eventId, $uploadedDiapoImages);
    }

    public function inscriptionEvent(int $id)
    {
        $query = $this->pdo->prepare('SELECT
            e.id_event AS id,
            e.name_event AS name,
            e.date_hour_start AS start,
            e.date_hour_end AS end,
            e.nombre_de_joueurs AS joueurs,
            e.status AS status,
            u.id_user AS user_id_db,
            u.pseudo AS organisateur
            FROM event AS e
            INNER JOIN user u ON e.fk_id_user = u.id_user
            WHERE e.fk_id_user = :userId
            GROUP BY e.id_event
            ORDER BY e.date_hour_start DESC
        ');

        $query->execute([':userId' => $id]);

        $event = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $event;

    }

    // Requête qui supprimer l'évènement
    public function deleteEvent(int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM event WHERE id_event = :id");
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        return $query->execute();
    }
}
