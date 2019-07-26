<?php

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use \Controllers\PageController;

// Analyse l'URL récupèré par la superglobale $_SERVER et renvoie le chemin de l'URL analysée
$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path_only == '/') {
    $home = new PageController();
    $home->index();
}
else {
    echo 'Page non trouvée.';
}