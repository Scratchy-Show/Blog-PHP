<?php


namespace Controllers;

use Models\User;

class AdminController extends Controller // Hérite de la class Controller
{
    public function registration()
    {
        // Si présence des variables
        if (isset($_POST['lastName']) && isset($_POST['firstName']) && isset($_POST['email'])
            && isset($_POST['username']) && isset($_POST['password'])) {
            $lastName = $_POST['lastName'];
            $firstName = $_POST['firstName'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm'];

            // Crée une instance du controller
            $checkFormValues = new CheckFormValuesController;

            // Vérifie la valeur des variables
            $verifiedLastName = $checkFormValues->checkLastName($lastName);
            $verifiedFirstName = $checkFormValues->checkFirstName($firstName);
            $verifiedEmail = $checkFormValues->checkEmail($email);
            $verifiedPassword = $checkFormValues->checkPassword($password, $confirmPassword);
            // Vérifie si $email est unique
            $verifiedSingleEmail = $checkFormValues->checkSingleEmail($email);
            // Vérifie si le pseudo est unique
            $verifiedSingleUsername = $checkFormValues->checkSingleUsername($username);

            if (($verifiedLastName == 1) && ($verifiedFirstName == 1) &&
                ($verifiedEmail == 1) && ($verifiedPassword == 1) &&
                ($verifiedSingleEmail == null) && ($verifiedSingleUsername == null)) {

                // Crée une instance de User
                $user = new User;
                // Appelle la fonction registerUserByForm() avec les paramètres du formulaire
                $user->registerUserByForm($lastName, $firstName, $email, $username, $password);

                //  Redirection vers la page d'administration
                $this->render('admin.html.twig', array());
            } else {
                // Affiche le page d'inscription avec le message d'erreur
                $this->render('registration.html.twig', array(
                    "messageLastName" => $verifiedLastName,
                    "messageFirstName" => $verifiedFirstName,
                    "messageEmail" => $verifiedEmail,
                    "messagePassword" => $verifiedPassword,
                    "messageSingleEmail" => $verifiedSingleEmail,
                    "messageSingleUsername"  => $verifiedSingleUsername
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
        // Si présence des variables 'username' et 'password'
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Crée une instance de User
            $user = new User;
            // Appelle la fonction checkLogin() avec les paramètres du formulaire
            $checkUser = $user->getUserByLogin($username, $password);

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