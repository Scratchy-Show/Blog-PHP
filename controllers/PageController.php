<?php


namespace Controllers;


class PageController extends Controller // Hérite de la class Controller
{
    // Affiche la page d'Accueil
    public function index()
    {
        $this->render('index.html.twig', array());
    }
}