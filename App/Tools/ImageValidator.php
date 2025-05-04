<?php
namespace App\Tools;

class ImageValidator {

    private const MAX_IMAGE_SIZE = 2000000;
    private const ALLOWED_IMAGE_TYPES = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

    private static function uploadedFile(array $fileData, string $destinationDir, string $baseName, string $suffix): array
    {
        $error = null;
        $path = null;

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
                $path = $targetPath;
            }
        } elseif ($fileData['error'] !== UPLOAD_ERR_NO_FILE) {
            $error = self::errorMessage($fileData['error'], htmlspecialchars(basename($fileData['name'])));
        }
        return ['error' => $error, 'path' => $path];
    }

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
                break;
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
                imagejpeg($newImage, $targetPath, 80);
                break;
            case 'image/png':
                imagepng($newImage, $targetPath, 9);
                break;
            case 'image/webp':
                imagewebp($newImage, $targetPath, 80);
                break;
        }

        imagedestroy($image);
        imagedestroy($newImage);
        return true;
    }

    public static function processImage(array $fileData, string $destinationDir, string $baseName, string $suffix, ?int $resizeWidth = null):array 
    {
        $uploadedResult = self::uploadedFile($fileData, $destinationDir, $baseName, $suffix);

        if($uploadedResult['error']) {
            return $uploadedResult;
        }

        $originalPath = $uploadedResult['path'];
        $resizedPath = null;

        if ($resizeWidth !== null && $originalPath) {
            $resizedName = pathinfo($originalPath, PATHINFO_FILENAME). '_resized.' . pathinfo($originalPath, PATHINFO_EXTENSION);
            $resizedPath = $destinationDir . $resizedName;
            if (!self::resizeImage($originalPath, $resizedPath, $resizeWidth)) {
                if (file_exists($originalPath)) {
                    unlink($originalPath);
                }
                return ['error' => "Erreur lord du redimensionnement de l'image.", 'path'=>null];
            }
            if (file_exists($originalPath)) {
                unlink($originalPath);
            }
            return ['error' => null, 'path' => $resizedPath];
        }
        return $uploadedResult;
    }

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