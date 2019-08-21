<?php

require_once __DIR__ . '/autoload.php';
// Charge composer
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\PageController;
use Controllers\AdminController;

// Initialise une session
session_start();

// Analyse l'URL récupèré par la superglobale $_SERVER et renvoie le chemin de l'URL analysée
$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gestion des erreurs
try {
    if ($path_only == '/') {
        $home = new PageController();
        $home->index();
    }
    elseif ($path_only == '/login')
    {
        $login = new AdminController();
        $login->login();
    }
    elseif ($path_only == '/registration') {
        $registration = new AdminController();
        $registration->registration();
    }
    else {
        // Erreur gérée, elle sera remontée jusqu'au bloc try
        throw new Exception('Page non trouvée.');
    }
}
catch (Exception $e) {
    // Affichage de l'erreur
    echo 'Erreur : ' . $e->getMessage();
}