<?php


namespace Controllers;

use Models\Comment;
use Models\Post;

class CommentController extends Controller // Hérite de la class Controller et CheckFormValuesController
{
    // Soumettre un commentaire
    public function submitComment() {
        // Si l'utilisateur est connecté, il peut soumettre un commentaire
        if (!empty($_SESSION['user'])) {

            // Si présence des variables
            if (isset($_POST['content']) && isset($_POST['postId'])) {

                // Par défaut, l'auteur est l'utilisateur connecté
                $author = $_SESSION['user']->getId();
                // Récupère les variables
                $content = $_POST['content'];
                $postId = $_POST['postId'];

                // Vérifie que les valeurs des variables ne soient pas vide
                $verifiedIfEmptyAuthor = $this->checkIfEmpty($author);
                $verifiedIfEmptyContent = $this->checkIfEmpty($content);
                $verifiedIfEmptyPostId = $this->checkIfEmpty($postId);

                // Si toutes les variables sont renseignées
                if (($verifiedIfEmptyAuthor == 1) &&
                    ($verifiedIfEmptyContent == 1) &&
                    ($verifiedIfEmptyPostId == 1)
                ) {
                    // Crée une instance de Comment
                    $comment = new Comment;
                    // Appelle la méthode qui enregistre un commentaire
                    $comment->addComment($content, $author, $postId);

                    // Message de confirmation
                    $messageCommentSendConfirmed = "Commentaire envoyé, en attente de modération.";
                    // Récupère l'url du site
                    $httpOrigin = $_SERVER['HTTP_ORIGIN'];
                    // Récupère l'article par son id
                    $post = Post::getPost($postId);
                    // Récupère le chemin de l'article
                    $path = $post->getPath();

                    // Redirection vers la page du post - En évitant l'affichage de plusieurs "message"
                    header("Location: " . $httpOrigin . "/post/" . $path . "?message=" . $messageCommentSendConfirmed);
                    // Empêche l'exécution du reste du script
                    die();
                }
                // Si une variable est vide
                else {
                    // Message d'erreur
                    $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";
                    // Récupère l'url de l'article
                    $url = $_SERVER['HTTP_REFERER'];

                    // Redirection vers la page du post avec le message d'erreur
                    header("Location: " . $url . "?message=" . $verifiedIfEmpty);
                    // Empêche l'exécution du reste du script
                    die();
                }
            }
            // Si il manque une variable
            else {
                // Message d'erreur
                $messageIssetVariable = "Erreur: Manque une variable pour pouvoir ajouter le commentaire";
                // Récupère l'url de l'article
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page du post avec le message d'erreur
                header("Location: ".$url."?message=".$messageIssetVariable);
                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si l'utilisateur n'est pas connecté
        else {
            // Redirection vers la page d'identification
            header("Location: /error404");
            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Affiche tous les commentaires d'un article pour l'administration
    public function commentsList($postId) {
        // Récupère tous les commentaires
        $comments = Comment::getAllCommentsForPost($postId);

        $this->render('commentsList.html.twig', array('comments' => $comments));
    }
}