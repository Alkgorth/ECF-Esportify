<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Tools\UserValidator;

class PersonalController extends Controller
{

    public function route(): void
    {
        try {
            //On met en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'espacePersonnel':
                        $this->espacePersonnel();
                        break;
                    case 'espacePersonnelOrga':
                        $this->espacePersonnelOrga();
                        break;
                    case 'espacePersonnelAdmin':
                        $this->espacePersonnelAdmin();
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

    protected function espacePersonnel()
    {
        try {

            $error = [];
            $affichage = "Vos informations ont bien été modifiées";

            if (!isset($_SESSION['user'])) {
                header('Location: index.php?controller=auth&action=connexion');
            }

            if (isset($_POST['saveUser'])) {

                $user = new User();
                $user->hydrate($_POST);
                $user->setIdUser($_SESSION['user']['id']);
                $error = UserValidator::validate($user);

                if (empty($error)) {
                    $userRepository = new UserRepository();
                    $userRepository->persist($user);

                    $_SESSION['user'] = [
                        'mail' => $user->getMail(),
                        'last_name' => $user->getLastName(),
                        'first_name' => $user->getFirstName(),
                        'pseudo' => $user->getPseudo(),
                    ];
                }
            }

            if (isset($_POST['delete'])) {
                $userRepository = new UserRepository();
                $userRepository->delete($_SESSION['user']['id']);
                session_destroy();
                header('Location: index.php?controller=pages&action=home');
            }

            $this->render('personal/espacePersonnel', [
                'affichage' => $affichage,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function espacePersonnelOrga()
    {
        try {

            $error = [];
            $affichage = "Vos informations ont bien été modifiées";

            if (!isset($_SESSION['user'])) {
                header('Location: index.php?controller=auth&action=connexion');
            }

            if (isset($_POST['saveUser'])) {

                $user = new User();
                $user->hydrate($_POST);
                $user->setIdUser($_SESSION['user']['id']);
                $user->setRole($_SESSION['user']['role']);
                $error = UserValidator::validate($user);

                if (empty($error)) {
                    $userRepository = new UserRepository();
                    $userRepository->persist($user);

                    $_SESSION['user'] = [
                        'id' => $user->getIdUser(),
                        'mail' => $user->getMail(),
                        'last_name' => $user->getLastName(),
                        'first_name' => $user->getFirstName(),
                        'pseudo' => $user->getPseudo(),
                        'role' => $user->getRole()
                    ];
                }
            }

            if (isset($_POST['delete'])) {
                $userRepository = new UserRepository();
                $userRepository->delete($_SESSION['user']['id']);
                session_destroy();
                header('Location: index.php?controller=pages&action=home');
            }

            $this->render('personal/espacePersonnelOrga', [
                'affichage' => $affichage,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function espacePersonnelAdmin()
    {
        try {

            $error = [];
            $affichage = "Vos informations ont bien été modifiées";

            if (!isset($_SESSION['user'])) {
                header('Location: index.php?controller=auth&action=connexion');
            }

            if (isset($_POST['saveUser'])) {

                $user = new User();
                $user->hydrate($_POST);
                $user->setIdUser($_SESSION['user']['id']);
                $user->setRole($_SESSION['user']['role']);
                $error = UserValidator::validate($user);

                if (empty($error)) {
                    $userRepository = new UserRepository();
                    $userRepository->persist($user);

                    $_SESSION['user'] = [
                        'id' => $user->getIdUser(),
                        'mail' => $user->getMail(),
                        'last_name' => $user->getLastName(),
                        'first_name' => $user->getFirstName(),
                        'pseudo' => $user->getPseudo(),
                        'role' => $user->getRole()
                    ];
                }
            }

            if (isset($_POST['delete'])) {
                $userRepository = new UserRepository();
                $userRepository->delete($_SESSION['user']['id']);
                session_destroy();
                header('Location: index.php?controller=pages&action=home');
            }

            $this->render('personal/espacePersonnelAdmin', [
                'affichage' => $affichage,
                'error' => $error
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
