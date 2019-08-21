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
     * @Column(type="string", name="last_name", length=25)
     */
    protected $lastName;

    /**
     * @Column(type="string", name="first_name", length=25)
     */
    protected $firstName;

    /**
     * @Column(type="string", length=50)
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
     * @Column(type="string", length=25)
     */
    protected $login;

    /**
     * @Column(type="string", name="password", length=255)
     */
    protected $hashedPassword;

    public function __construct()
    {
        // Définit le fuseau horaire
        date_default_timezone_set('Europe/Paris');
        // Par défaut, la date est la date d'aujourd'hui
        $this->date = new \DateTime();
        // Par défaut, le role est à 0 (false)
        $this->role =0;
    }

    // Vérifie la correspondance des identifiants
    public function getUserByLogin($login, $password) {
       // Gestion des erreurs
       try {
           // Repository dédié à l'entité User
           $userRepository = Database::getEntityManager()->getRepository(User::class);
           // Récupère l'utilisateur correspondant au login
           $user = $userRepository->findOneBy(["login" => "$login"]);

           // Si le login éxiste
           if ($user !== null ) {
               // Vérifie la correspondance de $password avec le mot de passe haché en BDD
               $checkPassword = password_verify($password, $user->getHashedPassword());
               // Retourne True ou False
               return $checkPassword;
           }
           // Si le login n'éxiste pas
           return false;
       }
       catch (PDOException $e) {
           echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
       }
    }

    // Enregistre un nouveau utilisateur
    public function registerUserByForm($lastName, $firstName, $email, $login, $password) {
        // Appelle hashPassword
        $hashedPassword = $this->hashPassword($password);

        // Définit les valeurs des variables
        $this->setLastName($lastName);
        $this->setFirstName($firstName);
        $this->setEmail($email);
        $this->setLogin($login);
        $this->setHashedPassword($hashedPassword);

        // Récupère EntityManager dans l'application
        $entityManager = Database::getEntityManager();
        // Planifie la sauvegarde de l'entité
        $entityManager->persist($this);
        // Effectue la sauvegarde de l'entité en bdd
        $entityManager->flush();
    }

    // Hachage du mot de passe
    public function hashPassword($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashedPassword;
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

    public function getHashedPassword()
    {
        return $this->hashedPassword;
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

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setHashedPassword($hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    public function setPassword($password)
    {
        $this->hashPassword($password);
    }
}