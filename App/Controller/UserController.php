<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Tools\SendMail;
use App\Tools\UserValidator;
use App\Tools\Security;


class UserController extends Controller
{

    public function route(): void
    {
        try {
            //On meT en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'home':
                        $this->home();
                        break;
                    case 'creationCompte':
                        $this->creationCompte();
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

    protected function home()
    {
        $eventRepository = new EventRepository();
        $event           = $eventRepository->homeDisplay();

        $this->render('pages/home', [
            'events' => $event,
        ]);
    }

    protected function creationCompte()
    {
        try {
            $error = [];

            $user = new User();

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

            if (isset($_POST['saveUser'])) {
                $user->hydrate($_POST);
                $user->setRole('joueur');
                $error = UserValidator::validate($user);

                if (empty($error)) {
                    $userRepository = new UserRepository();
                    $userRepository->persist($user);

                    SendMail::mailCreateUser($user->getLastName(), $user->getFirstName(), $user->getMail());

                    header('Location: index.php?controller=auth&action=connexion');
                    exit();
                }
            }

            $this->render('pages/creationCompte', [
                'user'           => '',
                'creationCompte' => 'Créer mon compte',
                'error'          => $error,
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
