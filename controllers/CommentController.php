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

                // Récupère l'url du site
                $httpOrigin = $_SERVER['HTTP_ORIGIN'];
                // Récupère l'article par son id
                $post = Post::getPost($postId);
                // Récupère le chemin de l'article
                $path = $post->getPath();

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

                    // Redirection vers la page du post - En évitant l'affichage de plusieurs "message"
                    header("Location: " . $httpOrigin . "/post/" . $path . "?message=" . $messageCommentSendConfirmed);
                    // Empêche l'exécution du reste du script
                    die();
                }
                // Si une variable est vide
                else {
                    // Message d'erreur
                    $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";

                    // Redirection vers la page du post - En évitant l'affichage de plusieurs "message"
                    header("Location: " . $httpOrigin . "/post/" . $path . "?message=" . $verifiedIfEmpty);
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
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Récupère tous les commentaires
        $comments = Comment::getAllCommentsForPost($postId);

        // Redirection par défaut
        if (count($_GET) == 1 ) {
            // Affiche la listes des commentaires d'un article
            $this->render('commentsList.html.twig', array('comments' => $comments));
        }
        // Redirection après suppression d'un commentaire
        else {
            // Affiche la listes des commentaires d'un article et le message de confirmation
            $this->render('commentsList.html.twig', array(
                "comments" => $comments,
                "message" => $_GET['message']
            ));
        }
    }

    // Supprimer un commentaire
    public function deleteComment($idComment)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Vérifie la présence de l'id
        if ($idComment != null) {

            // Récupère le commentaire
            $comment = Comment::getComment($idComment);

            // Si l'id correspond à un commentaire
            if ($comment != null) {
                // Appelle la méthode qui supprime un commentaire
                $comment->deleteCommentByHomeAdmin($comment);

                // Message de confirmation
                $messageCommentDeleteConfirmed = "Commentaire supprimé";

                // Récupère l'url précédente afin de revenir à la liste des commentaires
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page de la liste des articles du post avec le message
                header("Location: ".$url."&message=".$messageCommentDeleteConfirmed);

                // Empêche l'exécution du reste du script
                die();
            }
            // Si l'id n'a aucune correspondance
            else {
                // Message d'erreur
                $messageCommentDeleteFailed = "Erreur: Aucun commentaire correspond à cet id";

                // Récupère l'url précédente afin de revenir à la liste des commentaires
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page de la liste des articles du post avec le message
                header("Location: ".$url."&message=".$messageCommentDeleteFailed);

                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si l'id est vide
        $messageIdWithoutPost = "Erreur: Aucun id n'est renseigné";

        // Récupère l'url précédente afin de revenir à la liste des commentaires
        $url = $_SERVER['HTTP_REFERER'];

        // Redirection vers la page de la liste des articles du post avec le message
        header("Location: ".$url."&message=".$messageIdWithoutPost);

        // Empêche l'exécution du reste du script
        die();
    }
}