<?php


namespace Models;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * @Column(type="string", length=255)
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
    protected $createDate;

    /**
     * @Column(type="datetime")
     */
    protected $updateDate;

    /**
     * @Column(type="string", length=25)
     */
    protected $author;

    /**
     * @Column(type="string", length=255)
     */
    protected $path;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="post")
     */
    protected $comments;



    public function __construct()
    {
        // Définit le fuseau horaire
        date_default_timezone_set('Europe/Paris');
        // Par défaut, la date de création est la date d'aujourd'hui
        $this->createDate = new \DateTime();
        // Par défaut, la date de mise à jour est la date d'aujourd'hui
        $this->updateDate = new \DateTime();
        // Liste des commentaires
        $this->comments = new ArrayCollection();
    }

    // Récupère tous les postes de la bdd avec une pagination
    public static function getAllPostsWithPaging($page, $nbPerPage)
    {
        // Gestion des erreurs
        try {
            // Crée un requête
            $queryBuilder = Database::getEntityManager()->createQueryBuilder();

            // Requête
            $queryBuilder
                // Sélection la table
                ->select('post')
                // Définit la table
                ->from(Post::class, 'post')
                // Définit l'ordre d'affichage du plus récent ou plus ancien
                ->orderBy('post.createDate', 'desc')
                // Définit l'annonce à partir de laquelle commencer la liste
                ->setFirstResult(($page - 1) * $nbPerPage)
                // Définit le nombre d'annonce à afficher sur une page
                ->setMaxResults($nbPerPage);

            // Retourne l'objet Paginator correspondant à la requête
            return new Paginator($queryBuilder, true);
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
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
                array('createDate' => 'desc')
            );
            // Retourne un tableau contenant tous les posts
            return $listPosts;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère les 3 derniers postes
    public static function getThreeLastPosts()
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Post
            $postRepository = Database::getEntityManager()->getRepository(Post::class);
            // Récupère les 3 derniers postes par ordre décroissant
            $threeLastPosts = $postRepository->findBy(
                array(),
                array('createDate' => 'desc'),
                3,
                0
            );
            // Retourne un tableau contenant les 3 posts
            return $threeLastPosts;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Récupère un post par son id
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

    // Récupère un post par sa route
    public static function getPostByPath($path)
    {
        // Gestion des erreurs
        try {
            // Repository dédié à l'entité Post
            $postRepository = Database::getEntityManager()->getRepository(Post::class);
            // Récupère un post
            $post = $postRepository->findOneBy(array('path' => $path));
            // Retourne le post
            return $post;
        }
        catch (PDOException $e) {
            echo 'Échec lors du lancement de la requête: ' . $e->getMessage();
        }
    }

    // Ajoute un nouvel article
    public function addPostByForm($title, $author, $summary, $content, $path)
    {
        // Définit les valeurs des variables
        $this->setTitle($title);
        $this->setAuthor($author);
        $this->setSummary($summary);
        $this->setContent($content);
        $this->setPath($path);

        // Récupère EntityManager dans l'application
        $entityManager = Database::getEntityManager();
        // Planifie la sauvegarde de l'entité
        $entityManager->persist($this);
        // Effectue la sauvegarde de l'entité en bdd
        $entityManager->flush();
    }

    // Modifie un article
    public function editPostByForm($title, $author, $summary, $content, $updateDate)
    {
        // Définit les valeurs des variables
        $this->setTitle($title);
        $this->setAuthor($author);
        $this->setSummary($summary);
        $this->setContent($content);
        $this->setUpdateDate($updateDate);

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

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getComments()
    {
        return $this->comments;
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

    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }
}