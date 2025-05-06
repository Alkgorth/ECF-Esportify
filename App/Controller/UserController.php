<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Tools\UserValidator;
use App\Tools\SendMail;
use App\Tools\Security;


class UserController extends Controller
{

    public function route(): void
    {
        try {
            //on mes en place une condition pour lancer le bon controller
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
                'error' => $e->getMessage()
            ]);
        }
    }

    // controller=page&action=home
    protected function home()
    {
        $eventRepository = new EventRepository();
        $event = $eventRepository->homeDisplay();
             
        $this->render('pages/home', [
            'events' => $event
        ]);
    }

    // controller=page&action=creationCompte
    protected function creationCompte()
    {
        try {
            $error = [];

            $user = new User();

            if (isset($_POST['saveUser'])) {
                $user->hydrate($_POST);
                $user->setRole('joueur');
                $error = UserValidator::validate($user);

                if (empty($error)) {
                    $userRepository = new UserRepository();
                    $userRepository->persist($user);

                    SendMail::mailCreateUser($user->getLastName(), $user->getFirstName(), $user->getMail());

                    header('Location: index.php?controller=auth&action=connexion');
                }
            }

            $this->render('pages/creationCompte', [
                'user' => '',
                'creationCompte' => 'Créer mon compte',
                'error' => $error
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
