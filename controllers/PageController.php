<?php


namespace Controllers;


use Twig_Environment;
use Twig_Loader_Filesystem;

class PageController
{
    // Permet à toutes les fonctions de la classe d'accéder à Twig
    public function __construct()
    {
        // Spécifie l'emplacement des templates Twig
        $this->loader = new Twig_Loader_Filesystem(__DIR__.'/../views');

        // Instancie Twig
        $this->twig = new Twig_Environment($this->loader);
    }
    // Affiche la page d'Accueil
    public function index()
    {
        echo $this->twig->render('index.html.twig' );
    }
    // Affiche le page d'identification
    public function login()
    {
        echo $this->twig->render('login.html.twig' );
    }
}