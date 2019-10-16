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
        // Page d'accueil
        if ($path_only == '/') {
            $home = new PageController();
            $home->index();
        }
        // Page listant les articles
        elseif ($path_only == '/posts') {
            // Récupère le numéro de la page de l'URL
            $page = $_GET['page'];

            $posts = new PageController();
            $posts->posts($page);
        }
        // Page affichant un article
        // preg_match() recherche une correspondance dans $path_only avec la regex et renvoie le résultat dans $matches
        // \w = [a-zA-Z0-9_]
        // Le + permet de transmettre l'integralité du $path de l'article
        // Le .* permet d'accepter que le path de l'article dans l'URL et rien d'autre après
        // $matches est un tableau qui contient toute l'url de l'article => ($matches[0]) ou
        // simplement la route de l'article => ($matches[1])
        elseif (preg_match('/\/post\/(\w+.*)/', $path_only, $matches)) {
            $post = new PageController();
            $post->post($matches[1]);
        }
        // Page soumettre un commentaire
        elseif ($path_only == '/comment') {
            $comment = new CommentController();
            $comment->submitComment();
        }
        // Page d'inscription
        elseif ($path_only == '/registration') {
            $registration = new AdminController();
            $registration->registration();
        }
        // Page d'identification
        elseif ($path_only == '/login') {
            $login = new AdminController();
            $login->login();
        }
        // Déconnexion
        elseif ($path_only == '/logout') {
            $logout = new AdminController();
            $logout->logout();
        }
        // Page d'administration
        elseif ($path_only == '/admin') {
            $login = new AdminController();
            $login->admin();
        }
        // Formulaire des articles
        elseif ($path_only == '/admin/postForm') {
            // Récupère l'id de l'URL
            $idPost = $_GET;

            $addPost = new PostController();
            $addPost->post($idPost);
        }
        // Ajouter un article
        elseif ($path_only == '/admin/addPost') {
            $addPost = new PostController();
            $addPost->addPost();
        }
        // Modifier un article
        elseif ($path_only == '/admin/editPost') {
            // Récupère l'id de l'URL
            $idPost = $_GET;

            $editPost = new PostController();
            $editPost->editPost($idPost);
        }
        // Supprimer un article
        elseif ($path_only == '/admin/deletePost') {
            // Récupère l'id de l'URL
            $idPost = $_GET;

            $deletePost = new PostController();
            $deletePost->deletePost($idPost);
        }
        // Affiche la liste des commentaires d'un post
        elseif ($path_only == '/admin/commentsList') {
            // Récupère l'id de l'URL
            $idPost = $_GET;

            $commentsList = new CommentController();
            $commentsList->commentsList($idPost);
        }
        // Supprimer un commentaire
        elseif ($path_only == '/admin/deleteComment') {
            // Récupère l'id de l'URL
            $idComment = $_GET;

            $deleteComment = new CommentController();
            $deleteComment->deleteComment($idComment);
        }
        // Erreur 404
        else {
            $error404 = new Controller();
            $error404->redirectIfNotAdmin();
        }
    } catch (Exception $e) {
        // Affichage de l'erreur
        echo 'Erreur : ' . $e->getMessage();
    }
}