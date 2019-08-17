<?php


namespace Controllers;

use Models\User;

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

            // Vérifie la valeur des variables
            $checkLastName = $this->checkLastName($lastName);
            $checkFirstName = $this->checkFirstName($firstName);
            $checkEmail = $this->checkEmail($email);

            if (($checkLastName == 1) && ($checkFirstName == 1) && ($checkEmail == 1)) {
                // Crée une instance de User
                $user = new User;
                // Appelle la fonction registerUserByForm() avec les paramètres du formulaire
                $user->registerUserByForm($lastName, $firstName, $email, $login, $password);

                //  Redirection vers la page d'administration
                $this->render('admin.html.twig', array());

            } elseif (($checkLastName == 0) || ($checkFirstName == 0) || ($checkEmail == 0)) {
                // Affiche le page d'inscription avec le message d'erreur
                $this->render('registration.html.twig', array(
                    "messageLastName" => $checkLastName,
                    "messageFirstName" => $checkFirstName,
                    "messageEmail" => $checkEmail
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

    // Vérifie la valeur du nom transmis par l'utilisateur
    public function checkLastName($lastName)
    {
        // Vérifie que le nom correspond à l'expression régulières
        // Minimum 2 charactères - Maximum 25
        // Commence par une lettre (minuscule, majuscule, accentué)
        // Aucun chiffre
        // Accepte: minuscule, majuscule, lettre accentuée, tiret et apostrophe
        $checkLastName = preg_match("#^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-\']{1,25}$#", $lastName);

        if ($checkLastName == 0) {
            $messageLastName = "Le nom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

            return $messageLastName;
        }
        return $checkLastName;
    }

    // Vérifie la valeur du prénom transmis par l'utilisateur
    public function checkFirstName($firstName)
    {
        // Vérifie que le nom correspond à l'expression régulières
        // Minimum 2 charactères - Maximum 25
        // Commence par une lettre (minuscule, majuscule, accentué)
        // Aucun chiffre
        // Accepte: minuscule, majuscule, lettre accentuée, tiret et apostrophe
        $checkFirstName = preg_match("#^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\-\']{1,25}$#", $firstName);

        if ($checkFirstName == 0) {
            $messageFirstName = "Le prénom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

            return $messageFirstName;
        }
        return $checkFirstName;
    }

    // Vérifie que le mail correspond à l'expression régulières
    public function checkEmail($email)
    {
        // Vérifie le format de l'adresse mail
        $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        $checkEmail = preg_match("#^([a-z0-9_\.-]+\@[\da-z\.-]+\.[a-z\.]{2,6})$#", $filterEmail);

        if ($checkEmail == 0) {
            $messageEmail = "Le format du mail attendue est nom@exemple.fr";

            return $messageEmail;
        }
        return $checkEmail;
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