<?php
namespace App\Tools;

use DateTimeImmutable;
use App\Tools\ImageValidator;

class EventValidator
{
    //Constantes pour les limites de validation
    private const MAX_TITLE_LENGHT = 100;
    private const MAX_GAME_NAME_LENGHT = 100;
    private const MIN_DESCRIPTION_LENGHT = 10;
    private const MAX_DESCRIPTION_LENGHT = 500;
    private const MIN_PLAYERS = 10;
    private const MAX_DIAPO_IMAGES =5;


    //Vérifie si les champs du formulaire de création d'évènements son correctement renseignés.
    public static function validateEvent($event): array
    {
        $error = [];

        if (empty($event->getCoverImagePath())) {
            $error['cover_image_path'] = "Veuillez ajouter au moins une image de couverture pour l'évènement";
        }

        if (empty($event->getNameEvent())) {
            $error['name_event'] = "Veuillez renseigner un titre pour l'évènement !";
        } elseif (strlen($event->getNameEvent()) > self::MAX_TITLE_LENGHT) {
            $error['name_event'] = "Le titre est trop long, taille maximum autorisé 100 caractères.";
        }

        if (empty($event->getNameGame())) {
            $error['name_game'] = "Veuillez renseigner titre de jeux";
        } elseif (strlen($event->getNameGame()) > self::MAX_GAME_NAME_LENGHT) {
            $error['name_game'] = "Le nom du jeu est trop long, taille maximum autorisé 100 caractères.";
        }

        $fkIdPlateforme = $event->getFkIdPlateforme();

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
        } elseif (intval($event->getNombreDeJoueurs()) < self::MIN_PLAYERS) {
            $error['nombre_de_joueurs'] = "Le nombre de participant doit être au moins de 10 joueurs";
        }

