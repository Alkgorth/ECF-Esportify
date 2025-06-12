<?php
namespace App\Controller;

use App\MongoRepository\MongoUserRepository;
use App\MongoRepository\MongoEventRepository;
use App\Repository\EventRepository;
use Exception;

class SubscriptionController extends Controller
{
    public function route(): void
    {
        try {
            //On met en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'subscribe':
                        $this->subscribe($_POST['event_id']);
                        break;
                     default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                        break;
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function subscribe(int $event_id)
    {
        $userId = $_SESSION['user']['id'];
        $userName = $_SESSION['user']['pseudo'];

        if (!isset($userId)) {
            throw new Exception("User not authenticatided");
        }

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $eventId = $data['eventId'] ?? null;

        $eventRepository = new EventRepository();
        $event = $eventRepository->findOneById($eventId);

        if (!$event) {
            throw new Exception("Événement introuvable.");
        }

        $eventData = [
            'id' => $event['id'],
            'name' => $event['name'],
            'start' => $event['start'],
            'end' => $event['end'],
            'joueurs' => $event['joueurs'],
            'status' => $event['status'],
        ];

        $userData = [
            'id' => $userId,
            'pseudo' => $userName,
        ];

        $eventMongoRepository = new MongoEventRepository();
        $eventMongoRepository->addUserToEvent($eventData['id'], $userData);
        
        $userMongoRepository = new MongoUserRepository();
        $userMongoRepository->addEventToUser($userData['id'], $eventData);

        echo json_encode([
            'success' => true,
            'message' => 'Inscription réussie.'
        ]);
        exit;
    }
}