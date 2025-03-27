<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventImage;
use App\Repository\EventRepository;
use App\Tools\EventValidator;

class EventController extends Controller
{
    public function route(): void
    {
        try {
            //on mes en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'event':
                        // Pour afficher un jeu
                        $this->event($_GET['id']);
                        break;
                    case 'eventGamer':
                        // Pour appler la méthode list(), tout les jeux
                        $this->eventGamer();
                        break;
                    case 'eventOrga':
                        $this->eventOrga();
                        break;
                    case 'eventAdmin':
                        $this->eventAdmin();
                        break;
                    case 'createEvent':
                        $this->createEvent();
                        break;
                    case 'delete':
                        // Pour appler la méthode delete()
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

    /*protected function event()
    {
        
        try {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                
                // Charger le jeu par un appel au repository
                $eventRepository = new EventRepository();
                $eventGlobal = $eventRepository->findGlobal($id); 

                $this->render('games/jeuxDetail', [
                    'gamesDetails' => $eventGlobal

                ]);
            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }*/

    protected function event()
    {
        $this->render('event/event', []);
    }

    protected function eventGamer()
    {
        $this->render('event/eventGamer', []);
    }

    protected function eventOrga()
    {
        $this->render('event/eventOrga', []);
    }

    protected function eventAdmin()
    {
        $this->render('event/eventAdmin', []);
    }

    protected function createEvent()
    {
        try {
            $error = [];

            // if (! isset($_SESSION['user'])) {
            //     header('Location: index.php?controller=connexions&action=connexion');
            // }

            $eventRepository = new EventRepository();
            $plateformes     = $eventRepository->getAllPlateformes();

            $event      = new Event();
            

            if (isset($_POST['valider'])) {
                $event->hydrate($_POST);
                $error = EventValidator::validateEvent($event);
                if (empty($error)) {
                    $eventRepository = new EventRepository();

                    header('Location: index.php?');
                }
            }

            $eventImage = new EventImage();
            $eventImage->hydrate(($_POST));
            $error = EventValidator::validateEventImage($eventImage);

            if (empty($plateformes)) {
                throw new \Exception("Aucune donnée n'a été trouvée");
            } else {
                $this->render('event/createEvent', [
                    'plateformes' => $plateformes,
                    'error'       => $error,
                ]);
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
