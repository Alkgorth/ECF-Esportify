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

        $fkIdPlateforme = $event->getFkIdPlateforme();

        if (empty($fkIdPlateforme) && $fkIdPlateforme !== null) {
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

    //Effectue tout les contrôle sur l'image (type, taille, dimensions)
    public static function secureImage()
    {
        $destinationCover = "../../Assets/Documentation/Images/Couverture/";
        $destinationDiapo = "../../Assets/Documentation/Images/Diapo/";
        $uploadedFiles    = [];
        $error            = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Traitement de l'image de couverture
            if (isset($_FILES['cover_image_path']) && $_FILES['cover_image_path']['error'] === UPLOAD_ERR_OK) {

                $file = $_FILES['cover_image_path']['name'];

                $clearFileName = filter_var(trim($file), FILTER_SANITIZE_URL);
                if ($clearFileName !== $file) {
                    $error['cover_image_path'] = "Le nom du fichier contient des caratères non autorisés.";

                }

                $targetDir = $destinationCover . uniqid() . '_' . basename($file);

                $allowedTypes = ['image/png', 'image/jpeg'];

                $maxSize = 2 * 1024 * 1024;

                if (in_array($_FILES['cover_image_path']['type'], $allowedTypes) && $_FILES['cover_image_path']['size'] <= $maxSize) {

                    if (move_uploaded_file($_FILES['cover_image_path']['tmp_name'], $targetDir)) {
                        $uploadedFiles['cover_image_path'] = htmlspecialchars(basename($file));
                    } else {
                        $error['cover_image_path'] = "Erreur lors du téléchargement de l'image de couverture.";
                    }
                } else {
                    $error['cover_image_path'] = "Type ou taille de fichier non valide pour l'image de couverture.";
                }
            } elseif (isset($_FILES['cover_images_path']) && $_FILES['cover_image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
                $error['cover_image_path'] = "Erreur lors du téléchargement de l'image de couverture(code: " . $_FILES['cover_image_path']['error'] . ")";
            }

            //Traitement des images de diaporama
            if (isset($_FILES['image_path']) && is_array($_FILES['image_path']['error'])) {

                $files                       = $_FILES['image_path'];
                $uploadedFiles['image_path'] = [];
                $error['image_path']         = [];

                foreach ($files['name'] as $key => $name) {
                    if ($files['error'][$key] === UPLOAD_ERR_OK) {

                        $file = $name;

                        $clearFileName = filter_var(trim($file), FILTER_SANITIZE_URL);
                        if ($clearFileName !== $file) {
                            $error['image_path'][$key] = "Le nom de fichier contient des caratères non autorisés.";
                            continue;
                        }

                        $targetDir = $destinationDiapo . uniqid() . '_' . basename($file);

                        $allowedTypes = ['image/png', 'image/jpeg'];

                        $maxSize = 2 * 1024 * 1024;

                        if (in_array($files['type'][$key], $allowedTypes) && $files['size'][$key] <= $maxSize) {

                            if (move_uploaded_file($files['tmp_name'][$key], $targetDir)) {

                                $uploadedFiles['image_path'][] = htmlspecialchars(basename($file));
                            } else {
                                $error['image_path'][$key] = "Erreur lors du téléchargement de " . htmlspecialchars(basename($file)) . ".";
                            }
                        } else {
                            $error['image_path'][$key] = "Type ou taille de fichier non valide pour " . htmlspecialchars(basename($file)) . ".";
                        }
                    } elseif ($files['error'][$key] !== UPLOAD_ERR_NO_FILE) {
                        $error['image_path'][$key] = "Erreur lors du téléchargement de " . htmlspecialchars(basename($name)) . "(code : " . $files['error'][$key] . ".";
                    }
                }
            }
        }
        return ['uploaded' => $uploadedFiles, 'errors' => $error];
    }

    //Sécurisation des champs pouvant être envoyé par un utilisateur.
    public static function secureInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
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
