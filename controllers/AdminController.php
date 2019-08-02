<?php


namespace Controllers;

use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;
use Models\User;

class AdminController extends Controller // Hérite de la class Controller
{
    public function login()
    {
        // Si présence des variables 'login' et 'password'
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Crée une instance de User
            $user = new User;
            // Appelle la fonction checkLogin() avec les paramètres du formulaire
            $checkUser = $user->checkLogin($login, $password);

            // Si l'utilisateur est identifié il est redirigé sur la page d'administration
            if ($checkUser !== false ) {
                try {
                    echo $this->twig->render('admin.html.twig');
                }
                catch (Twig_Error_Loader $e)
                {
                    echo 'Erreur : ' . $e->getMessage();
                }
                catch (Twig_Error_Runtime $e)
                {
                    echo 'Erreur : ' . $e->getMessage();
                }
                catch (Twig_Error_Syntax $e)
                {
                    echo 'Erreur : ' . $e->getMessage();
                }
            }
            // Si l'utilisateur n'est pas identifié il est redirigé sur la page d'identification
            else {
                try {
                    echo $this->twig->render('login.html.twig');
                }
                catch (Twig_Error_Loader $e)
                {
                    echo 'Erreur : ' . $e->getMessage();
                }
                catch (Twig_Error_Runtime $e)
                {
                    echo 'Erreur : ' . $e->getMessage();
                }
                catch (Twig_Error_Syntax $e)
                {
                    echo 'Erreur : ' . $e->getMessage();
                }
            }
        }
        else {
            // Affiche le page d'identification
            try {
                echo $this->twig->render('login.html.twig');
            }
            catch (Twig_Error_Loader $e)
            {
                echo 'Erreur : ' . $e->getMessage();
            }
            catch (Twig_Error_Runtime $e)
            {
                echo 'Erreur : ' . $e->getMessage();
            }
            catch (Twig_Error_Syntax $e)
            {
                echo 'Erreur : ' . $e->getMessage();
            }
        }
    }
}