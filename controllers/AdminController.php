<?php


namespace Controllers;

use Exception;
use Models\User;

class AdminController
{
    public function loginPage()
    {
        // Vérifie la présence des variables 'login' et 'password'
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $user = checkLogin($login, $password);
        }
        else {
            throw new Exception('Absence de logins.');
        }
    }
}