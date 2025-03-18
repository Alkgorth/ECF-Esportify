<?php

// indique où ce situe le fichier
namespace App\Repository;

use App\Db\Mysql;
use App\Entity\Game;

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

    /*public function getAllGenre()
    {
        $query = $this->pdo->prepare('SELECT id_genre, name FROM genre');
        $query->execute();
        $genres = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $genres;
        
    }

    public function promoDisplay()
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
