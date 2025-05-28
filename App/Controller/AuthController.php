<?php
namespace App\Controller;

use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Tools\Security;
use App\Tools\SendMail;
use App\Tools\Token;
use App\Tools\UserValidator;

class AuthController extends Controller
{
    public function route(): void
    {
        try {
            //On mes en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'connexion':
                        // On appelLe la méthode connexion() qui est plus bas
                        $this->connexion();
                        break;
                    case 'mdpOublie':
                        $this->mdpOublie();
                        break;
                    case 'mdpReinitialise':
                        $this->mdpReinitialise();
                        break;
                    case 'reinitialiserMdp':
                        $this->reinitialiserMdp();
                        break;
                    case 'deconnexion':
                        $this->deconnexion();
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

    protected function connexion()
    {
        $error = [];

        if (Security::csrfToken()) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
                if (isset($_POST['mail']) && isset($_POST['password'])) {
                    $mail     = $_POST['mail'];
                    $password = $_POST['password'];

                    $userRepo = new UserRepository();
                    $user     = $userRepo->findUserByMail($mail);

                    if ($user && Security::verifyPassword($password, $user->getPassword())) {
                            session_regenerate_id(true);                            
                            $_SESSION['user'] = [
                                'id'         => $user->getIdUser(),
                                'mail'       => $user->getMail(),
                                'last_name'  => $user->getLastName(),
                                'first_name' => $user->getFirstName(),
                                'pseudo'     => $user->getPseudo(),
                                'role'       => $user->getRole(),
                            ];
                            header('Location: index.php?controller=personal&action=espacePersonnel');
                    } else {
                        $error[] = "Identifiant ou mot de passe incorrect";
                    }
                }
            }
        }
        $this->render('auth/connexion', [
            'error' => $error,
        ]);
    }

    protected function mdpOublie()
    {
        try {
            $error = [];

            if (isset($_POST['resetPassword']) && isset($_POST['mail'])) {
                $mail = $_POST['mail'];

                $userRepo = new UserRepository();
                $user     = $userRepo->findUserByMail($mail);

                if ($mail === $user->getMail()) {
                    $tokenGenerate = new Token();
                    $token         = $tokenGenerate->generateToken();

                    $tokenRepository = new UserRepository();
                    $tokenTable      = $tokenRepository->forgottenPassword($user, $token);

                    SendMail::mailForgottenPassword($user->getLastName(), $user->getFirstName(), $user->getMail(), $tokenTable->getToken());
                }
            }

            $this->render('auth/mdpOublie', [
                'error' => $error,
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function mdpReinitialise()
    {
        $this->render('auth/mdpReinitialise', []);
    }

    protected function reinitialiserMdp()
    {
        try {
            $error = [];

            if (isset($_GET['token'])) {
                $tokenValue = $_GET['token'];

                $tokenRepo = new TokenRepository();
                $token     = $tokenRepo->findToken($tokenValue);

                if (! $token) {
                    throw new \Exception("Le token est invalide ou a expiré.");
                }

                $userId = $token->getFkIdUser();

                $tokenIsValidate = new Token();
                $tokenIsValidate->isTokenValid($token);

                if (! $tokenIsValidate->isTokenValid($token)) {
                    throw new \Exception("Le token a expiré.");
                }

                $userRepo = new UserRepository();
                $user     = $userRepo->findOneById($userId);

                if (! $user) {
                    throw new \Exception("Aucun utilisateur rattaché à ce mail.");
                }

                if (isset($_POST['resetPassword'])) {

                    $password        = $_POST['password'];
                    $passwordConfirm = $_POST['passwordConfirm'];
                    $error           = UserValidator::validatePasswords($password, $passwordConfirm);

                    if (empty($error)) {
                        $user->setPassword($password);
                        $userRepo->updatePassword($user);

                        $tokenRepo->deleteToken($tokenValue);

                        header('Location: index.php?controller=auth&action=mdpReinitialise');
                    }
                }
            } else {
                header('Location: index.php');
            }

            $this->render('auth/reinitialiserMdp', [
                'error' => $error,
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function deconnexion()
    {
        session_regenerate_id(true);
        session_destroy();
        unset($_SESSION);
        header('location: index.php?controller=auth&action=connexion');
    }
}
