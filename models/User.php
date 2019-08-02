<?php


namespace Models;


use PDO;
use PDOException;

class User
{
    // Seule la class parent et les class filles accéde à ces varaibles
    protected $id;
    protected $lastName;
    protected $firstName;
    protected $email;
    protected $status;
    protected $login;
    protected $password;

    public function __construct()
    {
        // Connection à la bdd avec gestion des erreurs
        $this->dbb = new PDO('mysql:host=localhost;dbname=my_website;charset=utf8', 'root', '',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    // Vérifie la correspondance des identifiants
    public function checkLogin($login, $password) {
       // Gestion des erreurs
       try {
           // Requête préparée (plus sûr et plus rapide) qui recherche l'utilisateur possédant le login et le password donnés en paramètres.
           $query = $this->dbb->prepare('SELECT id FROM user WHERE login = :login AND password = :password');
           // Exécute la requête avec les paramètres indiqués dans l'array
           $query->execute(array('login' => $login, 'password' => $password));
           // Récupère le résultat sous forme de ligne
           $user = $query->fetch();
           // Retourne l'id de l'utilisateur
           return $user;
       }
       catch (PDOException $e) {
           echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
       }
    }

    ////// Getter //////

    public function getId()
    {
        return $this->id;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }


    ////// Setter //////

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}