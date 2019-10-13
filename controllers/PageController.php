<?php


namespace Controllers;


use Models\Comment;
use Models\Post;

class PageController extends Controller // Hérite de la class Controller et CheckFormValuesController
{
    // Affiche la page d'Accueil
    public function index()
    {
        // Récupère les 3 derniers postes
        $threeLastPosts = Post::getThreeLastPosts();

        $this->render('index.html.twig', array('threeLastPosts' => $threeLastPosts));
    }

    // Affiche la page des articles avec une pagination
    public function posts($page)
    {
        // Si la page éxiste
        if ($page >= 1) {
            // Définit le nombres d'articles par page
            $nbPerPage = 3;

            // Récupère tous les postes
            $posts = Post::getAllPostsWithPaging($page, $nbPerPage);

            // Calcule le nombre total de pages
            $nbPages = ceil(count($posts)/$nbPerPage);

            // Si la page éxiste
            if ($page <= $nbPages) {
                // Affiche la page des articles
                $this->render('posts.html.twig', array(
                    "posts" => $posts,
                    "nbPages" => $nbPages,
                    "page" => $page
                ));
            }
            // Si la page n'éxiste pas
            else {
                // Redirection vers la 404
                header("Location: /error404");
                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si la page n'éxiste pas
        else {
            // Redirection vers la 404
            header("Location: /error404");
            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Affiche un post
    public function post($path)
    {
        // Récupère l'article
        $post = Post::getPostByPath($path);
        // Récupère les commentaires associés
        $comments = Comment::getValidateComment($post->getId());

        // Si la route correspond à un article
        if ($post != null) {
            // Redirection par défaut
            if ($_GET == null ) {
                // Affiche la page de l'article
                $this->render('post.html.twig', array(
                    "post" => $post,
                    "comments" => $comments
                ));
            }
            // Redirection après ajout d'un commentaire
            else {
                // Affiche la page d'administration avec les posts et le message
                $this->render('post.html.twig', array(
                    "post" => $post,
                    "comments" => $comments,
                    "message" => $_GET['message']
                ));
            }
        }
        // Si la route ne correspond à aucun article
        else {
            // Redirection vers la 404
            header("Location: /error404");
            // Empêche l'exécution du reste du script
            die();
        }
    }
}