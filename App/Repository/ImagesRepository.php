<?php

// indique où ce situe le fichier
namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventImage;

class ImagesRepository extends MainRepository
{
    public function secureImage()
    {
        $destinationCover = "../../Assets/Documentation/Images/Couverture/";
        $destinationDiapo = "../../Assets/Documentation/Images/Diapo/";
        $uploadedFiles = [];
        $error = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //Traitement de l'image de couverture
            if(isset($_FILES['cover_image_path']) && $_FILES['cover_image_path']['error'] === UPLOAD_ERR_OK){

                $file = $_FILES['cover_image_path']['name'];

                $targetDir = $destinationCover.uniqid().'_'.basename($file);

                $allowedTypes = ['image/png','image/jpeg'];

                $maxSize = 2000000;

                if(in_array($_FILES['cover_image_path']['type'], $allowedTypes) && $_FILES['cover_image_path']['size']<= $maxSize){

                    if(move_uploaded_file($_FILES['cover_image_path']['tmp_name'], $targetDir)){
                        $uploadedFiles['cover_image_path'] = htmlspecialchars(basename($file));
                    } else {
                        $error['cover_image_path'] = "Erreur lors du téléchargement de l'image de couverture.";
                    } 
                } else {
                        $error['cover_image_path'] = "Type ou taille de fichier non valide pour l'image de couverture.";
                    }
            } elseif (isset($_FILES['cover_images_path']) && $_FILES['cover_image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $error['cover_image_path'] = "Erreur lors du téléchargement de l'image de couverture(code: ".$_FILES['cover_image_path']['error'] . ")";
                }
        }
        if(isset($_FILES['image_path']) && is_array($_FILES['image_path']['error'])){
            
            $files = $_FILES['image_path'];
            $uploadedFiles['image_path'] = [];
            $error['image_path'] = [];


            foreach($files['name'] as $key => $name){
                if($files['error'][$key] === UPLOAD_ERR_OK){

                    $file = $name;

                    $targetDir = $destinationDiapo.uniqid().'_'.basename($file);

                    $allowedTypes = ['image/png','image/jpeg'];

                    $maxSize = 2000000;

                    if(in_array($files['type'][$key], $allowedTypes) && $files['size'][$key]<= $maxSize){

                        if(move_uploaded_file($files['tmp_name'][$key], $targetDir)){

                            $uploadedFiles['image_path'][] = htmlspecialchars(basename($file));
                        } else {
                           $error['image_path'][$key] = "Erreur lors du téléchargement de ".htmlspecialchars(basename($file)).".";
                        }
                    } else {
                        $error['image_path'][$key] = "Type ou taille de fichier non valide pour ".htmlspecialchars(basename($file)).".";
                    }
                } elseif ($files['error'][$key] !== UPLOAD_ERR_NO_FILE){
                    $error['image_path'][$key] = "Erreur lors du téléchargement de ".htmlspecialchars(basename($name))."(code : ".$files['error'][$key].".";
                }
            }
        }
        return ['uploaded' => $uploadedFiles, 'errors' => $error];
    }
}