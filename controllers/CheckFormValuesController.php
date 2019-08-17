<?php


namespace Controllers;


class CheckFormValuesController
{
    // Vérifie la valeur du nom transmis par l'utilisateur
    public function checkLastName($lastName)
    {
        // Vérifie que le nom correspond à l'expression régulières
        // Minimum 2 charactères - Maximum 25
        // Commence par une lettre (minuscule, majuscule, accentué)
        // Aucun chiffre
        // Accepte: minuscule, majuscule, lettre accentuée, tiret et apostrophe
        $checkLastName = preg_match("#^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-\']{1,25}$#", $lastName);

        if ($checkLastName == 0) {
            $messageLastName = "Le nom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

            return $messageLastName;
        }
        return $checkLastName;
    }

    // Vérifie la valeur du prénom transmis par l'utilisateur
    public function checkFirstName($firstName)
    {
        // Vérifie que le nom correspond à l'expression régulières
        // Minimum 2 charactères - Maximum 25
        // Commence par une lettre (minuscule, majuscule, accentué)
        // Aucun chiffre
        // Accepte: minuscule, majuscule, lettre accentuée, tiret et apostrophe
        $checkFirstName = preg_match("#^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ\-\']{1,25}$#", $firstName);

        if ($checkFirstName == 0) {
            $messageFirstName = "Le prénom ne doit comporter que des lettres et contenir entre 2 et 25 caractères";

            return $messageFirstName;
        }
        return $checkFirstName;
    }

    // Vérifie que le mail correspond à l'expression régulières
    public function checkEmail($email)
    {
        // Vérifie le format de l'adresse mail
        $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        $checkEmail = preg_match("#^([a-z0-9_\.-]+\@[\da-z\.-]+\.[a-z\.]{2,6})$#", $filterEmail);

        if ($checkEmail == 0) {
            $messageEmail = "Le format du mail attendue est nom@exemple.fr";

            return $messageEmail;
        }
        return $checkEmail;
    }

    // Vérifie que le mail correspond à l'expression régulières
    public function checkPassword($password, $confirmPassword)
    {
        // Vérifie la correspondance des mots de passe
        $checkPassword = $password == $confirmPassword;

        if ($checkPassword == false) {
            $messagePassword = "Les deux mots de passe ne correspondent pas";

            return $messagePassword;
        }
        $checkPassword = 1;

        return $checkPassword;
    }
}