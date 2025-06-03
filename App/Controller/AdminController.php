<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Tools\UserValidator;
use App\Tools\SendMail;
use App\Tools\Security;

class AdminController extends Controller
{

    public function route(): void
    {
        try {
            //On mes en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'gestionEvent':
                        $this->gestionEvent();
                        break;
                    case 'gestionDroits':
                        $this->gestionDroits();
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

    protected function gestionEvent()
    {
        try {

            // if(isset($_SESSION['user']['role']) === 'administrateur'){

                $eventRepository = new EventRepository();
                $allEvent = $eventRepository->findEventAdmin();

            // } else {
            //     throw new \Exception("Vous n'êtes pas autorisé à accéder à cette page");
            //     // header('Location: index.php?');
            // }

            $this->render('admin/gestionEvent', [
                'allEvent' => $allEvent,
            ]);

        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage(),
            ]);
        }
        
    }

    protected function gestionDroits()
    {
        $this->render('admin/gestionDroits', []);
    }
}

