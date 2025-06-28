<?php

namespace App\Controller;

use App\Tools\Security;
use Exception;

class RawgKeyApiController extends Controller
{
    public function route(): void
    {
        header('Content-Type: application/json');

        try {
            //On met en place une condition pour lancer le bon controller
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'rawgApiKey':
                        $this->rawgApiKey();
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

    public function rawgApiKey()
    {
        $search = $_GET['search'] ?? '';

        if (strlen($search) < 3) {
            http_response_code(400);
            echo json_encode(['error' => 'Recherche trop courte']);
            return;
        }

        $apiKey = getenv('RAWG_API_KEY');

        if (!$apiKey) {
            http_response_code(500);
            echo json_encode(['error' => 'Clé API non définie sur le serveur']);
            return;
        }
        
        $url = "https://api.rawg.io/api/games?key=" . urlencode($apiKey) . "&search=" . urlencode($search) . "&page_size=5";
        
        try {
            $response = file_get_contents($url);
            if ($response === false) {
                throw new \Exception("Erreur lors de l’appel à RAWG");
            }
            echo $response;
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

        echo $response;
    
    }
}