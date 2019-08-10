<?php


namespace Models;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use PDOException;
use System\Database;

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
     * @Column(type="datetime")
     */
    protected $date;

    /**
     * @Column(type="string")
     */
    protected $login;

    /**
     * @Column(type="string")
     */
    protected $password;


    // Vérifie la correspondance des identifiants
    public function getUserByLogin($login, $password) {
       // Gestion des erreurs
       try {
           // Repository dédié à l'entité User
           $userRepository = Database::getEntityManager()->getRepository(User::class);
           // Récupère l'utilisateur correspondant aux paramètres
           $user = $userRepository->findOneBy(["login" => "$login", "password" => "$password"]);
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

    public function getDate()
    {
        return $this->date;
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

    public function setDate($date)
    {
        $this->date = $date;
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