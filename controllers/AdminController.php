<?php


namespace Controllers;

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
            $checkUser = $user->getUserByLogin($login, $password);

            // Si l'utilisateur est identifié il est redirigé sur la page d'administration
            if ($checkUser !== null ) {
                $this->render('admin.html.twig');
            }
            // Si l'utilisateur n'est pas identifié il est redirigé sur la page d'identification
            else {
                $this->render('login.html.twig');
            }
        }
        else {
            // Affiche le page d'identification par défaut
            $this->render('login.html.twig');
        }
    }
}