        if (empty($event->getDescription())) {
            $error['description'] = "Veuillez apporter une description à l'évènement";
        } else {
            $description       = EventValidator::secureInput($event->getDescription());
            $descriptionLenght = mb_strlen($description);

            if ($descriptionLenght < self::MIN_DESCRIPTION_LENGHT) {
                $error['description'] = "La description doit comporter au moins" . self::MIN_DESCRIPTION_LENGHT . "caractères.";
            } elseif ($descriptionLenght > self::MAX_DESCRIPTION_LENGHT) {
                $error['description'] = "La description ne doit pas dépasser" . self::MAX_DESCRIPTION_LENGHT . "caractères.";
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
            $names     = is_array($eventImage['name']) ? $eventImage['name'] : [$eventImage['name']];
            $types     = is_array($eventImage['type']) ? $eventImage['type'] : [$eventImage['type']];
            $tmp_names = is_array($eventImage['tmp_name']) ? $eventImage['tmp_name'] : [$eventImage['tmp_name']];
            $errors    = is_array($eventImage['error']) ? $eventImage['error'] : [$eventImage['error']];
            $sizes     = is_array($eventImage['size']) ? $eventImage['size'] : [$eventImage['size']];

            $allowedTypes = ['image/jpeg', 'image/png'];
            $maxSize      = 2 * 1024 * 1024;

            foreach ($names as $key => $name) {
                if ($errors[$key] === UPLOAD_ERR_OK) {
                    if (! in_array($types[$key], $allowedTypes)) {
                        $error['image_path'][] = "Le type du fichier " . htmlspecialchars($name) . " n'est pas autorisé.";
                    } elseif ($sizes[$key] > $maxSize) {
                        $error['image_path'][] = "Le fichier " . htmlspecialchars($name) . " est trop volumineux.";
                    }
                } else {
                    // Gestion des erreurs de téléchargement
                    switch ($errors[$key]) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            $error['image_path'][] = "Le fichier " . htmlspecialchars($name) . " est trop volumineux.";
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $error['image_path'][] = "Le fichier " . htmlspecialchars($name) . " n'a été que partiellement téléchargé.";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $error['image_path'][] = "Aucun fichier n'a été téléchargé.";
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $error['image_path'][] = "Le dossier temporaire est manquant.";
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $error['image_path'][] = "Échec de l'écriture du fichier sur le disque.";
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $error['image_path'][] = "Une extension PHP a arrêté le téléchargement du fichier.";
                            break;
                        default:
                            $error['image_path'][] = "Erreur inconnue lors du téléchargement du fichier.";
                            break;
                    }
                }
            }
        }
        return $error;
    }

    //Effectue tout les contrôle sur l'image (type, taille, dimensions)
    public static function secureImage(array $filesData): array
    {
        $error            = [];
        $uploadedFiles    = [];
        $projectRoot      = dirname(__DIR__, 2);
        $destinationCover = $projectRoot . "/Assets/Documentation/Images/Couverture/";
        $destinationDiapo = $projectRoot . "/Assets/Documentation/Images/Diapo/";

        if (! is_dir($destinationCover)) {
            if (! mkdir($destinationCover)) {
                error_log("Erreur lors de la création du répertoire" . $destinationCover);
                $error['cover_image_path'] = "Erreur serveur lors du traitement de l'image de couverture.";
            }
        }
        if (! is_dir($destinationDiapo)) {
            if (! mkdir($destinationDiapo)) {
                error_log("Erreur lors de la création du répertoire" . $destinationDiapo);
                $error['image_path'] = "Erreur serveur lors du traitement des images du diaporama.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $gameName = $_POST['name_game'];
            $baseName = preg_replace('/[^a-zA-Z0-9]/', ' ', $gameName);
            $baseName = strtolower($baseName);

            //Traitement de l'image de couverture
            if (isset($_FILES['cover_image_path']) && $filesData['cover_image_path']['error'] === UPLOAD_ERR_OK) {

                $uploadResult = ImageValidator::processImage($_FILES['cover_image_path'], $destinationCover, $baseName, 'Couverture_original', 1200);
                if ($uploadResult ['error']) {
                    $error['cover_image_path'] = $uploadResult['error'];
                } elseif ($uploadResult['path']) {
                    $uploadedFiles['cover_image_path'] = htmlspecialchars($uploadResult['path']);
                }
            } elseif (isset($_FILES['cover_image_path']) && $_FILES['cover_image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
                $error['cover_image_path'] = ImageValidator::errorMessage($_FILES['cover_image_path']['error'], htmlspecialchars(basename($_FILES['cover_image_path']['name'])));
            }

        /*
                $file        = $filesData['cover_image_path']['name'];
                $extension   = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $fileTmpPath = $filesData['cover_image_path']['tmp_name'];
                $fileType    = mime_content_type($fileTmpPath);

                if (in_array($fileType, $allowedTypes) && $filesData['cover_image_path']['size'] <= $maxSize) {
                    $newFileName    = $baseName . '_' . uniqid() . '_' . 'Couverture.' . $extension;
                    $targetDirCover = $destinationCover . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $targetDirCover)) {
                        $uploadedFiles['cover_image_path'] = htmlspecialchars($targetDirCover);
                    } else {
                        $error['cover_image_path'] = "Erreur lors du téléchargement de l'image de couverture.";
                    }

                } else {
                    $error['cover_image_path'] = "Type ou taille de fichier non valide pour l'image de couverture.";
                }
            } elseif (isset($filesData['cover_images_path']) && $filesData['cover_image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
                $error['cover_image_path'] = "Erreur lors du téléchargement de l'image de couverture(code: " . $filesData['cover_image_path']['error'] . ")";
            }
        */

            //Traitement des images de diaporama
            if (isset($filesData['image_path']) && is_array($filesData['image_path']['error'])) {

                $files = $_FILES['image_path'];
                $uploadedFiles['image_path'] = [];
                $diapoErrors = [];
                $uploadedDiapoCount = 0;

                if (is_array($files['name'])) {
                    if (count($files['name']) > self::MAX_DIAPO_IMAGES) {
                        $error['image_path'] = "Maximum d'image pour le diaporama dépassé, le nombre d'images autorisées est de " . self::MAX_DIAPO_IMAGES . ".";
                    } else {
                        foreach ($files['name'] as $key => $name) {
                            if (!empty($name) && $uploadedDiapoCount < self::MAX_DIAPO_IMAGES) {
                                $fileData = [
                                    'name' => $name,
                                    'type' => $files['type'][$key],
                                    'tmp_name' => $files['tmp_name'][$key],
                                    'error' => $files['error'][$key],
                                    'size' => $files['size'][$key],
                                ];

                                $uploadResult = ImageValidator::processImage($fileData, $destinationDiapo, $baseName, 'Diapo_' . ($uploadedDiapoCount + 1), 800);
                                if ($uploadResult['error']) {
                                    $diapoErrors[] = "Erreur lors du traitement de " . htmlspecialchars(basename($name)). ": " . $uploadResult['error'];
                                } elseif ($uploadResult['path']) {
                                    $uploadedFiles['image_path'][] = htmlspecialchars($uploadResult['path']);
                                    $uploadedDiapoCount++;
                                } elseif ($fileData['error'] !== UPLOAD_ERR_NO_FILE) {
                                    $diapoErrors[] = ImageValidator::errorMessage($fileData['error'], htmlspecialchars(basename($name)));
                                }
                            }
                        }

                        if (!empty($diapoErrors)) {
                            $error['image_path'] = $diapoErrors;
                        } elseif (empty(array_filter($files['name']))) {
                            $error['image_path'] = "Veuillez sélectionner au moins une image pour le diaporama.";
                        }
                    }
                } elseif (!empty($files['name'])) {
                    $uploadResult = ImageValidator::processImage($_FILES['image_path'], $destinationDiapo, $baseName, 'Diapo_1', 800);
                    if ($uploadResult['error']) {
                        $error['image_path'] = [$uploadResult['error']];
                    } elseif ($uploadResult['path']) {
                        $uploadedFiles['image_path'][] = htmlspecialchars($uploadResult['path']);
                    } elseif($_FILES['image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
                        $error['image_path'] = [ImageValidator::errorMessage($_FILES['image_path']['error'], htmlspecialchars(basename($_FILES['image_path']['name'])))];
                    }
                }
                
                /*if (is_array($filesData['image_path']['name']) && count($filesData['image_path']['name']) > $maxDiapoImages) {
                    $diapoErrors[] = "Maximum d'image pour le diaporama dépassé, le nombre d'images autorisées est de " . $maxDiapoImages . ".";
                }

                foreach ($files['name'] as $key => $name) {
                    $fileTmpPath = $files['tmp_name'][$key];
                    $fileType    = mime_content_type($fileTmpPath);
                    $fileSize    = $files['size'][$key];
                    $fileError   = $files['error'][$key];

                    if ($fileError === UPLOAD_ERR_OK) {

                        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
                            $newFileName    = $baseName . '_' . uniqid() . 'Diapo' . ($key + 1) . '.' . $extension;
                            $targetDirDiapo = $destinationDiapo . $newFileName;

                            if (move_uploaded_file($fileTmpPath, $targetDirDiapo)) {
                                $uploadedFiles['image_path'][] = htmlspecialchars($targetDirDiapo);
                            } else {
                                $diapoErrors[$key] = "Erreur lors du téléchargement de " . htmlspecialchars(basename($name)) . ".";
                            }
                        } else {
                            $diapoErrors[$key] = "Type ou taille de fichier non valide pour " . htmlspecialchars(basename($name)) . ".";
                        }
                    } elseif ($fileError !== UPLOAD_ERR_NO_FILE) {
                        $diapoErrors[$key] = "Erreur lors du téléchargement de ". htmlspecialchars(basename($name)) . "(code : ".$fileError.").";
                    }
                }

                if (!empty($diapoErrors)) {
                    $error['image_path'] = $diapoErrors;
                }

                //Traitement pour le cas où une seule image est sélectionnée pour le diaporama
            } elseif (isset($filesData['image_path']) && $filesData['image_path']['error'] === UPLOAD_ERR_OK) {
                $name       = $filesData['image_path']['name'];
                $tmp_name   = $filesData['image_path']['tmp_name'];
                $type       = $filesData['image_path']['type'];
                $size       = $filesData['image_path']['size'];
                $error_code = $filesData['image_path']['error'];
                $diapoErrors = [];

                if ($error_code === UPLOAD_ERR_OK) {
                    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($type, $allowedTypes) && $size <= $maxSize) {

                        $newFileName    = $baseName . '_' . uniqid() . 'Diapo1.' . $extension;
                        $targetDirDiapo = $destinationDiapo . $newFileName;
                        if (move_uploaded_file($tmp_name, $targetDirDiapo)) {
                            $uploadedFiles['image_path'][] = htmlspecialchars($targetDirDiapo);
                        } else {
                            $diapoErrors[] = "Erreur lors du téléchargement de " . htmlspecialchars(basename($name)) . ".";
                        }
                    } else {
                        $diapoErrors[] = "Type ou taille de fichier non valide pour " . htmlspecialchars(basename($name)) . ".";
                    }
                }  else if ($error_code !== UPLOAD_ERR_NO_FILE) {
                    $diapoErrors[] = "Erreur lors du téléchargement de l'image (code: " . $error_code . ").";
                }
                if (!empty($diapoErrors)) {
                    $error['image_path'] = $diapoErrors;
                }
            */
            }
        }
        return ['uploaded' => $uploadedFiles, 'errors' => $error];
    }

    //Sécurisation des champs pouvant être envoyé par un utilisateur.
    public static function secureInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /*
      public static function validateImage(array $file, string $fieldName): array {
    $errors = [];

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
