<?php

namespace App\Repository;

use App\Entity\EventImage;

class ImagesRepository extends MainRepository
{
    //Récupération de toutes les images de diaporama en base de données
    public function getAllDiapos()
    {
        $query = $this->pdo->prepare('SELECT image_path, name FROM event_image');
        $query->execute();
        $imageDiapos = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $imageDiapos;
    }
}
