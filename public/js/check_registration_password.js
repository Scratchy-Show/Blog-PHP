// Vérifie la correspondance des mots de passe du formulaire d'inscription

password1 = document.getElementById("password");

if (password1 !== null) {
    const password = {
         password1: document.getElementById("password"),
         password2: document.getElementById("confirm"),

        checkRegistrationPassword: function() {
            if (this.password1.value !== this.password2.value)
            {
                this.password1.style.border = "2px solid rgb(216,14,14)";
                this.password2.style.border = "2px solid rgb(216,14,14)";
            }
            else
            {
                this.password1.style.border = "2px solid rgb(34,139,34)";
                this.password2.style.border = "2px solid rgb(34,139,34)";
            }
        }
    };

    document.getElementById("password").addEventListener("keyup", function() {
        password.checkRegistrationPassword();
    });

    document.getElementById("confirm").addEventListener("keyup", function() {
        password.checkRegistrationPassword();

    });
}