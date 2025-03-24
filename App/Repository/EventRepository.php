<?php

// indique où ce situe le fichier
namespace App\Repository;

use App\Entity\Event;

class EventRepository extends MainRepository
{
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

    public function getAllPlateformes()
    {
        $query = $this->pdo->prepare('SELECT id_plateforme, name FROM plateforme');
        $query->execute();
        $plateformes = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $plateformes;

    }

    public function creationEvent(array $data)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $query = $this->pdo->prepare("
        CREATE PROCEDURE insert_in_multiple_tables
        @image_couv,
        @image_diapo,
        @name_event,
        @name_game,
        @plateforme,
        @date_hour_start,
        @date_hour_end,
        @nombre_de_joueurs,
        @description,
        @visibility,

        AS
            BEGIN
                BEGIN TRANSACTION

                INSERT INTO plateforme (name)
                    VALUES (@plateforme);
                        DECLARE @id_plateforme INT = SCOPE_IDENTITY();

                INSERT INTO event (name_event, name_game, date_hour_start, date_hour_end, nombre_de_joueurs, description, visibility, fk_id_user, fk_id_plateforme, cover_image_path)
                    VALUES (@name_event, @name_game, @date_hour_start, @date_hour_end, @nombre_de_joueurs, @description, @visibility, @fk_id_user, @fk_id_plateforme, @cover_image)
                        DECLARE @id_event INT = SCOPE_IDENTITY();

                INSERT INTO event_image (image_path, image_order, fk_id_event)
                    VALUE (@image_path, @image_order, @fk_id_event)
                        DECLARE @id_image_path INT = SCOPE_IDENTITY();

            COMMIT TRANSACTION;
        END;

    ");

            $query->bindValue(':developpeur', $data['developpeur'], $this->pdo::PARAM_STR);
            $query->bindValue(':editeur', $data['editeur'], $this->pdo::PARAM_STR);
            $query->bindValue(':date_de_sortie', $data['date_de_sortie'], $this->pdo::PARAM_STR);
            $query->bindValue(':game_name', $data['game_name'], $this->pdo::PARAM_STR);
            $query->bindValue(':description', $data['description'], $this->pdo::PARAM_STR);
            $query->bindValue(':image', $data['image'], $this->pdo::PARAM_STR);
            $query->bindValue(':pegi', $data['pegi'], $this->pdo::PARAM_INT);
            $query->bindValue(':genre', $data['genre'], $this->pdo::PARAM_STR);

            return $query->execute();

        }
    }

    public function persist(Event $event)
    {
        // requête qui insère l'utilisateur
        if ($event->getIdEvent() !== null) {
            $query = $this->pdo->prepare(
                "UPDATE event SET name_event = :name_event, name_game = :name_game, date_hour_start = :date_hour_start,
                                        date_hour_end = :date_hour_end, nombre_de_joueurs = :nombre_de_joueurs, description = :description, visibility = :visibility
                                        WHERE id_user = :id"
            );
            $query->bindValue(':id', $event->getIdEvent(), $this->pdo::PARAM_INT);
        } else {
            $query = $this->pdo->prepare(
                "INSERT INTO event (name_event, name_game, date_hour_start, date_hour_end, nombre_de_joueurs, description, visibility)
                                        VALUES (:name_event, :name_game, :date_hour_start, :date_hour_end, :nombre_de_joueurs, :description; :visibility)"
            );
            /*$query->bindValue(':role', $event->getRole(), $this->pdo::PARAM_STR);*/
        }

        $query->bindValue(':last_name', htmlspecialchars($event->getLastName()), $this->pdo::PARAM_STR);
        $query->bindValue(':first_name', htmlspecialchars($event->getFirstName()), $this->pdo::PARAM_STR);
        $query->bindValue(':mail', htmlspecialchars($event->getMail()), $this->pdo::PARAM_STR);
        $query->bindValue(':pseudo', htmlspecialchars($event->getPseudo()), $this->pdo::PARAM_STR);
        $query->bindValue(':password', htmlspecialchars(password_hash($event->getPassword(), PASSWORD_DEFAULT)), $this->pdo::PARAM_STR);

        return $query->execute();
    }

    /*public function promoDisplay()
    {
        $query = $this->pdo->prepare('SELECT
            g.name AS name,
            p.label AS pegi_name,
            s.price AS specification_price,
            pl.name AS plateforme_name,
            s.promo AS promo,
            g.id_jeu AS id
            FROM games AS g
            INNER JOIN specifications s ON g.id_jeu = s.id_jeu
            INNER JOIN plateforme pl ON s.id_plateforme = pl.id_plateforme
            INNER JOIN pegi p ON g.id_pegi = p.id_pegi
            WHERE s.discounted = 1
            ORDER BY RAND()
            LIMIT 6
            ');


        $query->execute();

        $game = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $game;
    }*/
}
