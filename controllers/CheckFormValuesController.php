<?php


namespace Controllers;


use Models\User;

class CheckFormValuesController
{
    // Formulaire d'inscription - Vérifie si l'adresse mail et le pseudo sont unique
    public function checkSingleUsernameEmail($email, $username)
    {
        $resultSingleEmail = User::getUserByEmail($email);
        $resultSingleUsername = User::getUserByUsername($username);

        if (($resultSingleEmail != null) || ($resultSingleUsername != null)) {

            if (($resultSingleEmail != null) && ($resultSingleUsername != null)) {
                $messageSingleEmail = "Cette adresse mail existe déjà";
                $messageSingleUsername = "Ce pseudo est déjà utilisé";
                return array($messageSingleEmail, $messageSingleUsername);

            } elseif ($resultSingleEmail != null) {
                $messageSingleEmail = "Cette adresse mail existe déjà";
                return array($messageSingleEmail, null);

            } else {
                $messageSingleUsername = "Ce pseudo est déjà utilisé";
                return array(null, $messageSingleUsername);
            }
        }
        return null;
    }

    // Formulaire d'inscription - Vérifie le nom et prénom correspondent à l'expression régulières
    public function checkName($lastName, $firstName)
    {
        // Vérifie que le nom et le prénom correspondent à l'expression régulières
        // Minimum 2 charactères - Maximum 25
        // Commence par une lettre (minuscule, majuscule, accentué)
        // Aucun chiffre
        // Accepte: minuscule, majuscule, lettre accentuée, tiret et apostrophe
        $regex = "#^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-']{1,25}$#";
        $checkLastName = preg_match($regex, $lastName);
        $checkFirstName = preg_match($regex, $firstName);

        if (($checkLastName == 0) || ($checkFirstName == 0)) {

            if (($checkLastName == 0) && ($checkFirstName == 0)) {
                $messageLastName = "Le nom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";
                $messageFirstName = "Le prénom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";
                return array($messageLastName, $messageFirstName);

            } elseif ($checkLastName == 0) {
                $messageLastName = "Le nom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";
                return array($messageLastName, null);

            } else {
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
        $checkEmail = preg_match("#^([a-z0-9_\.-]+\@[\da-z\.-]+\.[a-z\.]{2,6})$#", $filterEmail);

        if ($checkEmail == 0) {
            $messageEmail = "Le format du mail attendue est nom@exemple.fr";
            return $messageEmail;
        }
        return $checkEmail;
    }

    // Formulaire d'inscription - Vérifie que le pseudo ne soit pas vide
    public function checkUsername($username)
    {
        if (empty($username)) {
            $messageUsername = "Le pseudo n'a pas été renseigné";
            return $messageUsername;
        }
        $username = 1;
        return $username;
    }

    // Formulaire d'inscription - Vérifie les deux mot de passe
    public function checkPassword($password, $confirmPassword)
    {
        // Si les deux mot de passe ne sont pas vident
        if ((!empty($password)) && (!empty($confirmPassword))) {
            // Vérifie que $password est identique à $confirmPassword
            $checkPassword = $password == $confirmPassword;

            if ($checkPassword == false) {
                $messagePassword = "Les deux mots de passe ne correspondent pas";
                return $messagePassword;
            }
            $checkPassword = 1;
            return $checkPassword;
        }
        else {
            $messagePassword = "Le mot de passe n'a pas été renseigné";
            return $messagePassword;
        }
    }

    // Formulaire d'ajout d'article - Nettoie le titre d'un article pour en faire sa route
    public function cleanTitle($title) {
        // Remplace les lettres accentuées
        $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
        $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");
        $cleanTitle = str_replace($search, $replace, $title);

        // Convertie toutes les majuscules en minuscules
        $lowercaseTitle = strtolower($cleanTitle);

        // Remplace les espaces par des underscores
        $withoutSpaces = str_replace(' ', '_', $lowercaseTitle);

        return $withoutSpaces;
    }

    // Formulaire d'ajout d'article, d'ajout de commentaire et de modification d'article
    // Vérifie que les variables ne soient pas vide
    public function checkIfEmpty($argument)
    {
        // Si l'une des variable est vide
        if (empty($argument)) {
            $messageIsEmpty = "Erreur: Tous les champs n'ont pas été renseigné";
            return $messageIsEmpty;
        }
        $isNotEmpty = 1;
        return $isNotEmpty;
    }
}