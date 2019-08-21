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

    // Affiche la page donnée en paramètre
    public function render($page, $arguments) {
        // Récupère l'url de la page précédente
        $_SESSION['previousUrl'] = $_SERVER['HTTP_REFERER'];

        echo $this->twig->render($page, $arguments);
    }

    // Vérifie le role de l'utilisateur
    public function isAdmin($user) {
        // Si l'utilisateur est administrateur
        if ($user->getRole() == 1 ) {
            //  Redirige vers la page d'administration
            $this->render('homeAdmin.html.twig', array());
        }
        // Si l'utilisateur n'est pas un administrateur
        else {
            $this->redirectIfNotAdmin();
        }
    }

    // Renvoie un utilisateur non administrateur
    public function redirectIfNotAdmin() {
        //  Redirige vers la page d'erreur 404
        $this->render('error404.html.twig', array());
    }
}