<?php
namespace App\Tools;

class ImageValidator {

    private const MAX_IMAGE_SIZE = 2000000;
    private const ALLOWED_IMAGE_TYPES = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

    //Upload et valide un fichier image
    private static function uploadedFile(array $fileData, string $destinationDir, string $baseName, string $suffix): array
    {
        $error = null;
        $path = null;
        $nameOfFile = '';

        if ($fileData['error'] === UPLOAD_ERR_OK) {
            $fileType = mime_content_type($fileData['tmp_name']);
            $fileExtension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
            $newFileName = $baseName . uniqid() . $suffix . '.' . $fileExtension;
            $targetPath = $destinationDir . $newFileName;

            if (!in_array($fileType, self::ALLOWED_IMAGE_TYPES) || $fileData['size'] > self::MAX_IMAGE_SIZE) {
                $error = "Type ou taille de fichier non valide pour " . htmlspecialchars(basename($fileData['name'])). ".";
            } elseif (!move_uploaded_file($fileData['tmp_name'], $targetPath)) {
                $error = "Erreur lors du téléchargement de " . htmlspecialchars(basename($fileData['name'])). ".";
            } else {
                $nameOfFile = $newFileName;
            }
        } elseif ($fileData['error'] !== UPLOAD_ERR_NO_FILE) {
            $error = self::errorMessage($fileData['error'], htmlspecialchars(basename($fileData['name'])));
        }
        return ['error' => $error, 'nameOfFile' => $nameOfFile];
    }

    //Redimenssionne une image
    private static function resizeImage(string $sourcePath, string $targetPath, int $newWidth): bool
    {
        $mime = mime_content_type($sourcePath);
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        $sourceWidth = imagesx($image);
        $sourceHeight = imagesy($image);
        $newHeight = (int)($newWidth * $sourceHeight / $sourceHeight);
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/webp':
                imagejpeg($newImage, $targetPath, 80);
                break;
            case 'image/png':
                imagepng($newImage, $targetPath, 9);
                break;
            default:
                return false;
        }

        imagedestroy($image);
        imagedestroy($newImage);
        return true;
    }

    //Upload, valide et redimenssionne une image.
    public static function processImage(array $fileData, string $destinationDir, string $baseName, string $suffix, ?int $resizeWidth = null):array 
    {
        $uploadResult = self::uploadedFile($fileData, $destinationDir, $baseName, $suffix);

        if($uploadResult['error']) {
            return $uploadResult;
        }

        $originalPath = $destinationDir . $uploadResult['nameOfFile'];
        $errorMsg = null;
        $nameOfFile = $uploadResult['nameOfFile'];

        if ($resizeWidth !== null && !empty($originalPath)) {
            $resizedName = pathinfo($originalPath, PATHINFO_FILENAME). '_resized.' . pathinfo($originalPath, PATHINFO_EXTENSION);
            $resizedPath = $destinationDir . $resizedName;

            if (!self::resizeImage($originalPath, $resizedPath, $resizeWidth)) {
                $errorMsg = "Erreur lors du redimensionnement de l'image.";
            }

            if (file_exists($originalPath)) {
                unlink($originalPath);
            }

            $nameOfFile = $resizedName;
        }

        return ['error' => $errorMsg, 'nameOfFile' => $nameOfFile ];
    }

    //Retourne un message d'erreur clair en fonction de la situation.
    public static function errorMessage(int $errorCode, string $fileName): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "Le fichier {$fileName} est trop volumineux.";
                break;
            case UPLOAD_ERR_PARTIAL:
                return "Le fichier {$fileName} n'a pas été correctement téléchargé.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Le dossier temporaire est manquant.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                return "Échec de l'écriture du fichier {$fileName}.";
                break;
            case UPLOAD_ERR_EXTENSION:
                return "Une extension PHP a arrêté le téléchargement du fichier {$fileName}.";
                break;
            default:
                return "Erreur inconnue lors du téléchargement du fichier {$fileName}.";
                break;
        }
    }
}