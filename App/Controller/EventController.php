<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Status;
use App\Repository\EventRepository;
use App\Tools\EventValidator;
use App\Tools\ImageValidator;
use App\Tools\Security;
use DateTimeImmutable;

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
                $isAjax = isset($_GET['ajax']);

                // Charger le jeu par un appel au repository
                $eventRepository = new EventRepository();
                $eventDetail     = $eventRepository->findEventById($id);

                if (!$eventDetail) {
                    throw new \Exception("Événement introuvable.");
                }

                if ($isAjax) {
                    $this->render('event/modalContent', [
                        'eventDetail' => $eventDetail,
                        'cheminCouverture' => EventValidator::getCoverDir(),
                        'cheminDiaporama' => EventValidator::getDiaporamaDir(),
                    ]);
                } else {
                    $this->render('event/eventDetail', [
                        'eventDetail' => $eventDetail,
                    ]);
                }

            } else {
                throw new \Exception("L'id est manquant en paramètre");
            }

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    //Affichage des events validé, renommée en displayValid()
    protected function eventGeneral()
    {
        try {
            $eventRepository = new EventRepository();
            $allEvents = $eventRepository->findValidate();            

            $this->render('event/eventGeneral', [
                'allEvents' => $allEvents,
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
        try {

            if (! isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                header('Location: index.php?controller=connexions&action=connexion');
                exit();
            }

            $userId = $_SESSION['user']['id'];

            $error           = [];
            $majEvent        = "Votre demande de mise à jour a été envoyée.";
            $deleteEvent     = "L'évènement a bien été supprimé !";
            $eventRepository = new EventRepository();
            $myEvents = $eventRepository->myEvents($userId);
            $plateformes     = $eventRepository->getAllPlateformes();
            $event           = new Event();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $csrfTokenFromRequest = '';
    
                if (!empty($_POST['csrfToken'])) {
                    $csrfTokenFromRequest = $_POST['csrfToken'];
                }
    
                if (!Security::checkCsrfToken($csrfTokenFromRequest)) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide. Requête refusée.']);
                    return;
                }
            }

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
                } else {
                    $existingCoverPath = $_POST['existing_cover_image_path'] ?? null;
                    $event->setCoverImagePath(($existingCoverPath));
                }

                $finalDiapoImages = [];
                if (!empty($uploadedDiapoImages)) {
                    $finalDiapoImages = $uploadedDiapoImages;
                } else {
                    $finalDiapoImages = $_POST['existing_diaporama_image_paths'] ?? [];
                }

                $eventErrors = EventValidator::validateEvent($event);
                $error = array_merge($error, $eventErrors ?? []);
                $event->setFkIdUser($userId);

                if (empty($error)){
                    $updateEvent = $eventRepository->updateEvent($event);
                    if ($updateEvent) {
                        $eventRepository->updateEventImage($event->getIdEvent(), $finalDiapoImages);
                        $majEvent = "Votre demande de mise à jour à été envoyée.";
                        header('Location: index.php?controller=event&action=mesEvents');
                        exit();
                    } else {
                        $error['database'] = "Erreur lors de la mise à jour !";
                    }
                } else {
                    $myEvents = $eventRepository->myEvents($userId);
                }
        }

        if (isset($_POST['delete'])){
              $csrfTokenFromRequest = $_POST['csrfToken'] ?? '';
              if (!Security::checkCsrfToken($csrfTokenFromRequest)) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide. Requête refusée.']);
                return;
              }
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
            'events'      => $myEvents,
            'cheminCouverture' => EventValidator::getCoverDir(),
            'cheminDiaporama' => EventValidator::getDiaporamaDir(),
        ]);

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    //Affichage de la page création évènement
    protected function createEvent()
    {
        try {
            $error     = [];
            $affichage = "Votre proposition d'évènement à bien été envoyé.";
            $majEvent  = "Votre évènement à été mis à jour.";
            $eventRepository = new EventRepository();
            $plateformes     = $eventRepository->getAllPlateformes();

            if (! isset($_SESSION['user'])) {
                header('Location: index.php?controller=connexions&action=connexion');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $csrfTokenFromRequest = '';

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['csrfToken'])) {
                    $csrfTokenFromRequest = $_POST['csrfToken'];
                }

                if (!Security::checkCsrfToken($csrfTokenFromRequest)) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide. Requête refusée.']);
                    return;
                }

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

            var_dump($_SESSION);
            $userId = $_SESSION['user']['id'];

            $error           = [];
            $majEvent        = "Votre demande de mise à jour a été envoyée.";
            $deleteEvent     = "L'évènement a bien été supprimé !";
            $eventRepository = new EventRepository();
            $myEvents = $eventRepository->myEvents($userId);
            $plateformes     = $eventRepository->getAllPlateformes();
            $event           = new Event();
            $csrfTokenFromRequest = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $csrfTokenFromRequest = $_POST['csrfToken'];
    
                if (!Security::checkCsrfToken($csrfTokenFromRequest)) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide. Requête refusée.']);
                    return;
                }
            }


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
                } else {
                    $existingCoverPath = $_POST['existing_cover_image_path'] ?? null;
                    $event->setCoverImagePath(($existingCoverPath));
                }

                $finalDiapoImages = [];
                if (!empty($uploadedDiapoImages)) {
                    $finalDiapoImages = $uploadedDiapoImages;
                } else {
                    $finalDiapoImages = $_POST['existing_diaporama_image_paths'] ?? [];
                }

                $eventErrors = EventValidator::validateEvent($event);
                $error = array_merge($error, $eventErrors ?? []);
                $event->setFkIdUser($userId);

                if (empty($error)){
                    $updateEvent = $eventRepository->updateEvent($event);
                    if ($updateEvent) {
                        $eventRepository->updateEventImage($event->getIdEvent(), $finalDiapoImages);
                        $majEvent = "Votre demande de mise à jour à été envoyée.";
                        header('Location: index.php?controller=event&action=mesEvents');
                        exit();
                    } else {
                        $error['database'] = "Erreur lors de la mise à jour !";
                    }
                } else {
                    $myEvents = $eventRepository->myEvents($userId);
                }

            }

            if (isset($_POST['delete'])){
                $csrfTokenFromRequest = $_POST['csrfToken'] ?? '';
                if (!Security::checkCsrfToken($csrfTokenFromRequest)) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Jeton CSRF invalide. Requête refusée.']);
                    return;
                }
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
                'events'      => $myEvents,
                'cheminCouverture' => EventValidator::getCoverDir(),
                'cheminDiaporama' => EventValidator::getDiaporamaDir(),
            ]);

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }

    }
}
