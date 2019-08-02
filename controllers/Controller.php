<?php


namespace Controllers;


use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller
{
    // Seule la class parent et les class filles accéde à ces varaibles
    protected $loader;
    protected $twig;

    // Accéde à Twig
    public function __construct()
    {
        // Spécifie l'emplacement des templates Twig
        $this->loader = new Twig_Loader_Filesystem(__DIR__.'/../views');

        // Instancie Twig
        $this->twig = new Twig_Environment($this->loader);
    }
}