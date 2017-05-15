$(document).ready(function() {
    var a = {
        valid: "fa fa-check-circle fa-lg text-success",
        invalid: "fa fa-times-circle fa-lg",
        validating: "fa fa-refresh"
    };
    $("#userForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_user[username]': {
                    validators: {
                        notEmpty: { message: "Le nom utilisateur est obligatoire." },
                        stringLength: { min: 5, max: 15, message: "Ce champ doit avoir au moins 5 caractères" },
                        regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_user[email]': {
                    validators: {
                        notEmpty: { message: "L'adresse mail est obligatoire." },
                        emailAddress: { message: "Cette adresse email n'est pas valide" }
                    }
                },
                'appbundle_user[password]': {
                    validators: {
                        notEmpty: { message: "Le mot de passe est obligatoire" },
                        different: { field: "appbundle_user[username]", message: "Le mot de passe ne doit pas être idenitique au nom utilisateur." }
                    }
                },
                dateNaiss: {
                    validators: {
                        notEmpty: { message: "La date de naissance doit être obligatoire" },
                        //stringLength: { min: 6, max: 30, message: "The username must be more than 6 and less than 30 characters long" },
                        regexp: { regexp: /^[Z0-9-\.]+$/, message: "La date de naissance doit être au format jj-mm-aaaa (ex: 22-02-1941)" }
                    }
                }
            }
        }),
        $("#regionForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_region[nom]': {
                    validators: {
                        notEmpty: { message: "Le nom de la région est obligatoire." },
                        //stringLength: { min: 5, max: 15, message: "Ce champ doit avoir au moins 5 caractères" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_region[code]': {
                    validators: {
                        notEmpty: { message: "Le code identificateur de la région est obligatoire." },
                        stringLength: { min: 2, max: 2, message: "Ce champ doit comporter 2 caractères" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Ce code doit être uniquement numérique" }
                    }
                }
            }
        })
});