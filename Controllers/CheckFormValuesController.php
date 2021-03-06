<?php


namespace Controllers;

use Models\User;

class CheckFormValuesController
{
    // Formulaire d'inscription - Vérifie si l'adresse mail et le pseudo sont unique
    public function checkSingleUsernameEmail($email, $username)
    {
        // Vérifie la présence du mail ou du pseudo dans la BDD
        $resultSingleEmail = User::getUserByEmail($email);
        $resultSingleUsername = User::getUserByUsername($username);

        // Si l'un ou l'autre éxiste déjà dans la BDD
        if (($resultSingleEmail != null) || ($resultSingleUsername != null)) {
            // Si les deux éxistent déjà dans la BDD
            if (($resultSingleEmail != null) && ($resultSingleUsername != null)) {
                // Message d'erreur
                $messageSingleEmail = "Cette adresse mail existe déjà";

                // Message d'erreur
                $messageSingleUsername = "Ce pseudo est déjà utilisé";

                return array($messageSingleEmail, $messageSingleUsername);
            } elseif ($resultSingleEmail != null) {
                // Si uniquement l'adresse mail éxiste déjà dans la BDD

                // Message d'erreur
                $messageSingleEmail = "Cette adresse mail existe déjà";

                return array($messageSingleEmail, null);
            } else {
                // Si uniquement le pseudo éxiste déjà dans la BDD

                // Message d'erreur
                $messageSingleUsername = "Ce pseudo est déjà utilisé";

                return array(null, $messageSingleUsername);
            }
        }
        return null;
    }

    // Formulaire d'inscription - Vérifie le nom et prénom correspondent à l'expression régulières
    public function checkName($lastName, $firstName)
    {
        // Minimum 2 charactères - Maximum 25
        // Commence par une lettre (minuscule, majuscule, accentué)
        // Aucun chiffre
        // Accepte: minuscule, majuscule, lettre accentuée, tiret et apostrophe
        $regex = "#^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-']{1,25}$#";

        // Vérifie que le nom correspond à l'expression régulières
        $checkLastName = preg_match($regex, $lastName);

        // Vérifie que le prénom correspond à l'expression régulières
        $checkFirstName = preg_match($regex, $firstName);

        // Si l'un ou l'autre ne correspondent pas à la regex
        if (($checkLastName == 0) || ($checkFirstName == 0)) {
            // Si aucun des deux ne correspondent à la regex
            if (($checkLastName == 0) && ($checkFirstName == 0)) {
                // Message d'erreur
                $messageLastName = "Le nom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

                // Message d'erreur
                $messageFirstName = "Le prénom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

                return array($messageLastName, $messageFirstName);
            } elseif ($checkLastName == 0) {
                // Si le nom ne correspond pas à la regex

                // Message d'erreur
                $messageLastName = "Le nom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

                return array($messageLastName, null);
            } else {
                // Si le prénom ne correspond pas à la regex

                // Message d'erreur
                $messageFirstName = "Le prénom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

                return array(null, $messageFirstName);
            }
        }
        return 1;
    }

    // Formulaire d'inscription - Vérifie que le mail correspond à l'expression régulières
    public function checkEmail($email)
    {
        // Valide l'adresse selon la syntaxe défini par la RFC 822
        $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        // Vérifie le format de l'adresse mail
        $checkEmail = preg_match("#^([a-z0-9_.-]+@[\da-z.-]+\.[a-z.]{2,6})$#", $filterEmail);

        // Si le mail ne correspond pas au format
        if ($checkEmail == 0) {
            // Message d'erreur
            $messageEmail = "Le format du mail attendue est nom@exemple.fr";

            return $messageEmail;

        }
        return $checkEmail;
    }

    // Formulaire d'inscription - Vérifie les deux mot de passe
    public function checkPassword($password, $confirmPassword)
    {
        // Si les deux mot de passe ne sont pas vident
        if ((!empty($password)) && (!empty($confirmPassword))) {
            // Vérifie que $password est identique à $confirmPassword
            $checkPassword = $password == $confirmPassword;

            // Si les deux mots de passe ne corresponent pas
            if ($checkPassword == false) {
                // Message d'erreur
                $messagePassword = "Les deux mots de passe ne correspondent pas";

                return $messagePassword;
            }
            $checkPassword = 1;
            return $checkPassword;
        } else {
            // Si le champ n'a pas été renseigné

            // Message d'erreur
            $messagePassword = "Le mot de passe n'a pas été renseigné";

            return $messagePassword;

        }
    }

    // Formulaire d'ajout d'article - Nettoie le titre d'un article pour en faire sa route
    public function cleanTitle($title)
    {
        // Remplace les lettres accentuées
        $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,
        Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");

        $replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,
        A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");

        $cleanTitle = str_replace($search, $replace, $title);

        // Convertie toutes les majuscules en minuscules
        $lowercaseTitle = strtolower($cleanTitle);

        // Remplace les espaces par des underscores
        $withoutSpaces = str_replace(' ', '_', $lowercaseTitle);

        return $withoutSpaces;
    }
}
