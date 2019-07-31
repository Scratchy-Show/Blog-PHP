<?php


namespace Models;


class User
{
    protected $id;
    protected $lastName;
    protected $firstName;
    protected $email;
    protected $status;
    protected $login;
    protected $password;


    public function __construct()
    {
        // Connection à la bdd
        function dbConnect()
        {
            $db = new PDO('mysql:host=localhost;dbname=my_website;charset=utf8', 'root', '');
            return $db;
        }
    }

    // Vérifie la correspondance des identifiants
    public function checkLogin($login, $password) {
        $sql = $this->db->query('SELECT id FROM User WHERE login = :login AND password = :password');
        $result = $this->$sql;
        return $loginsData = $result->fetch();
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