<?php


namespace Controllers;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller extends CheckFormValuesController // Hérite de la class CheckFormValuesController
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
        // Permet d'accéder à la superglobale $_SESSION dans toutes les vues
        $this->twig->addGlobal('session', $_SESSION);
    }

    // Récupère HTTP_REFERER
    public function httpReferer()
    {
        // Si HTTP_REFERER est déclaré renvoie sur l'URL précédente
        if (isset($_SERVER['HTTP_REFERER'])) {
            $_SESSION['previousUrl'] = $_SERVER['HTTP_REFERER'];
        }
    }

    // Définie les variables de session
    public function setSessionVariables($user)
    {
        $_SESSION['user'] = $user;
    }

    // Affiche la page donnée en paramètre
    public function render($page, $arguments)
    {
        // Appelle httpReferer()
        $this->httpReferer();

        echo $this->twig->render($page, $arguments);
    }

    // Vérifie le role de l'utilisateur
    public function isAdmin($user)
    {
        // Si l'utilisateur n'est pas administrateur
        if ($user->getRole() != 1 ) {
            $this->redirectIfNotAdmin();
        }
    }

    // Redirige un utilisateur non administrateur
    public function redirectIfNotAdmin()
    {
        //  Redirige vers la page d'erreur 404
        $this->render('error404.html.twig', array());
    }
}