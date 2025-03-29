<?php
namespace App\Tools;

use App\Entity\Event;
use App\Repository\PlateformeRepository;
use DateTimeImmutable;

class EventValidator
{
    //Vérifie si les champs du formaulaire de création d'évènements son correctement renseignés.
    public static function validateEvent($event): array
    {
        $error = [];

        if(empty($event->getCoverImagePath())){
            $error[] = "Veuillez ajouter au moins une image de couverture pour l'évènement";
        }
            
        if (empty($event->getNameEvent())) {
            $error[] = "Veuillez renseigner un titre pour l'évènement !";
        } elseif (strlen($event->getNameEvent()) > 100) {
            $error[] = "Le titre est trop long, taille maximum autorisé 100 caractères.";
        }

        if (empty($event->getNameGame())) {
            $error[] = "Veuillez renseigner titre de jeux";
        } elseif (strlen($event->getNameGame()) > 100) {
            $error[] = "Le nom du jeu est trop long, taille maximum autorisé 100 caractères.";
        }

        $event->setFkIdPlateforme($_POST['name_plateforme'] ?? '');
        if (!empty($event->getFkIdPlateforme()) && is_numeric($event->getFkIdPlateforme())) {
            $plateformeRepository = new PlateformeRepository();
            $plateformeId = $plateformeRepository->findPlateforme($event->getFkIdPlateforme());

            if ($plateformeId) {
                $event->setFkIdPlateforme($plateformeId['id_plateforme']);
            } else {
                $error[] = "La plateforme spécifiée n'existe pas.";
            }
        } else {
            $error[] = "Veuillez sélectionner une plateforme.";
        }

        if (empty($event->getDateHourStart())) {
            $error[] = "Veuillez renseigner une date et une heure à laquelle commence l'évènement";
        } else {
            $now = new DateTimeImmutable();
            if ($event->getDateHourStart() < $now) {
                $error[] = "La date et l'heure de début doivent être ultérieures à l'heure actuelle.";
            }
        }

        if (empty($event->getDateHourEnd())) {
            $error[] = "Veuillez renseigner une date et une heure à laquelle ce termine l'évènement";
        } else {
            if ($event->getDateHourEnd() <= $event->getDateHourStart()) {
                $error[] = "La date et l'heure de fin doivent être ultérieures à la date et l'heure de début.";
            } else {
                $interval  = $event->getDateHourStart()->diff($event->getDateHourEnd());
                $totalTime = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                if ($totalTime < 60) {
                    $error[] = "L'évènement doit durer au moins 1 heure.";
                }
            }
        }

        if (empty($event->getNombreDeJoueurs()) || ! is_numeric($event->getNombreDeJoueurs())) {
            $error[] = "Veuillez renseigner un nombre de joueurs";
        } elseif (intval($event->getNombreDeJoueurs()) < 10) {
            $error[] = "Le nombre de participant doit être au moins de 10 joueurs";
        }

        if (empty($event->getDescription())) {
            $error[] = "Veuillez apporter une description à l'évènement";
        } else {
            $description       = EventValidator::secureInput($event->getDescription());
            $descriptionLenght = mb_strlen($description);

            if ($descriptionLenght < 10) {
                $error[] = "La description doit comporter au moins 10 caractères.";
            } elseif ($descriptionLenght > 500) {
                $error[] = "La description ne doit pas dépasser 500 caractères.";
            }

            if (! preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\(\)\[\]:]{3,}$/', $description)) {
                $error[] = "La description contient des caractères non autorisés.";
            }
        }

        $event->setVisibility($_POST['visibility'] ?? '');
        if (empty($event->getVisibility())) {
            $error[] = "Aucune visibilité n'a été choisit pour l'évènement.";
        } elseif (!in_array($event->getVisibility(), ['public', 'privé'])) {
            $error[] = "La visibilité sélectionnée est invalide.";
        }

        // var_dump($event->getFkIdPlateforme(), $event->getVisibility());

        return $error;
    }

    //Vérifie si les fichiers envoyés son conforme à ce qui est attendu.
    public static function isFileUploaded($eventImage)
    {
        $error = [];

        if (isset($eventImage->getImagePath) && !empty($eventImage->getImagePath)){
           $error[] = "Aucune image n'a été sélectionné pour le diaporama.";
        };
    }

    //Sécurisation des champs pouvant être envoyé par un utilisateur.
    public static function secureInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    //Effectue tout les contrôle sur l'image (type, taille, dimensions)
    public static function secureImage(string $input)
    {
        return filter_var(trim($input), FILTER_SANITIZE_URL);
    }

    /* vérification des caractères dans le nom du fichier, utili aussi pour les input titre, nom de jeu
    if (!preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\(\)\[\]:]{3,}$/', $gameName)) {
        $errors['game_name'] = 'Le nom n\'est pas valide.';
      }

      !preg_match() signifie que seuls les caractères indiqués sont autorisés, tandis que preg_match() sans ! détecte les caractères interdits


      public static function validateImage(array $file, string $fieldName): array {
    $errors = [];

    // Vérifier s'il y a une erreur d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Une erreur est survenue lors du téléchargement de l'image ($fieldName).";
    }

    // Vérifier le type de fichier
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        $errors[] = "Le fichier $fieldName doit être au format JPEG, PNG, GIF ou WEBP.";
    }

    // Vérifier la taille (max 5 Mo)
    $maxSize = 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $errors[] = "L'image $fieldName ne doit pas dépasser 5 Mo.";
    }

    // Vérifier les dimensions de l'image
    list($width, $height) = getimagesize($file['tmp_name']);
    
    if ($fieldName === 'cover_image') {
        $minWidth = 500;  // Spécifique pour la cover
        $minHeight = 500;
    } else {
        $minWidth = 300;  // Spécifique pour les autres images
        $minHeight = 300;
    }

    if ($width < $minWidth || $height < $minHeight) {
        $errors[] = "L'image $fieldName est trop petite (min {$minWidth}x{$minHeight} px).";
    }
    if ($width > 5000 || $height > 5000) {
        $errors[] = "L'image $fieldName est trop grande (max 5000x5000 px).";
    }

    return $errors;
}

      */
}
