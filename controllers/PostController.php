<?php


namespace Controllers;


use Models\Post;

class PostController extends Controller
{
    // Affiche soit le formulaire d'ajout, soit le formualire de modification d'un article
    public function post()
    {
        // Si l'utilisateur est connecté
        if (!empty($_SESSION['user'])) {

            // Vérifie que l'utilisateur est un administrateur
            $this->isAdmin($_SESSION['user']);

            // Si il n'y a pas la présence d'un id, c'est pour l'ajout d'un article
            if (!isset($_GET['id'])) {
                // Affiche la page d'ajout d'article
                $this->render('postForm.html.twig', array());
            }
            // Si il y'a la présence d'un id, c'est pour la modification d'un article
            elseif (isset($_GET['id'])) {

            }
            else {
                // Si l'id n'a aucune correspondance
                $message = "Aucun article ne correspond à l'id " . $_GET['id'];
                // Redirection vers la page d'administration
                $this->render('homeAdmin.html.twig', array("message" => $message));
            }
        }
        // Si l'utilisateur n'est pas connecté
        else {
            // Redirection vers la page 404
            $this->redirectIfNotAdmin();
        }
    }

    // Ajoute un article dans la bdd
    public function addPost()
    {
        // Si l'utilisateur est connecté
        if (!empty($_SESSION['user'])) {

            // Vérifie que l'utilisateur est un administrateur
            $this->isAdmin($_SESSION['user']);

            // Récupère les variables
            $title = $_POST['title-post'];
            $summary = $_POST['summary'];
            $content = $_POST['content'];

            // Vérifie que les valeurs des variables ne soient pas vide
            $verifiedIfEmpty = $this->checkIfEmpty($title, $summary, $content);

            // Si toutes les variables sont renseignées
            if ($verifiedIfEmpty == 1) {

                // Crée une instance de Post
                $post = new Post;
                // Appelle la fonction qui enregistre un post avec les paramètres du formulaire
                $messagePostAddConfirmed= $post->addPostByForm($title, $summary, $content);

                // Affiche le formulaire d'ajout d'article avec le message de confirmation
                $this->render('postForm.html.twig', array("messagePostAddConfirmed" => $messagePostAddConfirmed));
            }
            else {
                // Affiche le formulaire d'ajout d'article avec le message d'erreur
                $this->render('postForm.html.twig', array("messageIfEmpty" => $verifiedIfEmpty));
            }

        }
         // Si l'utilisateur n'est pas connecté
        else {
            // Redirection vers la page 404
            $this->redirectIfNotAdmin();
        }
    }
}