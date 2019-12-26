<?php


namespace Controllers;

use Models\Comment;
use Models\Post;
use Models\User;

class CommentController extends Controller // Hérite de la class Controller et CheckFormValuesController
{
    // Soumettre un commentaire
    public function submitComment()
    {
        // Si l'utilisateur est connecté, il peut soumettre un commentaire
        if (!empty($_SESSION['user'])) {
            // Si présence des variables
            if (isset($_POST['content']) && isset($_POST['postId'])) {
                // Récupère les variables
                $content = $_POST['content'];
                $postId = $_POST['postId'];

                // Récupère l'article par son id
                $post = Post::getPost($postId);

                // Si un post à été trouvé
                if ($post != false) {
                    // Récupère le chemin de l'article
                    $path = $post->getPath();

                    // Récupère l'url du site
                    $httpOrigin = $_SERVER['HTTP_ORIGIN'];

                    // Par défaut, l'auteur est l'utilisateur connecté
                    $authorId = $_SESSION['user']->getId();

                    // Si les variables sont renseignés
                    if (!empty($authorId) && !empty($content)) {
                        // Crée une instance de Comment
                        $comment = new Comment;

                        // Récupère l'auteur par son id
                        $author = User::getUserById($authorId);

                        // Appelle la méthode qui enregistre un commentaire
                        $comment->addComment($content, $author, $post);

                        // Message de confirmation
                        $messageCommentSendConfirmed = "Commentaire envoyé, en attente de validation";

                        // Redirection vers la page du post - En évitant l'affichage de plusieurs "message"
                        header("Location: " . $httpOrigin . "/post/" . $path . "?message="
                            . $messageCommentSendConfirmed . "#anchor-confirm");

                        // Empêche l'exécution du reste du script
                        die();
                    } else {
                        // Si une variable est vide

                        // Message d'erreur
                        $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";

                        // Redirection vers la page du post - En évitant l'affichage de plusieurs "message"
                        header("Location: " . $httpOrigin . "/post/" . $path . "?message="
                            . $verifiedIfEmpty . "#anchor-error");

                        // Empêche l'exécution du reste du script
                        die();
                    }
                } else {
                    // Si aucun post à été trouvé

                    // Message d'erreur
                    $noPostFound = "Erreur: Aucun article correspond à cet id";

                    // Redirection vers la page des articles
                    header("Location: /posts?&page=1&message=" . $noPostFound);

                    // Empêche l'exécution du reste du script
                    die();
                }
            } else {
                // Si il manque une variable

                // Message d'erreur
                $messageIssetVariable = "Erreur: Manque une variable pour pouvoir ajouter le commentaire";

                // Redirection vers la page des articles
                header("Location: /posts?&page=1&message=" . $messageIssetVariable);

                // Empêche l'exécution du reste du script
                die();
            }
        } else {
            // Si l'utilisateur n'est pas connecté

            // Redirection vers la page d'erreur 404
            header("Location: /error404");

            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Affiche tous les commentaires d'un article avec une pagination pour l'administration
    public function commentsList($postId, $page)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Si présence des variables
        if (isset($postId) && isset($page)) {
            // Si toutes les variables sont renseignées
            if (!empty($postId) && !empty($page)) {
                // Récupère le post
                $post = Post::getPost($postId);

                // Si le post éxiste
                if ($post != false) {
                    // Vérifie que le n° de page est un chiffre entier
                    if (ctype_digit($page)) {
                        // Si la page éxiste
                        if ($page >= 1) {
                            // Définit le nombres de commentaires par page
                            $nbPerPage = 10;

                            // Récupère tous les commentaires du post
                            $comments = Comment::getAllCommentsForPostWithPaging($postId, $page, $nbPerPage);

                            // Calcule le nombre total de pages
                            $nbPages = ceil(count($comments) / $nbPerPage);

                            // Si la page éxiste
                            if ($page <= $nbPages) {
                                // Redirection par défaut
                                if (empty($_GET['message'])) {
                                    // Affiche la listes des commentaires d'un article
                                    $this->render('commentsList.html.twig', array(
                                        'comments' => $comments,
                                        'post' => $post,
                                        "nbPages" => $nbPages,
                                        "page" => $page
                                    ));
                                } else {
                                    // Redirection après suppression d'un commentaire

                                    // Affiche la listes des commentaires d'un article et le message de confirmation
                                    $this->render('commentsList.html.twig', array(
                                        'comments' => $comments,
                                        'post' => $post,
                                        "nbPages" => $nbPages,
                                        "page" => $page,
                                        'message' => $_GET['message']
                                    ));
                                }
                            } else {
                                // Si il y a aucun commentaire

                                // Affiche un message d'information
                                $this->render('commentsList.html.twig', array(
                                    'comments' => $comments,
                                    'post' => $post,
                                ));
                            }
                        } else {
                            // Si la page n'éxiste pas

                            // Redirection vers la 404
                            header("Location: /error404");

                            // Empêche l'exécution du reste du script
                            die();
                        }
                    } else {
                        // Si ce n'est pas un chiffre entier

                        // Redirection vers la 404
                        header("Location: /error404");

                        // Empêche l'exécution du reste du script
                        die();
                    }
                } else {
                    // Si l'id correspond à aucun post

                    // Message d'erreur
                    $messagePostEditConfirmed = "Erreur: Aucun post correspond à cet id";

                    // Redirection vers la page d'administration
                    header("Location: /admin?&page=" . $page . "&message=".$messagePostEditConfirmed);

                    // Empêche l'exécution du reste du script
                    die();
                }
            } else {
                // Si une variable est vide

                // Message d'erreur
                $verifiedIfEmpty = "Erreur: Une variable n'a pas été renseigné";

                // Redirection vers la page d'administration
                header("Location: /admin?page=1&message=" . $verifiedIfEmpty);

                // Empêche l'exécution du reste du script
                die();
            }
        } else {
            // Si il manque une variable

            // Message d'erreur
            $messageIssetVariable = "Erreur: Manque une variable pour afficher les commentaires";

            // Redirection vers la page d'administration
            header("Location: /admin?page=1&message=" . $messageIssetVariable);

            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Valider un commentaire
    public function validateComment($idComment)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Vérifie la présence de l'id
        if ($idComment != null) {
            // Récupère le commentaire
            $comment = Comment::getComment($idComment);

            // Si l'id correspond à un commentaire
            if ($comment != false) {
                // Appelle la méthode qui valide un commentaire
                $comment->validateCommentByHomeAdmin($comment);

                // Message de confirmation
                $messageCommentDeleteConfirmed = "Commentaire validé";

                // Récupère l'url précédente afin de revenir à la liste des commentaires
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page de la liste des commentaires du post avec le message
                header("Location: ". $url . "&message=" . $messageCommentDeleteConfirmed);

                // Empêche l'exécution du reste du script
                die();
            } else {
                // Si l'id n'a aucune correspondance

                // Message d'erreur
                $messageCommentDeleteFailed = "Erreur: Aucun commentaire correspond à cet id";

                // Récupère l'url précédente afin de revenir à la liste des commentaires
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page de la liste des commentaires du post avec le message
                header("Location: " . $url . "&message=" . $messageCommentDeleteFailed);

                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si l'id est vide
        $messageIdWithoutPost = "Erreur: Aucun id n'est renseigné";

        // Récupère l'url précédente afin de revenir à la liste des commentaires
        $url = $_SERVER['HTTP_REFERER'];

        // Redirection vers la page de la liste des commentaires du post avec le message
        header("Location: " . $url . "&message=" . $messageIdWithoutPost);

        // Empêche l'exécution du reste du script
        die();
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

                // Redirection vers la page de la liste des commentaires du post avec le message
                header("Location: " . $url . "&message=" . $messageCommentDeleteConfirmed);

                // Empêche l'exécution du reste du script
                die();
            } else {
                // Si l'id n'a aucune correspondance

                // Message d'erreur
                $messageCommentDeleteFailed = "Erreur: Aucun commentaire correspond à cet id";

                // Récupère l'url précédente afin de revenir à la liste des commentaires
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page de la liste des commentaires du post avec le message
                header("Location: " . $url . "&message=" . $messageCommentDeleteFailed);

                // Empêche l'exécution du reste du script
                die();
            }
        }
        // Si l'id est vide
        $messageIdWithoutPost = "Erreur: Aucun id n'est renseigné";

        // Récupère l'url précédente afin de revenir à la liste des commentaires
        $url = $_SERVER['HTTP_REFERER'];

        // Redirection vers la page de la liste des commentaires du post avec le message
        header("Location: " . $url . "&message=" . $messageIdWithoutPost);

        // Empêche l'exécution du reste du script
        die();
    }
}
