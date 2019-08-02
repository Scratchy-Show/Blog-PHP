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
        try {
            echo $this->twig->render('index.html.twig');
        }
        catch (Twig_Error_Loader $e)
        {
            echo 'Erreur : ' . $e->getMessage();
        }
        catch (Twig_Error_Runtime $e)
        {
            echo 'Erreur : ' . $e->getMessage();
        }
        catch (Twig_Error_Syntax $e)
        {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}