<?php
namespace App\Controller;

use App\MongoRepository\MongoUserRepository;
use App\MongoRepository\MongoEventRepository;

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

        if (!isset($userId)) {
            throw new Exception("User not authenticatided");
        }

        $eventRepository = new MongoEventRepository();
        $userRepository = new MongoUserRepository();
    }
}