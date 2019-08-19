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
    protected $username;

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

    // Enregistre un nouveau utilisateur
    public function registerUserByForm($lastName, $firstName, $email, $username, $password) {
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

        // Définit les valeurs des variables
        $this->setLastName($lastName);
        $this->setFirstName($firstName);
        $this->setEmail($email);
        $this->setUsername($username);
        $this->setHashedPassword($hashedPassword);

        // Récupère EntityManager dans l'application
        $entityManager = Database::getEntityManager();
        // Planifie la sauvegarde de l'entité
        $entityManager->persist($this);
        // Effectue la sauvegarde de l'entité en bdd
        $entityManager->flush();
    }

    // Récupère un utilisateur avec son mail
    public function getUserByEmail($email) {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité User
            $userRepository = Database::getEntityManager()->getRepository(User::class);
            // Recherche un email correspondant
            $user = $userRepository->findBy(array('email' => $email));
            return $user;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère un utilisateur avec son pseudo
    public function getUserByUsername($username) {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité User
            $userRepository = Database::getEntityManager()->getRepository(User::class);
            // Recherche un pseudo correspondant
            $user = $userRepository->findBy(array('username' => $username));
            return $user;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère un utilisateur avec ses identifiants
    public function getUserByLogin($username, $password) {
       // Gestion des erreurs
       try {
           // Repository dédié à l'entité User
           $userRepository = Database::getEntityManager()->getRepository(User::class);
           // Récupère l'utilisateur correspondant au pseudo
           $user = $userRepository->findOneBy(["username" => "$username"]);

           // Si le login éxiste
           if ($user !== null ) {
               // Vérifie la correspondance de $password avec le mot de passe haché en BDD
               $checkPassword = password_verify($password, $user->getHashedPassword());
               // Retourne True ou False
               return $checkPassword;
           }
           // Si le login n'éxiste pas
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

    public function getUsername()
    {
        return $this->username;
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

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setHashedPassword($hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }
}