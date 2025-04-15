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

        if($_SERVER['REQUEST_METHOD'] === $_POST){

            if(isset($_FILES['cover_image_path']) && $_FILES['cover_image_path']['error'] === UPLOAD_ERR_OK){

                $file = $_FILES['cover_image_path']['name'];

                $target_dir = $destinationCover.basename($file);

                $allowed_types = ['image/png','image/jpeg'];

                if(in_array($_FILES['cover_image_path']['type'], $allowed_types) && $_FILES['cover_image_path']['size']<=2000000){
                    
                    $unique_filename = uniqid().'_'.basename($file);

                    $target_dir = $destinationCover.$unique_filename;

                    if(move_uploaded_file($_FILES['cover_image_path']['tmp_name'], $target_dir)){

                        echo "Le fichier".htmlspecialchars(basename($file))." a bien été téléchargé.";
                    } else {
                        echo "Erreur lors du téléchargement de l'image de couverture.";
                    } 
                } else {
                        echo "Type ou taille de fichier non valide pour l'image de couverture.";
                }
            }
        }
        if(isset($_FILES['image_path']) && is_array($_FILES['image_path']['error'])){
            
            $files = $_FILES['image_path'];

            $uploaded_files = [];

            foreach($files['name'] as $key => $name){
                if($files['error']['$key'] === UPLOAD_ERR_OK){

                    $file = $name;

                    $target_dir = $destinationDiapo.basename($file);

                    $allowed_types = ['image/png','image/jpeg'];

                    if(in_array($files['type'][$key], $allowed_types) && $files['size']['key']<=2000000){

                        $unique_filename = uniqid().'_'.basename($file);

                        $target_dir = $destinationDiapo.$unique_filename;

                        if(move_uploaded_file($files['tmp_name'][$key], $target_dir)){

                            $uploaded_files[] = htmlspecialchars(basename($file));
                        } else {
                            echo "Erreur lors du téléchargement de ".htmlspecialchars(basename($file)).".";
                        }
                    } else {
                        echo "Type ou taille de fichier non valide pour ".htmlspecialchars(basename($file)).".";
                    }
                }
            }
            if(!empty ($uploaded_files)){
                echo "Les fichiers suivants on été téléchargés: ".implode(",",$uploaded_files);
            }
        }
    }
}