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
        header('Content-Type: application/json');

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
                return;
            }

            $csrfTokenFromRequest = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!Security::checkCsrfToken($csrfTokenFromRequest)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide. Requête refusée.']);
                return;
            }

            $userId = $_SESSION['user']['id'];
            $userName = $_SESSION['user']['pseudo'];

            if (empty($userId) || empty($userName)) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => "Informations utilisateur manquantes après vérification de connexion."]);
                return;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $eventId = $data['eventId'];
            
            if (!$eventId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "Identifiant de l'évènement manquant."]);
                return;
            }

            $eventRepository = new EventRepository();
            $event = $eventRepository->findOneById($eventId);

            if (!$event) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => "Événement introuvable."]);
                return;
            }

            $eventData = [
                'id' => (int)$event['id'],
                'name_event' => $event['name'],
                'date_start' => $event['start'],
                'date_end' => $event['end'],
                'nombre_de_joueurs' => (int)$event['joueurs'],
                'status' => $event['status'],
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

            $successUserUpdate = $userMongoRepository->addEventToUser($userData, $eventData);
            $successEventUpdate = $eventMongoRepository->addUserToEvent($eventData, $userData);



            if ($successUserUpdate && $successEventUpdate) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => "Inscription réussie."
                ]);
                return;
            } else {
                $errorMessage = '';
                if (!$successUserUpdate) {
                    $errorMessage .= "Échec ajout événement à l'utilisateur. ";
                }
                if (!$successEventUpdate) {
                    $errorMessage .= "Échec ajout utilisateur à l'événement.";
                }
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement dans la base de données : ' . trim($errorMessage)]);
                return;
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => "Erreur serveur : " . $e->getMessage()]);
            return;
        }
    }
}