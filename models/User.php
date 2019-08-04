<?php


namespace Models;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use PDO;
use PDOException;

/**
 * @Entity
 * @Table(name="User")
 */
class User
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string", name="last_name")
     */
    protected $lastName;

    /**
     * @Column(type="string", name="first_name")
     */
    protected $firstName;

    /**
     * @Column(type="string")
     */
    protected $email;

    /**
     * @Column(type="boolean")
     */
    protected $role;

    /**
     * @Column(type="string")
     */
    protected $login;

    /**
     * @Column(type="string")
     */
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

    public function getRole()
    {
        return $this->role;
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

    public function setStatus($role)
    {
        $this->role = $role;
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