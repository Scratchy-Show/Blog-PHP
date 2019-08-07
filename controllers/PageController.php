<?php


namespace Controllers;

use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;


class PageController extends Controller // Hérite de la class Controller
{
    // Affiche la page d'Accueil
    public function index()
    {
        echo $this->twig->render('index.html.twig');
    }
}