<?php


namespace Controllers;


class PostController extends Controller
{
    // Supervise l'ajout et la modification des articles
    public function post()
    {
        // Si l'utilisateur est connecté
        if (!empty($_SESSION['user'])) {

            // Si l'utilisateur est un administrateur
            $this->isAdmin($_SESSION['user']);

            // Si c'est pour l'ajout d'un article
            if (!isset($_GET['id'])) {
                // Affiche la page d'ajout d'article avec les tags
                $this->render('postForm.html.twig', array());
            }
            // Si c'est pour la modification d'un article
            elseif (isset($_GET['id'])) {

            }
            else {
                // Si le parametre id n'a aucune correspondance
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
}