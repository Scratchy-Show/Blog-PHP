<?php


namespace Controllers;

use Models\User;
use System\Database;

class AdminController extends Controller // Hérite de la class Controller
{
    public function login()
    {
        // Si présence des variables 'login' et 'password'
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = htmlentities($_POST['login']);
            $password = htmlentities($_POST['password']);

            // Crée une instance de User
            $user = new User;
            // Appelle la fonction checkLogin() avec les paramètres du formulaire
            $checkUser = $user->getUserByLogin($login, $password);

            // Si l'utilisateur est identifié
            if ($checkUser !== null ) {
                //  Redirection vers la page d'administration
                $this->render('admin.html.twig', array("user" => $checkUser));
            }
            // Si l'utilisateur n'est pas identifié
            else {
                // Message d'erreur
                $message = "Logins incorrect, veuillez réessayer";
                // Redirection vers la page d'identification
                $this->render('login.html.twig', array("message" => $message));
            }
        }
        else {
            // Affiche le page d'identification par défaut
            $this->render('login.html.twig', array());
        }
    }

    public function registration()
    {
        // Si présence des variables
        if (isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['email'])
            && isset($_POST['login']) && isset($_POST['password'])) {
            $lastName = htmlentities($_POST['lastName']);
            $firstName = htmlentities($_POST['firstName']);
            $email = htmlentities($_POST['email']);
            $login = htmlentities($_POST['login']);
            $password = htmlentities($_POST['password']);

            // Crée une instance de User
            $user = new User;
            // Appelle la fonction registerUserByForm() avec les paramètres du formulaire
            $user->registerUserByForm($lastName, $firstName, $email, $login, $password);

            //  Redirection vers la page d'administration
            $this->render('admin.html.twig', array());
        }
        else {
            // Message d'erreur
            $message = "Vous n'avez pas rempli tous les champs du formulaire";
            // Affiche le page d'inscription par défaut
            $this->render('registration.html.twig', array("message" => $message));
        }
    }
}