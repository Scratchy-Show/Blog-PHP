<?php

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

use \Controllers\PageController;

if (isset($_GET['action'])) {
    echo 'OK';
}
else {
    $home = new PageController();
    $home->index();
}