<?php

require_once __DIR__ . '../autoload_twig.php';
require_once __DIR__ . '../vendor/autoload.php';
require_once __DIR__ . '/autoload.php';

use \Controllers\PageController;

if ($_GET['action'] ==  "/") {
    $home = new PageController();
    $home->index();
}
else {
    echo 'Erreur';
}