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
        $this->render('admin/gestionEvent', []);
    }

    protected function gestionDroits()
    {
        $this->render('admin/gestionDroits', []);
    }
}

