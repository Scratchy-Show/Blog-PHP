<?php
//  Permet d'accéder à SchemaTool, un composant capable
// de générer un schéma de base de données relationnelle entièrement basé sur
// les classes d'entités définies et leurs métadonnées
// par l'interface de la ligne de commande.

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use System\Database;

// Charge automatiquement composer grâce à router.php
require __DIR__ . '/../system/router.php';

// Récupère EntityManager dans l'application
$entityManager = Database::getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);
