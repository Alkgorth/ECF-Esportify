<?php
namespace App\Tools;

use DateTimeImmutable;

class EventValidator
{
    //Vérifie si les champs du formaulaire de création d'évènements son correctement renseignés.
    public static function validateEvent($event): array
    {
        $error = [];

        if (empty($event->getCoverImagePath())) {
            $error['cover_image_path'] = "Veuillez ajouter au moins une image de couverture pour l'évènement";
        }

        if (empty($event->getNameEvent())) {
            $error['name_event'] = "Veuillez renseigner un titre pour l'évènement !";
        } elseif (strlen($event->getNameEvent()) > 100) {
            $error['name_event'] = "Le titre est trop long, taille maximum autorisé 100 caractères.";
        }

        if (empty($event->getNameGame())) {
            $error['name_game'] = "Veuillez renseigner titre de jeux";
        } elseif (strlen($event->getNameGame()) > 100) {
            $error['name_game'] = "Le nom du jeu est trop long, taille maximum autorisé 100 caractères.";
        }

        $fkIdPlateforme = $_POST['name_plateforme'];

        if (empty($fkIdPlateforme)) {
            $error['name_plateforme'] = "Veuillez sélectionner une plateforme.";
        }

        if (empty($event->getDateHourStart())) {
            $error['date_hour_start'] = "Veuillez renseigner une date et une heure à laquelle commence l'évènement";
        } else {
            $now = new DateTimeImmutable();
            if ($event->getDateHourStart() < $now) {
                $error['date_hour_start'] = "La date et l'heure de début doivent être ultérieures à l'heure actuelle.";
            }
        }

        if (empty($event->getDateHourEnd())) {
            $error['date_hour_end'] = "Veuillez renseigner une date et une heure à laquelle ce termine l'évènement";
        } else {
            if ($event->getDateHourEnd() <= $event->getDateHourStart()) {
                $error['date_hour_end'] = "La date et l'heure de fin doivent être ultérieures à la date et l'heure de début.";
            } else {
                $interval  = $event->getDateHourStart()->diff($event->getDateHourEnd());
                $totalTime = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                if ($totalTime < 60) {
                    $error['date_hour_end'] = "L'évènement doit durer au moins 1 heure.";
                }
            }
        }

        if (empty($event->getNombreDeJoueurs()) || ! is_numeric($event->getNombreDeJoueurs())) {
            $error['nombre_de_joueurs'] = "Veuillez renseigner un nombre de joueurs";
        } elseif (intval($event->getNombreDeJoueurs()) < 10) {
            $error['nombre_de_joueurs'] = "Le nombre de participant doit être au moins de 10 joueurs";
        }

        if (empty($event->getDescription())) {
            $error['description'] = "Veuillez apporter une description à l'évènement";
        } else {
            $description       = EventValidator::secureInput($event->getDescription());
            $descriptionLenght = mb_strlen($description);

            if ($descriptionLenght < 10) {
                $error['description'] = "La description doit comporter au moins 10 caractères.";
            } elseif ($descriptionLenght > 500) {
                $error['description'] = "La description ne doit pas dépasser 500 caractères.";
            }

            if (! preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\(\)\[\]:]{3,}$/', $description)) {
                $error['description'] = "La description contient des caractères non autorisés.";
            }
        }

        if (! isset($_POST['visibility'])) {
            $error['visibility'] = "Veuillez sélectionner une visibilité pour l'évènement.";
        }

        return $error;
    }

    //Vérifie si les fichiers envoyés son conforme à ce qui est attendu.
    public static function isFileUploaded($eventImage)
    {
        $error = [];

        if (! isset($eventImage['name']) || empty($eventImage['name'][0])) {
            $error['image_path'] = "Aucune image n'a été sélectionné pour le diaporama.";
        } else {
            foreach ($eventImage['error'] as $key => $error) {
                if ($error === UPLOAD_ERR_OK) {
                    $maxSize      = 2 * 1024 * 1024;
                    $allowedTypes = ['image/jpeg', 'image/png'];
                }
                if (! in_array($eventImage['type'][$key], $allowedTypes)) {
                    $error['image_path'][] = "Le type du fichier " . $eventImage['name'][$key] . "n'est pas autorisé";
                } else {
                    // Gestion des erreurs de téléchargement
                    switch ($error) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            $errors['image_path'][] = "Le fichier " . $eventImage['name'][$key] . " est trop volumineux.";
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $errors['image_path'][] = "Le fichier " . $eventImage['name'][$key] . " n'a été que partiellement téléchargé.";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $errors['image_path'][] = "Aucun fichier n'a été téléchargé.";
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $errors['image_path'][] = "Le dossier temporaire est manquant.";
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $errors['image_path'][] = "Échec de l'écriture du fichier sur le disque.";
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $errors['image_path'][] = "Une extension PHP a arrêté le téléchargement du fichier.";
                            break;
                        default:
                            $errors['image_path'][] = "Erreur inconnue lors du téléchargement du fichier.";
                            break;
                    }
                }
            }
        }
        return $error;
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
