<?php

require_once __DIR__ . '/autoload.php';
// Charge composer
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\Controller;
use Controllers\PageController;
use Controllers\AdminController;
use Controllers\PostController;

// Initialise une session
session_start();

// Analyse l'URL récupèré par la superglobale $_SERVER et renvoie le chemin de l'URL analysée
$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gestion des erreurs
try {
    // Page d'accueil
    if ($path_only == '/') {
        $home = new PageController();
        $home->index();
    }
    // Page d'inscription
    elseif ($path_only == '/registration') {
        $registration = new AdminController();
        $registration->registration();
    }
    // Page d'identification
    elseif ($path_only == '/login')
    {
        $login = new AdminController();
        $login->login();
    }
    // Déconnexion
    elseif ($path_only == '/logout') {
        $logout = new AdminController();
        $logout->logout();
    }
    // Page d'administration
    elseif ($path_only == '/admin')
    {
        $login = new AdminController();
        $login->admin();
    }
    // Page du formulaire - Ajout d'un article
    elseif ($path_only == '/admin/formAddPost')
    {
        $addPost = new PostController();
        $addPost->post();
    }
    // Ajouter un article
    elseif ($path_only == '/admin/addPost')
    {
        $addPost = new PostController();
        $addPost->addPost();
    }
    // Erreur 404
    else {
        $error404 = new Controller();
        $error404->redirectIfNotAdmin();
    }
}
catch (Exception $e) {
    // Affichage de l'erreur
    echo 'Erreur : ' . $e->getMessage();
}