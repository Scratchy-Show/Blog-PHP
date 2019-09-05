<?php


namespace Controllers;

use Models\User;

class AdminController extends Controller // Hérite de la class Controller et CheckFormValuesController
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

            // Vérifie la valeur des variables
            $verifiedName = $this->checkName($lastName, $firstName);
            $verifiedEmail = $this->checkEmail($email);
            $verifiedPassword = $this->checkPassword($password, $confirmPassword);
            // Vérifie si $email et $username sont unique
            $verifiedSingleUsernameEmail = $this->checkSingleUsernameEmail($email, $username);

            if (($verifiedName == 1) &&
                ($verifiedEmail == 1) &&
                ($verifiedPassword == 1) &&
                ($verifiedSingleUsernameEmail == null)) {

                // Crée une instance de User
                $user = new User;
                // Appelle la fonction registerUserByForm() avec les paramètres du formulaire
                $user->registerUserByForm($lastName, $firstName, $email, $username, $password);

                //  Redirection vers la page d'administration
                $this->render('homeAdmin.html.twig', array());
            } else {
                // Affiche le page d'inscription avec le message d'erreur
                $this->render('registration.html.twig', array(
                    "messageLastName" => $verifiedName[0],
                    "messageFirstName" => $verifiedName[1],
                    "messageEmail" => $verifiedEmail,
                    "messagePassword" => $verifiedPassword,
                    "messageSingleEmail" => $verifiedSingleUsernameEmail[0],
                    "messageSingleUsername"  => $verifiedSingleUsernameEmail[1]
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

            // Appelle la fonction static getUserByLogin() avec les paramètres du formulaire
            $checkUser = User::getUserByLogin($username, $password);

            // Si l'utilisateur est identifié
            if ($checkUser[0] == true) {
                // Appel la methode setSessionVariables
                $this->setSessionVariables($checkUser[1]);

                // Si l'utilisateur est un administrateur
                if (Controller::isAdmin($checkUser[1]) == true) {
                    //  Redirige vers la page d'administration
                    $this->render('homeAdmin.html.twig', array());
                }
                // Si l'utilisateur n'est pas un administrateur
                else {
                    // Si HTTP_REFERER est déclaré renvoie sur l'URL précédente
                    if (isset($_SESSION['previousUrl'])) {
                        header('Location: ' . $_SESSION['previousUrl']);
                    }
                    // Si HTTP_REFERER n'est pas déclaré renvoie sur la page d'accueil
                    else  {
                        header('Location: ' . 'http://my-blog');
                    }
                }
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

    // Déconnecte l'utilisateur
    public function logout()
    {
        // Détruit les variables de la session
        session_unset();
        // Détruit la session
        session_destroy();
        // Redirige vers l'URL précédente
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}