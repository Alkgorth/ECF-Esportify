<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Status;
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
                    case 'eventDetail':
                        // Pour afficher un jeu
                        $this->eventDetail($_GET['id']);
                        break;
                    case 'eventGeneral':
                        // Pour afficher un jeu
                        $this->eventGeneral($_GET['id']);
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
                    case 'mesEvents':
                        $this->mesEvents();
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

    protected function eventDetail()
    {
        try {

            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                // Charger le jeu par un appel au repository
                $eventRepository = new EventRepository();
                $eventDetail     = $eventRepository->findEventById($id);

                $this->render('event/eventDetail', [
                    'eventDetail' => $eventDetail,

                ]);
            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /*protected function event()
    {
        $this->render('event/event', []);
    }*/

    protected function eventGeneral()
    {
        $this->render('event/eventGeneral', []);
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

    //Affichage de la page création évènement
    protected function createEvent()
    {
        try {
            $error     = [];
            $affichage = "Votre proposition d'évènement à bien été envoyé.";
            $majEvent  = "Votre évènement à été mis à jour.";

            // if (! isset($_SESSION['user'])) {
            //     header('Location: index.php?controller=connexions&action=connexion');
            // }

            $eventRepository = new EventRepository();
            $plateformes     = $eventRepository->getAllPlateformes();
            $event           = new Event();

            if (! empty($_POST)) {
                if (! isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_COOKIE['csrf_token']) {
                    die('Token CSRF invalide');
                }
            }

            if (isset($_POST['valider']) || isset($_POST['modifier'])) {
                $event->hydrate($_POST);
                $event->setStatus(Status::EnAttente);

                $uploadResult        = EventValidator::secureImage($_FILES);
                $uploadedCoverImage  = $uploadResult['uploaded']['cover_image_path'] ?? null;
                $uploadedDiapoImages = $uploadResult['uploaded']['image_path'] ?? [];
                $uploadErrors        = $uploadResult['errors'];
                $error               = array_merge($error, $uploadErrors ?? []);

                if ($uploadedCoverImage) {
                    $event->setCoverImagePath($uploadedCoverImage);
                }

                $imageErrors = EventValidator::isFileUploaded($_FILES['image_path']);
                $eventErrors = EventValidator::validateEvent($event);
                $error       = array_merge($error, $eventErrors ?? [], $imageErrors ?? []);

                if (! empty($eventErrors)) {
                    $error = array_merge($error, $eventErrors);
                }

                if (! empty($imageErrors)) {
                    $error = array_merge($error, $imageErrors);
                }

                if (empty($error) && isset($_POST['valider'])) {

                    $event->setFkIdUser($_SESSION['user']['id_user'] ?? 1); //Retirer le ?? 1 pour éviter la suggestion d'event hors connexion

                    $eventId = $eventRepository->insertEvent($event);

                    if ($eventId) {
                        $eventRepository->insertEventImage($eventId, $uploadedDiapoImages);
                        $affichage = "L'évènement à été créé avec succès !";
                        //header('Location: index.php?');
                    } else {
                        $error['database'] = "Erreur lors de l'enregistrement !";
                    }
                }

                if (empty($error) && isset($_POST['modifier'])) {

                    $event->setFkIdUser($_SESSION['user']['id_user'] ?? 1);

                    $updateEvent = $eventRepository->updateEvent($event);

                    if ($updateEvent) {
                        $eventRepository->updateEventImage($event->getIdEvent(), $uploadedDiapoImages);
                        $affichage = $majEvent;
                        //header('Location: index.php?');
                    } else {
                        $error['database'] = "Erreur lors de la mise à jour !";
                    }
                }
            }

            if (empty($plateformes)) {
                throw new \Exception("Aucune donnée n'a été trouvée");
            } else {
                $this->render('event/createEvent', [
                    'affichage'   => $affichage,
                    'majEvent'    => $majEvent,
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

    //affichage des évènement de l'utilisateur dans son espace personnel
    protected function mesEvents()
    {
        try {
            $error           = [];
            $majEvent        = "Votre demande de mise à jour à été envoyée.";
            $eventRepository = new EventRepository();
            $myEvent = $eventRepository->myEvents();
            $plateformes     = $eventRepository->getAllPlateformes();
            $event           = new Event();

            //Ajouter controle du csrf_token

            if (empty($error) && isset($_POST['modifier'])) {
                $event->hydrate($_POST);
                $event->setStatus(Status::EnAttente);

                $uploadResult        = EventValidator::secureImage($_FILES);
                $uploadedCoverImage  = $uploadResult['uploaded']['cover_image_path'] ?? null;
                $uploadedDiapoImages = $uploadResult['uploaded']['image_path'] ?? [];
                $uploadErrors        = $uploadResult['errors'];
                $error               = array_merge($error, $uploadErrors ?? []);

                if ($uploadedCoverImage) {
                    $event->setCoverImagePath($uploadedCoverImage);
                }

                $imageErrors = EventValidator::isFileUploaded($_FILES['image_path']);
                $eventErrors = EventValidator::validateEvent($event);
                $error       = array_merge($error, $eventErrors ?? [], $imageErrors ?? []);

                if (! empty($eventErrors)) {
                    $error = array_merge($error, $eventErrors);
                }

                if (! empty($imageErrors)) {
                    $error = array_merge($error, $imageErrors);
                }

                $event->setFkIdUser($_SESSION['user']['id_user'] ?? 1);

                $updateEvent = $eventRepository->updateEvent($event);

                if ($updateEvent) {
                    $eventRepository->updateEventImage($event->getIdEvent(), $uploadedDiapoImages);
                    $majEvent = "Votre demande de mise à jour à été envoyée.";
                    //header('Location: index.php?');
                } else {
                    $error['database'] = "Erreur lors de la mise à jour !";
                }
            }

            if (empty($plateformes)) {
                throw new \Exception("Aucune donnée n'a été trouvée");
            } else {
                $this->render('event/mesEvents', [
                    'majEvent'    => $majEvent,
                    'plateformes' => $plateformes,
                    'error'       => $error,
                    'events'      => $myEvent,
                ]);
            }

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }

    }
}
