<?php


namespace Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use PDOException;
use System\Database;

/**
 * @Entity
 * @Table(name="Comment")
 */
class Comment
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="comments")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Column(type="integer")
     */
    protected $author;

    /**
     * @ManyToOne(targetEntity="Post", inversedBy="comments")
     * @JoinColumn(name="post_id", referencedColumnName="id")
     * @Column(type="integer")
     */
    protected $post;

    /**
     * @Column(type="datetime")
     */
    protected $date;

    /**
     * @Column(type="boolean")
     */
    protected $validate;

    /**
     * @Column(type="text", length=65535)
     */
    protected $content;

    public function __construct()
    {
        // Définit le fuseau horaire
        date_default_timezone_set('Europe/Paris');
        // Par défaut, la date est la date d'aujourd'hui
        $this->date = new \DateTime();
        // Par défaut, la validation est à 0 (false)
        $this->validate = 0;
    }

    // Ajoute un nouveau commentaire
    public function addComment($content, $author, $postId)
    {
        // Définit les valeurs des variables
        $this->setContent($content);
        $this->setAuthor($author);
        $this->setPost($postId);

        // Récupère EntityManager dans l'application
        $entityManager = Database::getEntityManager();
        // Planifie la sauvegarde de l'entité
        $entityManager->persist($this);
        // Effectue la sauvegarde de l'entité en bdd
        $entityManager->flush();
    }

    // Récupère tous les commentaires validés d'un article
    public static function getValidateComment($postId)
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Comment
            $postRepository = Database::getEntityManager()->getRepository(Comment::class);
            // Récupère tous les commentaires validés par ordre croissant
            $threeLastPosts = $postRepository->findBy(
                array('post' => $postId, 'validate' => 1),
                array('date' => 'ASC')
            );
            // Retourne un tableau contenant les commentaires
            return $threeLastPosts;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère tous les commentaires d'un article de la bdd
    public static function getAllCommentsForPost($postId)
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Comment
            $postRepository = Database::getEntityManager()->getRepository(Comment::class);
            // Récupère tous les commentaires par ordre décroissant
            $commentsList = $postRepository->findBy(
                array('post' => $postId),
                array('date' => 'ASC')
            );
            // Retourne un tableau contenant tous les posts
            return $commentsList;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère un commentaire par son id
    public static function getComment($idComment)
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Comment
            $postRepository = Database::getEntityManager()->getRepository(Comment::class);
            // Récupère un post
            $comment = $postRepository->find($idComment);
            // Retourne le post
            return $comment;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Supprime un commentaire
    public function deleteCommentByHomeAdmin($idComment)
    {
        // Gestion des erreurs
        try {
            // Supprime le commentaire
            Database::getEntityManager()->remove($idComment);
            // Met à jour la bdd
            Database::getEntityManager()->flush($idComment);
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

    public function getAuthor()
    {
        return $this->author;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getContent()
    {
        return $this->content;
    }

    ////// Setter //////

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}

