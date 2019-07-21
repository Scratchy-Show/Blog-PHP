<?php


namespace Controllers;


use Twig_Environment;
use Twig_Loader_Filesystem;

class PageController
{
    public function index()
    {
        // Spécifie l'emplacement des templates Twig
        $this->loader = new Twig_Loader_Filesystem(__DIR__.'/../views');

        // Instancie Twig
        $this->twig = new Twig_Environment($this->loader);

        echo $this->twig->render('index.html.twig' );
    }
}