<?php


namespace Controllers;

use Models\User;
use Controllers\CheckFormValuesController;

class AdminController extends Controller // Hérite de la class Controller
{
    public function registration()
    {
        // Si présence des variables
        if (isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['email'])
            && isset($_POST['login']) && isset($_POST['password'])) {
            $lastName = $_POST['lastName'];
            $firstName = $_POST['firstName'];
            $email = $_POST['email'];
            $login = $_POST['login'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm'];

            // Crée une instance du controller
            $checkValues = new CheckFormValuesController;

            // Le controlleur vérifie la valeur des variables
            $checkLastName = $checkValues->checkLastName($lastName);
            $checkFirstName = $checkValues->checkFirstName($firstName);
            $checkEmail = $checkValues->checkEmail($email);
            $checkPassword = $checkValues->checkPassword($password, $confirmPassword);

            if (($checkLastName == 1) && ($checkFirstName == 1) && ($checkEmail == 1) && ($checkPassword == 1)) {
                // Crée une instance de User
                $user = new User;
                // Appelle la fonction registerUserByForm() avec les paramètres du formulaire
                $user->registerUserByForm($lastName, $firstName, $email, $login, $password);

                //  Redirection vers la page d'administration
                $this->render('admin.html.twig', array());

            } else {
                // Affiche le page d'inscription avec le message d'erreur
                $this->render('registration.html.twig', array(
                    "messageLastName" => $checkLastName,
                    "messageFirstName" => $checkFirstName,
                    "messageEmail" => $checkEmail,
                    "messagePassword" => $checkPassword
                ));
            }
        }
        else {
            // Message d'erreur
            $message = "Vous n'avez pas rempli tous les champs du formulaire";
            // Affiche le page d'inscription par défaut
            $this->render('registration.html.twig', array("message" => $message));
        }
    }

    public function login()
    {
        // Si présence des variables 'login' et 'password'
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Crée une instance de User
            $user = new User;
            // Appelle la fonction checkLogin() avec les paramètres du formulaire
            $checkUser = $user->getUserByLogin($login, $password);

            // Si l'utilisateur est identifié
            if ($checkUser == true ) {
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
}