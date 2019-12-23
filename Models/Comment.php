<?php


namespace Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PDOException;
use System\Database;
use \DateTime;

/**
 * @Entity
 * @Table(name="comment")
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
     * @ManyToOne(targetEntity="Models\User", inversedBy="comments")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @ManyToOne(targetEntity="Models\Post", inversedBy="comments")
     * @JoinColumn(name="post_id", referencedColumnName="id")
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
        $this->date = new DateTime();
        // Par défaut, la validation est à 0 (false)
        $this->validate = 0;
    }

    // Ajoute un nouveau commentaire
    public function addComment($content, $author, $post)
    {
        // Définit les valeurs des variables
        $this->setContent($content);
        $this->setAuthor($author);
        $this->setPost($post);

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
        } catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère tous les commentaires d'un article
    public static function getAllCommentsForPost($postId)
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Comment
            $postRepository = Database::getEntityManager()->getRepository(Comment::class);
            // Récupère tous les commentaires
            $allComments = $postRepository->findBy(
                array('post' => $postId),
                array()
            );
            // Retourne un tableau contenant les commentaires
            return $allComments;
        } catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère tous les commentaires d'un article de la bdd avec pagination
    public static function getAllCommentsForPostWithPaging($postId, $page, $nbPerPage)
    {
        // Gestion des erreurs
        try {
            // Crée un requête
            $queryBuilder = Database::getEntityManager()->createQueryBuilder();

            // Requête
            $queryBuilder
                // Sélection la table
                ->select('comment')
                // Définit la table
                ->from(Comment::class, 'comment')
                // Correspondance du post
                ->where('comment.post = ?1')
                // Paramètres de correspondance
                ->setParameter(1, $postId)
                // Définit l'ordre d'affichage du plus récent ou plus ancien
                ->orderBy('comment.date', 'desc')
                // Définit l'annonce à partir de laquelle commencer la liste
                ->setFirstResult(($page - 1) * $nbPerPage)
                // Définit le nombre d'annonce à afficher sur une page
                ->setMaxResults($nbPerPage);

            // Retourne l'objet Paginator correspondant à la requête
            return new Paginator($queryBuilder, true);
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Valide un commentaire
    public function validateCommentByHomeAdmin($idComment)
    {
        // Gestion des erreurs
        try {
            // Appelle la méthode qui récupère le commentaire
            $comment = $this->getComment($idComment);

            // Valide le commentaire
            $comment->setValidate(true);

            // Met à jour la bdd
            Database::getEntityManager()->flush($idComment);
        } catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Supprime un commentaire
    public static function deleteCommentByHomeAdmin($idComment)
    {
        // Gestion des erreurs
        try {
            // Supprime le commentaire
            Database::getEntityManager()->remove($idComment);
            // Met à jour la bdd
            Database::getEntityManager()->flush($idComment);
        } catch (PDOException $e) {
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

    public function getDate()
    {
        return $this->date;
    }

    public function getValidate()
    {
        return $this->validate;
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

    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}
