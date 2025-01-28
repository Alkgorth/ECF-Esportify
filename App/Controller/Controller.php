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
                        // on appelle la méthode route du fichier PageController
                        $pageController->route();
                        break;
                    case 'event':
                        // charger controller event
                        $pageController = new EventController();
                        $pageController->route();
                        break;
                    case 'auth':
                        // charger controller jeux
                        $pageController = new AuthController();
                        $pageController->route();
                        break;
                    case 'admin':
                        // charger controller jeux

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
                // Extrait chaque ligne du tableau et créé des variables pour chacune
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
