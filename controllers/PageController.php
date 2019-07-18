<?php


namespace Controllers\PageController;


class PageController
{
    public function index()
    {
        echo $twig->render('index.html.twig' );
    }
}