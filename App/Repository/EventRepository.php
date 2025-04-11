<?php

// indique où ce situe le fichier
namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventImage;

class EventRepository extends MainRepository
{
    //Affichage des derniers évènements ajouté en base de données
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

    //Affichage des informations global pour un évènement
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

    //Récupération de toutes les plateformes en base de données
    public function getAllPlateformes()
    {
        $query = $this->pdo->prepare('SELECT id_plateforme, name FROM plateforme');
        $query->execute();
        $plateformes = $query->fetchAll($this->pdo::FETCH_ASSOC);

        return $plateformes;

    }


    //Création d'un évènement en base de données
    public function creationEvent(Event $event, int $fk_id_user)
    {
        try {
            $this->pdo->beginTransaction();

            $userCheck = $this->pdo->prepare("SELECT COUNT (*) FROM user WHERE id_user = :id");
            $userCheck->bindValue(':id', $fk_id_user, $this->pdo::PARAM_INT);
            $userCheck->execute();
            if($userCheck->fetchColumn() === 0) {
                throw new \Exception("L'utilisateur n'existe pas !");
            }

            $this->insertEvent($event, $fk_id_user);


        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (! isset($data['cover_image_path'], $data['image_path'], $data['name_event'],
                $data['name_game'], $data['fk_id_plateforme'], $data['date_hour_start'],
                $data['date_hour_end'], $data['nombre_de_joueurs'], $data['description'],
                $data['visibility'])) {
                throw new \Exception("Vous devez remplir tout les champs.");
            }
            $cover_image_path  = filter_var(trim($data['cover_image_path']), FILTER_SANITIZE_URL);
            $image_path        = filter_var(trim($data['image_path']), FILTER_SANITIZE_URL);
            $name_event        = htmlspecialchars(trim($data['name_event']), ENT_QUOTES, 'UTF-8');
            $name_game         = htmlspecialchars(trim($data['name_game']), ENT_QUOTES, 'UTF-8');
            $fk_id_plateforme  = filter_var($data['fk_id_plateforme'], FILTER_VALIDATE_INT);
            $date_hour_start   = trim($data['date_hour_start']);
            $date_hour_end     = trim($data['date_hour_end']);
            $nombre_de_joueurs = filter_var($data['nombre_de_joueurs'], FILTER_VALIDATE_INT);
            $description       = htmlspecialchars(trim($data['description']), ENT_QUOTES, 'UTF-8');
            $visibility        = htmlspecialchars(trim($data['visibility']), ENT_QUOTES, 'UTF-8');


            if ($nombre_de_joueurs === false || $nombre_de_joueurs < 10) {
                throw new \Exception("Le nombre de joueurs doit être au minimum de 10.");
            }

            if ($fk_id_plateforme === false) {
                throw new \Exception("La plateforme choisit ne fait pas partie de la liste proposée.");
            }

            $query = $this->pdo->prepare("SELECT COUNT(*) FROM plateforme WHERE id_plateforme = :id");
            $query->bindParam(':id', $fk_id_plateforme, $this->pdo::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() == 0) {
                throw new \Exception("La plateforme choisit n'est pas reconnue.");
            }

            $this->pdo->beginTransaction();
            $query = $this->pdo->prepare("
                    INSERT INTO event (name_event, name_game, date_hour_start, date_hour_end, nombre_de_joueurs, description, visibility, fk_id_plateforme, cover_image_path)
                    VALUES (:name_event, :name_game, :date_hour_start, :date_hour_end, :nombre_de_joueurs, :description, :visibility, :fk_id_plateforme, :cover_image_path)");

            $query->bindParam(':name_event', $name_event, $this->pdo::PARAM_STR);
            $query->bindValue(':name_game', $name_game, $this->pdo::PARAM_STR);
            $query->bindValue(':date_hour_start', $date_hour_start, $this->pdo::PARAM_STR);
            $query->bindValue(':date_hour_end', $date_hour_end, $this->pdo::PARAM_STR);
            $query->bindValue(':nombre_de_joueurs', $nombre_de_joueurs, $this->pdo::PARAM_INT);
            $query->bindValue(':description', $description, $this->pdo::PARAM_STR);
            $query->bindValue(':visibility', $visibility, $this->pdo::PARAM_STR);
            $query->bindValue(':fk_id_plateforme', $fk_id_plateforme, $this->pdo::PARAM_INT);
            $query->bindValue(':cover_image_path', $cover_image_path, $this->pdo::PARAM_STR);
            $query->execute();
            $id_event = $this->pdo->lastInsertId();

            $query = $this->pdo->prepare("
            INSERT INTO event_image (image_path, image_order, fk_id_event)
            VALUES (:image_path, :image_order, :fk_id_event)");

            $image_order = 1;
            $query->bindValue(':image_path', $image_path, $this->pdo::PARAM_STR);
            $query->bindParam(':image_order', $image_order, $this->pdo::PARAM_INT);
            $query->bindParam(':fk_id_event', $id_event, $this->pdo::PARAM_INT);
            $query->execute();

            $this->pdo->commit();

        }
    }

    //Récupération des inforamations dans le formaulaire de création d'évènement à ajouter dans la table Event
   /* public function insertEvent(Event $data, int $fk_id_user, ?string $cover_image_path)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($data->getNameEvent(), $data->getNameGame(), $data->getFkIdPlateforme(),
            $data->getDateHourStart(), $data->getDateHourEnd(), $data->getNombreDeJoueurs(),
            $data->getDescription(), $data->getVisibility())){
                throw new \Exception("Vous devez remplir tout les champs");
            }

            $query = $this->pdo->prepare("
            INSERT INTO event (name_event, name_game, date_hour_start, date_hour_end, nombre_de_joueurs, description, visibility, fk_id_plateforme, fk_id_user, cover_image_path)
            VALUES (:name_event, :name_game, :date_hour_start, :date_hour_end, :nombre_de_joueurs,:description, :visibility, :fk_id_plateforme, :fk_id_user, :cover_image_path)");

            $name_event = htmlspecialchars(trim($data['name_event']), ENT_QUOTES, 'UTF-8');
            $name_game = htmlspecialchars(trim($data['name_game']), ENT_QUOTES, 'UTF-8');
            $fk_id_plateforme = filter_var($data['fk_id_plateforme'], FILTER_VALIDATE_INT);
            $date_hour_start = trim($data['date_hour_start']);
            $date_hour_end = trim($data['date_hour_end']);
            $nombre_de_joueurs = filter_var($data['nombre_de_joueurs'], FILTER_VALIDATE_INT);
            $description = htmlspecialchars(trim($data['description']), ENT_QUOTES, 'UTF-8');
            $visibility = htmlspecialchars(trim($data['visibility']), ENT_QUOTES, 'UTF-8');
        }
    }

    //Récupération des inforamations dans le formaulaire de création d'évènement à ajouter dans la table EventImage
    public function insertEventImage(EventImage $data)
    {

    }

    

    public function creationEvent(Event $event, string $cover_image_path, string $image_path)
{
    try {
        // Démarrer la transaction
        $this->pdo->beginTransaction();

        // Insérer l'événement
        $this->insertEvent($event);

        // Insérer l'image de couverture
        $this->insertEventImage($event->getId(), $cover_image_path, $image_path);

        // Si tout est ok, valider la transaction
        $this->pdo->commit();
    } catch (\Exception $e) {
        // En cas d'erreur, annuler la transaction
        $this->pdo->rollBack();
        throw new \Exception("Erreur lors de la création de l'événement : " . $e->getMessage());
    }
}

private function insertEvent(Event $event)
{
    // Validation des données pour l'événement
    if (!isset($event->getNameEvent(), $event->getNameGame(), $event->getFkIdPlateforme(),
        $event->getDateHourStart(), $event->getDateHourEnd(), $event->getNombreDeJoueurs(),
        $event->getDescription(), $event->getVisibility())) {
        throw new \Exception("Vous devez remplir tous les champs.");
    }

    // Préparer et exécuter l'insertion dans la table event
    $query = $this->pdo->prepare("
        INSERT INTO event (name_event, name_game, date_hour_start, date_hour_end, nombre_de_joueurs, description, visibility, fk_id_plateforme)
        VALUES (:name_event, :name_game, :date_hour_start, :date_hour_end, :nombre_de_joueurs, :description, :visibility, :fk_id_plateforme)
    ");

    $query->bindParam(':name_event', $event->getNameEvent(), PDO::PARAM_STR);
    $query->bindValue(':name_game', $event->getNameGame(), PDO::PARAM_STR);
    $query->bindValue(':date_hour_start', $event->getDateHourStart()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $query->bindValue(':date_hour_end', $event->getDateHourEnd()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $query->bindValue(':nombre_de_joueurs', $event->getNombreDeJoueurs(), PDO::PARAM_INT);
    $query->bindValue(':description', $event->getDescription(), PDO::PARAM_STR);
    $query->bindValue(':visibility', $event->getVisibility()->value, PDO::PARAM_STR);
    $query->bindValue(':fk_id_plateforme', $event->getFkIdPlateforme(), PDO::PARAM_INT);
    $query->execute();

    // Récupérer l'ID de l'événement nouvellement inséré
    $event->setId($this->pdo->lastInsertId());
}

private function insertEventImage(int $eventId, string $cover_image_path, string $image_path)
{
    // Validation des données pour l'image
    if (empty($cover_image_path) || empty($image_path)) {
        throw new \Exception("Les chemins d'images ne peuvent pas être vides.");
    }

    // Insertion dans la table event_image
    $query = $this->pdo->prepare("
        INSERT INTO event_image (image_path, image_order, fk_id_event, cover_image_path)
        VALUES (:image_path, :image_order, :fk_id_event, :cover_image_path)
    ");

    $image_order = 1; // L'image principale a un ordre 1
    $query->bindValue(':image_path', $image_path, PDO::PARAM_STR);
    $query->bindValue(':cover_image_path', $cover_image_path, PDO::PARAM_STR);
    $query->bindParam(':image_order', $image_order, PDO::PARAM_INT);
    $query->bindParam(':fk_id_event', $eventId, PDO::PARAM_INT);
    $query->execute();
}


// Création de l'objet Event
$event = new Event();
$event->setNameEvent($_POST['name_event']);
$event->setNameGame($_POST['name_game']);
$event->setFkIdPlateforme($_POST['fk_id_plateforme']);
$event->setDateHourStart(new \DateTimeImmutable($_POST['date_hour_start']));
$event->setDateHourEnd(new \DateTimeImmutable($_POST['date_hour_end']));
$event->setNombreDeJoueurs($_POST['nombre_de_joueurs']);
$event->setDescription($_POST['description']);
$event->setVisibility(Visibility::tryFrom($_POST['visibility']));

// Données d'images
$cover_image_path = $_POST['cover_image_path'];
$image_path = $_POST['image_path'];

// Appel à la méthode de création
$eventRepository = new EventRepository();
$eventRepository->creationEvent($event, $cover_image_path, $image_path);

_____________________________________________________________________________________________________________________

<?php

use PDO;

class EventRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function creationEvent(Event $event, array $cover_image, array $images, int $fk_id_user)
    {
        try {
            // Démarrer la transaction
            $this->pdo->beginTransaction();

            // Vérification de l'existence de l'utilisateur
            $stmtUserCheck = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE id_user = :id");
            $stmtUserCheck->bindParam(':id', $fk_id_user, PDO::PARAM_INT);
            $stmtUserCheck->execute();
            if ($stmtUserCheck->fetchColumn() == 0) {
                throw new \Exception("L'utilisateur spécifié n'existe pas.");
            }

            // Récupérer l'ID de la plateforme par son nom
            $plateformeName = $event->getPlateformeName();
            if (!empty($plateformeName)) {
                $stmtPlateforme = $this->pdo->prepare("SELECT id_plateforme FROM plateforme WHERE name = :name LIMIT 1");
                $stmtPlateforme->bindParam(':name', $plateformeName, PDO::PARAM_STR);
                $stmtPlateforme->execute();
                $plateformeData = $stmtPlateforme->fetch();
                if (!$plateformeData) {
                    throw new \Exception("La plateforme spécifiée n'existe pas.");
                }
                $fk_id_plateforme = $plateformeData['id_plateforme'];
                $event->setFkIdPlateforme($fk_id_plateforme);
            } else {
                throw new \Exception("Veuillez sélectionner une plateforme.");
            }

            // Insérer l'événement
            $this->insertEvent($event, $fk_id_user, $cover_image['name'] ?? null);

            $eventId = $event->getId();

            // Traiter l'image de couverture
            if (!empty($cover_image['name'])) {
                $this->uploadAndInsertImage($eventId, $cover_image, 0, 'cover'); // Ordre 0 pour l'image de couverture
            }

            // Traiter les images du diaporama
            if (!empty($images['name'][0])) {
                foreach ($images['name'] as $key => $name) {
                    if (!empty($name)) {
                        $this->uploadAndInsertImage($eventId, [
                            'name' => $name,
                            'tmp_name' => $images['tmp_name'][$key],
                            'size' => $images['size'][$key],
                            'type' => $images['type'][$key],
                            'error' => $images['error'][$key],
                        ], $key + 1, 'slide'); // Ordre commençant à 1 pour les images du diaporama
                    }
                }
            }

            // Si tout est ok, valider la transaction
            $this->pdo->commit();
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            $this->pdo->rollBack();
            throw new \Exception("Erreur lors de la création de l'événement : " . $e->getMessage());
        }
    }

    private function insertEvent(Event $event, int $fk_id_user, ?string $cover_image_name)
    {
        // Validation des données pour l'événement
        if (!isset($event->getNameEvent(), $event->getNameGame(), $event->getFkIdPlateforme(),
            $event->getDateHourStart(), $event->getDateHourEnd(), $event->getNombreDeJoueurs(),
            $event->getDescription())) { // Visibility est gérée différemment
            throw new \Exception("Vous devez remplir tous les champs de l'événement.");
        }

        // Déterminer la visibilité
        $visibility = 'public'; // Valeur par défaut
        if (isset($event->getVisibility()) && $event->getVisibility() === 'privé') {
            $visibility = 'privé';
        }

        // Préparer et exécuter l'insertion dans la table event
        $query = $this->pdo->prepare("
            INSERT INTO event (name_event, name_game, date_hour_start, date_hour_end, nombre_de_joueurs, description, visibility, fk_id_plateforme, fk_id_user, cover_image_path)
            VALUES (:name_event, :name_game, :date_hour_start, :date_hour_end, :nombre_de_joueurs, :description, :visibility, :fk_id_plateforme, :fk_id_user, :cover_image_path)
        ");

        $query->bindParam(':name_event', $event->getNameEvent(), PDO::PARAM_STR);
        $query->bindValue(':name_game', $event->getNameGame(), PDO::PARAM_STR);
        $query->bindValue(':date_hour_start', $event->getDateHourStart()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':date_hour_end', $event->getDateHourEnd()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':nombre_de_joueurs', $event->getNombreDeJoueurs(), PDO::PARAM_INT);
        $query->bindValue(':description', $event->getDescription(), PDO::PARAM_STR);
        $query->bindParam(':visibility', $visibility, PDO::PARAM_STR);
        $query->bindValue(':fk_id_plateforme', $event->getFkIdPlateforme(), PDO::PARAM_INT);
        $query->bindParam(':fk_id_user', $fk_id_user, PDO::PARAM_INT);
        $query->bindParam(':cover_image_path', $cover_image_name, PDO::PARAM_STR);
        $query->execute();

        // Récupérer l'ID de l'événement nouvellement inséré
        $event->setId($this->pdo->lastInsertId());
    }

    private function uploadAndInsertImage(int $eventId, array $file, int $order, string $type)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $maxSize = 2 * 1024 * 1024; // 2 Mo

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        $fileError = $file['error'];

        if ($fileError !== UPLOAD_ERR_OK) {
            // Gérer l'erreur de téléchargement spécifique
            error_log("Erreur de téléchargement pour " . $type . ": " . $fileError);
            return; // Ou throw new Exception selon votre gestion des erreurs
        }

        if ($fileSize > $maxSize) {
            error_log("Le fichier " . $fileName . " est trop volumineux.");
            return;
        }

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes) || !in_array($fileExt, $allowedExtensions)) {
            error_log("Type ou extension de fichier non autorisé pour " . $fileName);
            return;
        }

        $uploadDirectory = 'uploads/';
        $newFileName = uniqid('', true) . '.' . $fileExt;
        $destination = $uploadDirectory . $newFileName;

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        if (move_uploaded_file($fileTmpName, $destination)) {
            $this->insertEventImage($eventId, $destination, $order);
            if ($type === 'cover') {
                // Pas besoin de faire autre chose pour la couverture ici, l'info est déjà dans la table event
            }
        } else {
            error_log("Erreur lors du déplacement du fichier " . $fileName);
        }
    }

    private function insertEventImage(int $eventId, string $image_path, int $order)
    {
        $query = $this->pdo->prepare("
            INSERT INTO event_image (image_path, image_order, fk_id_event)
            VALUES (:image_path, :image_order, :fk_id_event)
        ");

        $query->bindParam(':image_path', $image_path, PDO::PARAM_STR);
        $query->bindParam(':image_order', $order, PDO::PARAM_INT);
        $query->bindParam(':fk_id_event', $eventId, PDO::PARAM_INT);
        $query->execute();
    }
}

    */

}
