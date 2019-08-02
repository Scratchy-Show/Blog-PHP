<?php

function loadClass ($class) {
    // Remplace le \ par / du paramètre $class
    $class = str_replace("\\", "/", $class);
    // S'assure que le fichier n'est inclus qu'une seule fois
    // Retourne le dossier du fichier inclus
    // Construit le chemin du fichier de classe et ajoute l'extension .php
    include_once __DIR__ . '/../' . $class . '.php';
}

// Enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.
spl_autoload_register('loadClass');