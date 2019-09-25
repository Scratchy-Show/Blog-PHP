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
 * @Table(name="Post")
 */
class Post
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @Column(type="string", length=25)
     */
    protected $title;

    /**
     * @Column(type="text", length=65535)
     */
    protected $summary;

    /**
     * @Column(type="text", length=65535)
     */
    protected $content;

    /**
     * @Column(type="datetime")
     */
    protected $date;

    /**
     * @Column(type="string", length=25)
     */
    protected $author;



    public function __construct()
    {
        // Définit le fuseau horaire
        date_default_timezone_set('Europe/Paris');
        // Par défaut, la date est la date d'aujourd'hui
        $this->date = new \DateTime();
        // Par défaut, l'auteur est l'utilisateur connecté
        $this->author = $_SESSION['user']->getUsername();
    }

    // Récupère tous les postes de la bdd
    public static function getAllPosts()
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Post
            $postRepository = Database::getEntityManager()->getRepository(Post::class);
            // Récupère tous les postes par ordre décroissant
            $listPosts = $postRepository->findBy(
                array(),
                array('date' => 'desc')
            );
            // Retourne un tableau contenant tous les posts
            return $listPosts;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère un post
    public static function getPost($idPost)
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Post
            $postRepository = Database::getEntityManager()->getRepository(Post::class);
            // Récupère un post
            $post = $postRepository->find($idPost);
            // Retourne le post
            return $post;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Ajoute un nouvel article
    public function addPostByForm($title, $summary, $content)
    {
        // Définit les valeurs des variables
        $this->setTitle($title);
        $this->setSummary($summary);
        $this->setContent($content);

        // Récupère EntityManager dans l'application
        $entityManager = Database::getEntityManager();
        // Planifie la sauvegarde de l'entité
        $entityManager->persist($this);
        // Effectue la sauvegarde de l'entité en bdd
        $entityManager->flush();
    }

    // Modifie un nouvel article
    public function editPostByForm($title, $summary, $content)
    {
        // Définit les valeurs des variables
        $this->setTitle($title);
        $this->setSummary($summary);
        $this->setContent($content);

        // Récupère EntityManager dans l'application
        $entityManager = Database::getEntityManager();

        // Effectue la sauvegarde de l'entité en bdd
        $entityManager->flush();
    }

    // Supprime un article
    public function deletePostByHomeAdmin($post)
    {
        // Gestion des erreurs
        try {
            // Supprime le post
            Database::getEntityManager()->remove($post);
            // Met à jour la bdd
            Database::getEntityManager()->flush($post);
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    ////// Setter //////

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }
}