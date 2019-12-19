<?php


namespace Controllers;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Controller extends CheckFormValuesController // Hérite de la class CheckFormValuesController
{
    // Seule la class parent et les class filles accéde à ces varaibles
    protected $loader;
    protected $twig;

    // Accéde à Twig
    public function __construct()
    {
        // Spécifie l'emplacement des templates Twig
        $this->loader = new FilesystemLoader(__DIR__.'/../views');
        // Instancie Twig
        $this->twig = new Environment($this->loader);
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

    // Créer un jeton
    public function createToken()
    {
        // Si le jeton de session est déjà définie
        if (isset($_SESSION['token'])) {
            // Suppression du jeton existant
            unset($_SESSION['token']);
        }

        // Algorithme de hachage SHA256
        // bin2hex : Convertit des données binaires en représentation hexadécimale
        // openssl_random_pseudo_bytes : Génère une chaine pseudo-aléatoire d'octets

        // Création d'un nouveau jeton
        $token = hash('sha256', bin2hex(openssl_random_pseudo_bytes(6)));

        // Définie la variable de session
        $_SESSION['token'] = $token;

        // Récupère l'heure de la création du jeton
        $_SESSION['token_time'] = time();

        return $token;
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

        try {
            echo $this->twig->render($page, $arguments);
        } catch (LoaderError $e) {
            // Affichage de l'erreur
            echo 'Erreur : ' . $e->getMessage();
        } catch (RuntimeError $e) {
            // Affichage de l'erreur
            echo 'Erreur : ' . $e->getMessage();
        } catch (SyntaxError $e) {
            // Affichage de l'erreur
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Redirige un utilisateur non identifié et non administrateur
    public function redirectIfNotLoggedOrNotAdmin()
    {
        // Si l'utilisateur n'est pas connecté ou n'est pas un administrateur
        if (empty($_SESSION['user']) || ($_SESSION['user']->getRole() != 1 )) {
            $this->redirectIfNotAdmin();
        }
    }

    // Redirige un utilisateur non administrateur
    public function redirectIfNotAdmin()
    {
        //  Redirige vers la page d'erreur 404
        $this->render('error404.html.twig', array());
        // Empêche l'exécution du reste du script
        die();
    }
}
