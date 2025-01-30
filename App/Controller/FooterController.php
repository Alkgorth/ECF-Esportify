<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Tools\UserValidator;
use App\Tools\SendMail;
use App\Tools\Security;

class FooterController extends Controller
{

    public function route(): void
    {
        try {
            //on mes en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'aPropos':
                        $this->aPropos();
                        break;
                    case 'confidentialite':
                        $this->confidentialite();
                        break;
                    case 'contacts':
                        $this->contacts();
                        break;
                    case 'faq':
                        $this->faq();
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

    protected function aPropos()
    {
        $this->render('footer/aPropos', []);
    }

    protected function confidentialite()
    {
        $this->render('footer/confidentialite', []);
    }

    protected function contacts()
    {
        $this->render('footer/contacts', []);
    }

    protected function faq()
    {
        $this->render('footer/faq', []);
    }
}
