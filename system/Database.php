<?php


namespace System;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Database
{
    private static $entityManager = null;

    // Permet l'accès à la fonctionnalité ORM de Doctrine
    public static function getEntityManager() {
        // Si $entityManager n'est pas instancié
        if (self::$entityManager === null) {
            // Chemin vers les fichiers d'entité
            $paths = array(__DIR__ . "/../models");

            $isDevMode = true;

            // Méthode Setup pour le mappage par annotation
            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);

            $cache = new ArrayCache; // Dev uniquement - Met uniquement en cache les données sur une base individuelle
            // Cache de métadonnées - Evite un gaspillage de ressources
            $config->setMetadataCacheImpl($cache);
            // Cache de requêtes - C'est un cache d'optimisation
            $config->setQueryCacheImpl($cache);

            // Génération automatique de classes proxy
            $config->setAutoGenerateProxyClasses(true); // Modifier la valeur à false en production

            // Paramètres de configuration de la base de données
            $dbParams = array(
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'my_website',
            );

            // Gestion des erreurs
            try {
                // Méthode EntityManager qui crée une instance d'EntityManager
                self::$entityManager = EntityManager::create($dbParams, $config);
            }
            catch (ORMException $e)
            {
                return null;
            }
        }
        return self::$entityManager;
    }
}