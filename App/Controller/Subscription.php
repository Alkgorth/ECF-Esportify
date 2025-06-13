<?php

namespace App\Controller;

use App\MongoRepository\MongoUserRepository;
use App\MongoRepository\MongoEventRepository;
use App\Repository\EventRepository;
use App\Tools\Security;
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
                        $this->subscribe();
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

    private function subscribe()
    {
        try {     
            
            if (!Security::isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
                exit;
            }

            $userId = $_SESSION['user']['id'];
            $userName = $_SESSION['user']['pseudo'];

            if (empty($userId) || empty($userName)) {
                throw new Exception("User not authenticatided");
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $eventId = $data['eventId'] ?? null;
            
            if (!$eventId) {
                throw new Exception("Identifiant de l'évènement manquant.");
            }

            $eventRepository = new EventRepository();
            $event = $eventRepository->findOneById($eventId);

            if (!$event) {
                throw new Exception("Événement introuvable.");
            }

            $eventData = [
                'id' => (int)$event['id'],
                'name' => $event['name'],
                'start' => $event['start'],
                'end' => $event['end'],
                'joueurs' => (int)$event['joueurs'],
                'status' => $event['visibility'],
            ];

            $userData = [
                'id' => (int)$userId,
                'pseudo' => $userName,
            ];

            $eventMongoRepository = new MongoEventRepository();
            $userMongoRepository = new MongoUserRepository();
            /*
            Condition pour vérifier si l'utilisateur est déjà inscrit à l'event
            Créer la méthode de vérification.
            if ($eventMongoRepository->isUserSubscribedToEvent($eventData['id'], $userData['id'])) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => "Vous êtes déjà inscrit à cet évènement."]);
                exit;
            }
            */
            $userMongoRepository->addEventToUser($userData, $eventData['id']);
            $eventMongoRepository->addUserToEvent($eventData, $userData['pseudo']);

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Inscription réussie.'
            ]);
            exit;

        } catch (\Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
            exit;
        }
    }
}