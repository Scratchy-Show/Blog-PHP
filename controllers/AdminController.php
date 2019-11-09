<?php


namespace Controllers;

use Models\Post;
use Models\User;

class AdminController extends Controller // Hérite de la class Controller et CheckFormValuesController
{
    public function newUserRegistration()
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

            // Si toute les variables sont renseignées
            if (!empty($lastName) && !empty($firstName) &&
                !empty($email) && !empty($username) &&
                !empty($password) && !empty($confirmPassword)) {

                // Vérifie si $email et $username sont unique
                $verifiedSingleUsernameEmail = $this->checkSingleUsernameEmail($email, $username);

                // Vérifie la valeur des variables
                $verifiedName = $this->checkName($lastName, $firstName);
                $verifiedEmail = $this->checkEmail($email);
                $verifiedPassword = $this->checkPassword($password, $confirmPassword);

                if (($verifiedName == 1) &&
                    ($verifiedEmail == 1) &&
                    ($verifiedPassword == 1) &&
                    ($verifiedSingleUsernameEmail == null)) {

                    // Crée une instance de User
                    $user = new User;
                    // Appelle la fonction qui enregistre un utilisateur avec les paramètres du formulaire
                    $user->registerUserByForm($lastName, $firstName, $email, $username, $password);

                    // Appel la methode qui va définir les variables de session
                    $this->setSessionVariables($user);

                    // Si HTTP_REFERER est déclaré renvoie sur l'URL précédente
                    if (isset($_SESSION['previousUrl'])) {
                        header('Location: ' . $_SESSION['previousUrl']);

                        // Empêche l'exécution du reste du script
                        die();
                    }
                    // Si HTTP_REFERER n'est pas déclaré renvoie sur la page d'accueil
                    else  {
                        // Message d'erreur
                        $refererEmpty = "Erreur: Veuillez recommencer";

                        // Redirection vers la page de modification d'un article
                        header("Location: /registration?message=" . $refererEmpty);

                        // Empêche l'exécution du reste du script
                        die();
                    }
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
            // Si une variable est vide
            else {
                // Message d'erreur
                $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";

                // Redirection vers la page de modification d'un article
                header("Location: /registration?message=" . $verifiedIfEmpty);

                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si il manque une variable
        else {
            // Message d'erreur
            $messageIssetVariable = "Erreur: Manque une variable pour pouvoir vous inscrire";

            // Redirection vers la page de modification d'un article
            header("Location: /registration?message=" . $messageIssetVariable);

            // Empêche l'exécution du reste du script
            die();
        }
    }

    public function connection()
    {
        // Si présence des variables 'username' et 'password'
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Si toute les variables sont renseignées
            if (!empty($username) && !empty($password)) {

                // Appelle la fonction static getUserByLogin() avec les paramètres du formulaire
                $checkUser = User::getUserByLogin($username, $password);

                // Si l'utilisateur est identifié
                if ($checkUser[0] == true) {
                    // Appel la methode qui va définir les variables de session
                    $this->setSessionVariables($checkUser[1]);

                    // Si l'utilisateur est un administrateur
                    if ($checkUser[1]->getRole() == 1) {
                        //  Redirige vers la page d'administration
                        header('Location: ' . '/admin?page=1');
                        // Empêche l'exécution du reste du script
                        die();
                    } // Si l'utilisateur n'est pas un administrateur
                    else {
                        // Si HTTP_REFERER est déclaré renvoie sur l'URL précédente
                        if (isset($_SESSION['previousUrl'])) {
                            header('Location: ' . $_SESSION['previousUrl']);
                            // Empêche l'exécution du reste du script
                            die();
                        } // Si HTTP_REFERER n'est pas déclaré renvoie sur la page d'accueil
                        else {
                            header('Location: ' . '/');
                            // Empêche l'exécution du reste du script
                            die();
                        }
                    }
                } // Si l'utilisateur ne c'est pas identifié correctement
                else {
                    // Message d'erreur
                    $message = "Erreur: Logins incorrect, veuillez réessayer";

                    // Redirection vers la page d'identification
                    $this->render('login.html.twig', array("message" => $message));
                }
            }
            // Si une variable est vide
            else {
                // Message d'erreur
                $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";

                // Redirection vers la page d'identification
                header("Location: /login?message=" . $verifiedIfEmpty);

                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si il manque une variable
        else {
            // Message d'erreur
            $messageIssetVariable = "Erreur: Manque une variable pour pouvoir vous connecter";

            // Redirection vers la page d'identification
            header("Location: /login?message=" . $messageIssetVariable);

            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Affiche la page d'administration avec une pagination
    public function admin($page)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Vérifie que le n° de page est un chiffre entier
        if (ctype_digit($page)) {

            // Si la page éxiste
            if ($page >= 1) {

                // Définit le nombres d'articles par page
                $nbPerPage = 5;

                // Récupère tous les posts de la bdd
                $listsPosts = Post::getAllPostsWithPaging($page, $nbPerPage);

                // Calcule le nombre total de pages
                $nbPages = ceil(count($listsPosts)/$nbPerPage);

                // Si la page éxiste
                if ($page <= $nbPages) {

                    // Redirection par défaut
                    if (empty($_GET['message'])) {
                        // Affiche la page d'administration avec les posts
                        $this->render('homeAdmin.html.twig', array(
                            "listPosts" => $listsPosts,
                            "nbPages" => $nbPages,
                            "page" => $page
                        ));
                    }
                    // Redirection après ajout, modification ou suppression d'un article
                    else {
                        // Affiche la page d'administration avec les posts et le message
                        $this->render('homeAdmin.html.twig', array(
                            "listPosts" => $listsPosts,
                            "nbPages" => $nbPages,
                            "page" => $page,
                            'message' => $_GET['message']
                        ));
                    }
                }
                // Si il y a aucun article
                else {
                    // Affiche un message d'information
                    $this->render('homeAdmin.html.twig', array(
                        'listPosts' => $listsPosts
                    ));
                }
            }
            // Si la page n'éxiste pas
            else {
                // Redirection vers la 404
                header("Location: /error404");
                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si ce n'est pas un chiffre entier
        else {
            // Redirection vers la 404
            header("Location: /error404");

            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Déconnecte l'utilisateur
    public function logout()
    {
        // Détruit les variables de la session
        session_unset();
        // Détruit la session
        session_destroy();
        // Redirection vers la page d'identification
        header('Location: ' . '/login');
        // Empêche l'exécution du reste du script
        die();
    }
}