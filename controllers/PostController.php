<?php


namespace Controllers;

use Models\Post;
use \DateTime;

class PostController extends Controller // Hérite de la class Controller et CheckFormValuesController
{
    // Affiche le formulaire d'ajout OU le formulaire de modification d'un article
    public function post($args)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // // Si args n'est pas vide = Modification d'un article
        if ($args != null) {
            $idPost = $args['id'];
            $page = $args['page'];

            // Vérifie que le n° de page est un chiffre entier
            if (ctype_digit($page)) {
                // Si présence des variables
                if (isset($idPost) && isset($page)) {
                    // Si les variables sont renseignés
                    if (!empty($idPost) && !empty($page)) {
                        // Crée une instance de Post
                        $newPost = new Post;

                        // Récupère le post
                        $post = $newPost->getPost($idPost);

                        // Si l'id correspond à un post
                        if (($post != null)) {
                            // Récupère les données a afficher
                            $id = $post->getId();
                            $title = $post->getTitle();
                            $author = $post->getAuthor();
                            $summary = $post->getSummary();
                            $content = $post->getContent();

                            // Affiche la page d'ajout d'article avec ses infos à modifier
                            $this->render('postForm.html.twig', array(
                                "id" => $id,
                                "title" => $title,
                                "author" => $author,
                                "summary" => $summary,
                                "content" => $content,
                                "page" => $page
                            ));
                        } else {
                            // Si l'id n'a aucune correspondance

                            // Message d'erreur
                            $messageIdWithoutPost = "Erreur: Aucun article ne correspond à cet id";

                            // Redirection vers la page d'administration
                            header("Location: /admin?page=" . $page . "&message=" . $messageIdWithoutPost);

                            // Empêche l'exécution du reste du script
                            die();
                        }
                    } else {
                        // Si une variable est vide

                        // Message d'erreur
                        $verifiedIfEmpty = "Erreur: Une variable n'a pas été renseignée";

                        // Redirection vers la page d'administration
                        header("Location: /admin?page=1&message=" . $verifiedIfEmpty);

                        // Empêche l'exécution du reste du script
                        die();
                    }
                } else {
                    // Si il manque une variable

                    // Message d'erreur
                    $messageIssetVariable = "Erreur: Manque une variable pour pouvoir modifier l'article";

                    // Redirection vers la page d'administration
                    header("Location: /admin?&page=1&message=" . $messageIssetVariable);

                    // Empêche l'exécution du reste du script
                    die();
                }
            } else {
                // Si ce n'est pas un chiffre entier

                // Message d'erreur
                $messageNotPageNumber = "Erreur: Ce n'est pas un numéro de page correct";

                // Redirection vers la page d'administration
                header("Location: /admin?page=1&message=" . $messageNotPageNumber);

                // Empêche l'exécution du reste du script
                die();
            }
        } else {
            // Si args est vide = Ajout d'un article

            // Affiche la page d'ajout d'article
            $this->render('postForm.html.twig', array());
        }
    }

    // Ajoute un article dans la bdd
    public function addPost()
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Si présence des variables
        if (isset($_POST['title-post']) && isset($_POST['summary']) && isset($_POST['content'])) {
            // Récupère les variables
            $title = $_POST['title-post'];
            $summary = $_POST['summary'];
            $content = $_POST['content'];

            // Par défaut, l'auteur est l'utilisateur connecté
            $author = $_SESSION['user']->getUsername();

            // Si toutes les variables sont renseignées
            if (!empty($title) && !empty($author) && !empty($summary) && !empty($content)) {
                // Nettoie le titre pour en faire une route
                $path = $this->cleanTitle($title);

                // Crée une instance de Post
                $post = new Post;

                // Appelle la méthode qui enregistre un post avec les paramètres du formulaire
                $post->addPostByForm($title, $author, $summary, $content, $path);

                // Message de confirmation
                $messagePostAddConfirmed = "Article ajouté";

                // Redirection vers la page d'administration
                header("Location: /admin?page=1&message=" . $messagePostAddConfirmed);

                // Empêche l'exécution du reste du script
                die();
            } else {
                // Si une variable est vide

                // Message d'erreur
                $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";

                // Affiche le formulaire d'ajout d'article avec le message d'erreur
                $this->render('postForm.html.twig', array("message" => $verifiedIfEmpty));
            }
        } else {
            // Si il manque une variable

            // Message d'erreur
            $messageIssetVariable = "Erreur: Manque une variable pour pouvoir ajouter l'article";

            // Affiche le formulaire d'ajout d'article avec le message d'erreur
            $this->render('postForm.html.twig', array("message" => $messageIssetVariable));
        }
    }

    // Modifier un article
    public function editPost($idPost, $page)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Vérifie que le n° de page est un chiffre entier
        if (ctype_digit($page)) {
            // Si présence des variables
            if (isset($_POST['title-post']) && isset($_POST['author']) &&
                isset($_POST['summary']) && isset($_POST['content'])) {
                // Récupère les variables
                $title = $_POST['title-post'];
                $author = $_POST['author'];
                $summary = $_POST['summary'];
                $content = $_POST['content'];
                $updateDate = new DateTime();

                // Si toutes les variables sont renseignées
                if (!empty($title) && !empty($author) && !empty($summary) && !empty($content)) {
                    // Récupère le post
                    $post = Post::getPost($idPost);

                    // Si l'id correspond à un post
                    if ($post != null) {
                        // Appelle la méthode qui enregistre un post avec les paramètres du formulaire
                        $post->editPostByForm($title, $author, $summary, $content, $updateDate);

                        // Message de confirmation
                        $messagePostEditConfirmed = "Article modifié";

                        // Redirection vers la page d'administration
                        header("Location: /admin?page=" . $page . "&message=" . $messagePostEditConfirmed);
                        // Empêche l'exécution du reste du script
                        die();
                    } else {
                        // Si l'id ne correspond pas à un post

                        // Message d'erreur
                        $messagePostEditConfirmed = "Erreur: Aucun post correspond à cet id";

                        // Redirection vers la page d'administration
                        header("Location: /admin?&page=" . $page . "&message=" . $messagePostEditConfirmed);

                        // Empêche l'exécution du reste du script
                        die();
                    }
                } else {
                    // Si une variable est vide

                    // Message d'erreur
                    $verifiedIfEmpty = "Erreur: Un champ n'a pas été renseigné";

                    // Redirection vers la page d'administration
                    header("Location: /admin?page=1&message=" . $verifiedIfEmpty);

                    // Empêche l'exécution du reste du script
                    die();
                }
            } else {
                // Si il manque une variable

                // Message d'erreur
                $messageIssetVariable = "Erreur: Manque une variable pour pouvoir modifier l'article";

                // Redirection vers la page d'administration
                header("Location: /admin?page=1&message=" . $messageIssetVariable);

                // Empêche l'exécution du reste du script
                die();
            }
        } else {
            // Si ce n'est pas un chiffre entier

            // Message d'erreur
            $messageNotPageNumber = "Erreur: Numéro de page incorrect";

            // Redirection vers la page d'administration
            header("Location: /admin?page=1&message=" . $messageNotPageNumber);

            // Empêche l'exécution du reste du script
            die();
        }
    }

    // Supprimer un article
    public function deletePost($token, $idPost)
    {
        // Vérifie que l'utilisateur est connecté et que c'est un administrateur
        $this->redirectIfNotLoggedOrNotAdmin();

        // Vérifie le jeton de l'utilisateur
        if ($token == $_SESSION['token']) {
            // Vérifie la présence de l'id
            if ($idPost != null) {
                // Crée une instance de Post
                $newPost = new Post;

                // Récupère le post
                $post = $newPost->getPost($idPost);

                // Si l'id correspond à un post
                if ($post != null) {
                    // Appelle la méthode qui supprime un post
                    $post->deletePostByHomeAdmin($post);

                    // Message de confirmation
                    $messagePostDeleteConfirmed = "Suppression de l'article et de ses commentaires";

                    // Redirection vers la page d'administration
                    header("Location: /admin?page=1&message=" . $messagePostDeleteConfirmed);

                    // Empêche l'exécution du reste du script
                    die();
                } else {
                    // Si l'id n'a aucune correspondance

                    // Message d'erreur
                    $messagePostDeleteFailed = "Erreur: Aucun article correspond à cet id";

                    // Récupère l'url précédente afin de revenir à la liste des articles
                    $url = $_SERVER['HTTP_REFERER'];

                    // Redirection vers la page de la liste des articles avec le message d'erreur
                    header("Location: " . $url . "&message=" . $messagePostDeleteFailed);

                    // Empêche l'exécution du reste du script
                    die();
                }
            } else {
                // Si l'id est vide
                $messageIdWithoutPost = "Erreur: Aucun id n'est renseigné";

                // Récupère l'url précédente afin de revenir à la liste des articles
                $url = $_SERVER['HTTP_REFERER'];

                // Redirection vers la page de la liste des articles avec le message d'erreur
                header("Location: " . $url . "&message=" . $messageIdWithoutPost);

                // Empêche l'exécution du reste du script
                die();
            }
        } else {
            // Si le jeton est différent
            $tokenfailed = "Erreur: jeton invalide";

            // Récupère l'url précédente afin de revenir à la liste des articles
            $url = $_SERVER['HTTP_REFERER'];

            // Redirection vers la page de la liste des articles avec le message d'erreur
            header("Location: " . $url . "&message=" . $tokenfailed);

            // Empêche l'exécution du reste du script
            die();
        }
    }
}
