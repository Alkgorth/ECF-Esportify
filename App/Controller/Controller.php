<?php

namespace App\Controller;

class Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['controller'])) {
                switch ($_GET['controller']) {
                    case 'pages':
                        $pageController = new UserController();
                        $pageController->route();
                        break;
                    case 'event':
                        $pageController = new EventController();
                        $pageController->route();
                        break;
                    case 'auth':
                        $pageController = new AuthController();
                        $pageController->route();
                        break;
                    case 'personal':
                        $pageController = new PersonalController();
                        $pageController->route();
                        break;
                    case 'admin':
                        $pageController = new AdminController();
                        $pageController->route();
                        break;
                    case 'footer':
                        $pageController = new FooterController();
                        $pageController->route();
                        break;
                    case 'subscription':
                        $pageController = new SubscriptionController();
                        $pageController->route();
                        break;
                    case 'rawgapikey':
                        $pageController = new RawgKeyApiController();
                        $pageController->route();
                        break;
                    default:
                        throw new \Exception("Désolé cette page n'existe pas 😣");
                        break;
                }
            } else {

                $pageController = new UserController();
                $pageController->home();
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }


    protected function render(string $path, array $params = []): void
    {

        $filePath = _ROOTPATH_ . '/templates/' . $path . '.php';

        try {
            if (!file_exists($filePath)) {
                throw new \Exception("Fichier non trouvé : " . $filePath);
            } else {
                extract($params);
                require_once $filePath;
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
