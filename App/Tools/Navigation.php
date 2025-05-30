<?php

namespace App\Tools;

class Navigation
{

    public static function addActiveClass($controller, $action)
    {
        if (isset($_GET['controller']) && $_GET['controller'] === $controller
            && isset($_GET['action']) && $_GET['action'] === $action) {
            return 'active';
        } else if (!isset($_GET['controller']) && $controller === 'pages' && $action === 'home') {
            return 'active';
        }
        return '';
    }

    // fonction pour ne plus avoir à ré-écrire le chemin et s'occuper des \ ou / 
    public function path(string ...$parts): string
    {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }

}