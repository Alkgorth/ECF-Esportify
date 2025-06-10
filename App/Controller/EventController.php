<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Status;
use App\Repository\EventRepository;
use App\Tools\EventValidator;
use App\Tools\Security;

class EventController extends Controller
{
    public function route(): void
    {
        try {
            //On met en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'eventDetail':
                        $this->eventDetail($_GET['id']);
                        break;
                    case 'eventGeneral':
                        $this->eventGeneral();
                        break;
                    case 'eventGamer':
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

    protected function eventGeneral()
    {
        try {
            $eventRepository = new EventRepository();
            $allEvent = $eventRepository->findValidate();

            $this->render('event/eventGeneral', [
                'allEvent' => $allEvent,
            ]);

        } catch (\Exception $e) {
            $this->render('error/default', [
                'error' => $e->getMessage(),
            ]);
        }
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

            if (! isset($_SESSION['user'])) {
                header('Location: index.php?controller=connexions&action=connexion');
            }

            if (Security::csrfToken()) {

                $eventRepository = new EventRepository();
                $plateformes     = $eventRepository->getAllPlateformes();
                $event           = new Event();

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

                        $event->setFkIdUser($_SESSION['user']['id']);

                        $eventId = $eventRepository->insertEvent($event);

                        if ($eventId) {
                            $eventRepository->insertEventImage($eventId, $uploadedDiapoImages);
                            $affichage = "L'évènement à été créé avec succès !";
                            header('Location: index.php?');
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
                            header('Location: index.php?');
                        } else {
                            $error['database'] = "Erreur lors de la mise à jour !";
                        }
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

    //Affichage des évènement de l'utilisateur dans son espace personnel
    protected function mesEvents()
    {
        try {
            
            if (! isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                header('Location: index.php?controller=connexions&action=connexion');
                exit();
            }

            $userId = $_SESSION['user']['id'];

            $error           = [];
            $majEvent        = "Votre demande de mise à jour à été envoyée.";
            $deleteEvent     = "L'évènement à bien été supprimé !";
            $eventRepository = new EventRepository();
            $myEvent = $eventRepository->myEvents($userId);
            $plateformes     = $eventRepository->getAllPlateformes();
            $event           = new Event();


            if (Security::csrfToken()) {

            if (isset($_POST['saveUpdate'])) {
                $event->hydrate($_POST);

                if (!empty($_POST['id_event'])) {
                    $event->setIdEvent((int) $_POST['id_event']);
                } else {
                    $error['id_event'] = "L'identifiant de l'évènement est manquant";
                };

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

                $event->setFkIdUser($userId);

                if (empty($error)){
                    $updateEvent = $eventRepository->updateEvent($event);
                    
                    if ($updateEvent) {
                        $eventRepository->updateEventImage($event->getIdEvent(), $uploadedDiapoImages);
                        $majEvent = "Votre demande de mise à jour à été envoyée.";
                        header('Location: index.php?controller=event&action=mesEvents');
                    } else {
                        $error['database'] = "Erreur lors de la mise à jour !";
                    }
                } else {
                    $myEvent = $eventRepository->myEvents($userId);
                }

            }
        }

        if (isset($_POST['delete']) && Security::csrfToken()){
            if (!empty($_POST['id_event'])) {
                $eventIdToDelete = (int) $_POST['id_event'];
                $eventRepository->deleteEvent($eventIdToDelete);
                header('Location: index.php?controller=event&action=mesEvents');
                exit();
            } else {
                $error['delete'] = "L'évènement à supprimer n'a pas été trouvé.";
            }
        }

        if (empty($plateformes)) {
            throw new \Exception("Aucune donnée n'a été trouvée");
        }

        $this->render('event/mesEvents', [
            'deleteEvent' => $deleteEvent,
            'majEvent'    => $majEvent,
            'plateformes' => $plateformes,
            'error'       => $error,
            'events'      => $myEvent,
        ]);

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }

    }
}
