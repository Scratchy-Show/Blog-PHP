<?php

require_once __DIR__ . '/autoload.php';
// Charge composer
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\Controller;
use Controllers\PageController;
use Controllers\AdminController;
use Controllers\PostController;
use Controllers\CommentController;

// Initialise une session
session_start();

if (isset($_SERVER['REQUEST_URI'])) {
    // Analyse l'URL récupèré par la superglobale $_SERVER et renvoie le chemin de l'URL analysée
    $path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Gestion des erreurs
    try {
        if ($path_only == '/') {                                     // Page d'accueil
            $home = new PageController();
            $home->index();
        } elseif ($path_only == '/send') {                           // Envoie du formulaire de contact
            $sendMail = new PageController();
            $sendMail->sendMail();
        } elseif ($path_only == '/posts') {                         // Page listant les articles
            // Récupère le numéro de la page de l'URL
            $page = $_GET['page'];

            $posts = new PageController();
            $posts->posts($page);
        } elseif (preg_match('/\/post\/(\w+.*)/', $path_only, $matches)) {  // Page affichant un article
            // preg_match() recherche une correspondance dans $path_only avec la regex
            // et renvoie le résultat dans $matches
            // \w = [a-zA-Z0-9_]
            // Le + permet de transmettre l'integralité du $path de l'article
            // Le .* permet d'accepter que le path de l'article dans l'URL et rien d'autre après
            // $matches est un tableau qui contient toute l'url de l'article => ($matches[0]) ou
            // simplement la route de l'article => ($matches[1])
            $post = new PageController();
            $post->post($matches[1]);
        } elseif ($path_only == '/comment') {                      // Soumettre un commentaire
            $comment = new CommentController();
            $comment->submitComment();
        } elseif ($path_only == '/registration') {                 // Page d'inscription
            $registration = new PageController();
            $registration->registration();
        } elseif ($path_only == '/newUserRegistration') {         // Ajoute un nouvel utilisateur
            $newUserRegistration = new AdminController();
            $newUserRegistration->newUserRegistration();
        } elseif ($path_only == '/login') {                       // Page d'identification
            $login = new PageController();
            $login->login();
        } elseif ($path_only == '/connection') {                 // Connexion
            $connection = new AdminController();
            $connection->connection();
        } elseif ($path_only == '/logout') {                     // Déconnexion
            $logout = new AdminController();
            $logout->logout();
        } elseif ($path_only == '/admin') {                      // Page d'administration
            // Récupère le numéro de la page de l'URL
            $page = $_GET['page'];

            $login = new AdminController();
            $login->admin($page);
        } elseif ($path_only == '/admin/postForm') {            // Formulaire des articles
            // Récupère $Id et $page de l'URL
            $args = $_GET;

            $addPost = new PostController();
            $addPost->post($args);
        } elseif ($path_only == '/admin/addPost') {             // Ajouter un article
            $addPost = new PostController();
            $addPost->addPost();
        } elseif ($path_only == '/admin/editPost') {            // Modifier un article
            // Récupère l'id de l'URL
            $idPost = $_GET['id'];

            // Récupère le numéro de la page de l'URL
            $page = $_GET['page'];

            $editPost = new PostController();
            $editPost->editPost($idPost, $page);
        } elseif ($path_only == '/admin/deletePost') {         // Supprimer un article
            // Récupère l'id de l'URL
            $idPost = $_GET;

            $deletePost = new PostController();
            $deletePost->deletePost($idPost);
        } elseif ($path_only == '/admin/commentsList') {      // Affiche la liste des commentaires d'un post
            // Récupère l'id de l'URL
            $idPost = $_GET['id'];

            // Récupère le numéro de la page de l'URL
            $page = $_GET['page'];

            $commentsList = new CommentController();
            $commentsList->commentsList($idPost, $page);
        } elseif ($path_only == '/admin/validateComment') {   // Valider un commentaire
            // Récupère l'id de l'URL
            $idComment = $_GET;

            $validateComment = new CommentController();
            $validateComment->validateComment($idComment);
        } elseif ($path_only == '/admin/deleteComment') {    // Supprimer un commentaire
            // Récupère l'id de l'URL
            $idComment = $_GET;

            $deleteComment = new CommentController();
            $deleteComment->deleteComment($idComment);
        } else {                                            // Erreur 404
            $error404 = new Controller();
            $error404->redirectIfNotAdmin();
        }
    } catch (Exception $e) {
        // Affichage de l'erreur
        echo 'Erreur : ' . $e->getMessage();
    }
}
