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
        }),
        $("#gestionnaireForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_gestionnaire[user]': {
                    validators: {
                        notEmpty: { message: "Veuillez selectionner l'utilisateur." },
                        //stringLength: { min: 5, max: 15, message: "Ce champ doit avoir au moins 5 caractères" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_gestionnaire[region]': {
                    validators: {
                        notEmpty: { message: "Veuillez selectionner la région." },
                        //stringLength: { min: 5, max: 15, message: "Ce champ doit avoir au moins 5 caractères" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_gestionnaire[fonction]': {
                    validators: {
                        notEmpty: { message: "La fonction est obligatoire." },
                        //different: { value: "chef", message: "La fonction doit être différente de chef" }
                    }
                },
                'appbundle_gestionnaire[nom]': {
                    validators: {
                        notEmpty: { message: "Le nom de famille est obligatoire." }
                    }
                },
                'appbundle_gestionnaire[prenoms]': {
                    validators: {
                        notEmpty: { message: "Le prenom est obligatoire." }
                    }
                },
                'appbundle_gestionnaire[contact]': {
                    validators: {
                        notEmpty: { message: "Le contact téléphonique est obligatoire." },
                        stringLength: { min: 8, max: 8, message: "Ce numéro de téléphone doit comporter 8 chiffres" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Ce champ doit être uniquement numérique" }
                    }
                }
            }
        }),
        $("#districtForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_district[nom]': {
                    validators: {
                        notEmpty: { message: "Le nom du district est obligatoire." },
                        stringLength: { min: 2, max: 75, message: "Veuillez donner le nom sans la mention 'DISTRICT'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                }
            }
        }),
        $("#groupeForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_groupe[paroisse]': {
                    validators: {
                        notEmpty: { message: "Le nom de la paroisse est obligatoire." },
                        stringLength: { min: 6, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_groupe[localite]': {
                    validators: {
                        notEmpty: { message: "Le nom de la localité est obligatoire." },
                        //stringLength: { min: 2, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                }
            }
        }),
        $("#scoutForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_scout[groupe]': {
                    validators: {
                        notEmpty: { message: "Le groupe du scout est obligatoire." },
                        //stringLength: { min: 6, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_scout[branche]': {
                    validators: {
                        notEmpty: { message: "L'unité du scout est obligatoire." },
                        //stringLength: { min: 2, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_scout[statut]': {
                    validators: {
                        notEmpty: { message: "Le statut du scout est obligatoire." },
                        //stringLength: { min: 2, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_scout[nom]': {
                    validators: {
                        notEmpty: { message: "Le nom de famille du scout est obligatoire." },
                        //stringLength: { min: 2, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_scout[prenoms]': {
                    validators: {
                        notEmpty: { message: "Le(s) prenom(s) du scout est(sont) obligatoire(s)." },
                        //stringLength: { min: 2, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_scout[datenaiss]': {
                    validators: {
                        notEmpty: { message: "La date de naissance  du scout est obligatoire." },
                        stringLength: { min: 10, max: 10, message: "La date de naissance doit être au format jj-mm-aaaa (ex: 22-02-1941)" },
                        regexp: { regexp: /^[Z0-9-\.]+$/, message: "La date de naissance doit être au format jj-mm-aaaa (ex: 22-02-1941)" }
                    }
                },
                'appbundle_scout[lieunaiss]': {
                    validators: {
                        notEmpty: { message: "Le lieu de naissance du scout est obligatoire." },
                        //stringLength: { min: 2, max: 75, message: "Veuillez donner le nom de la paroisse sans la mention 'PAROISSE'" },
                        //regexp: { regexp: /^[a-zA-Z0-9_\.]+$/, message: "Le nom utilisateur doit être uniquement alphanumerique. il ne doit pas comportement de tiret ni d'espace" }
                    }
                },
                'appbundle_scout[contact]': {
                    validators: {
                        //notEmpty: { message: "Le contact téléphonique est obligatoire." },
                        stringLength: { min: 8, max: 8, message: "Ce numéro de téléphone doit comporter 8 chiffres" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Ce champ doit être uniquement numérique" }
                    }
                },
                'appbundle_scout[contactparent]': {
                    validators: {
                        notEmpty: { message: "Le telephone du parent est obligatoire." },
                        stringLength: { min: 8, max: 8, message: "Ce numéro de téléphone doit comporter 8 chiffres" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Ce champ doit être uniquement numérique" }
                    }
                },
                'appbundle_scout[email]': {
                    validators: {
                        emailAddress: { message: "L'adresse entrée n'est pas une adresse email valide" }
                    }
                }
            }
        }),
        $("#cotisationForm").bootstrapValidator({
            framework: "bootstrap",
            icon: {
                valid: "glyphicon glyphicon-ok",
                invalid: "glyphicon glyphicon-remove",
                validating: "glyphicon glyphicon-refresh"
            },
            fields: {
                'appbundle_cotisation[annee]': {
                    validators: {
                        notEmpty: { message: "L'année scoute est obligatoire." },
                        stringLength: { min: 9, max: 9, message: "exemple: 2016-2017" },
                        regexp: { regexp: /^[Z0-9-\.]+$/, message: "L'année doit être au format 2016-2017" }
                    }
                },
                'appbundle_cotisation[cadre]': {
                    validators: {
                        notEmpty: { message: "Le montant de cotisation des cadres est obligatoire." },
                        stringLength: { min: 4, max: 5, message: "Veuillez entrer sans espace ni separateur de millier" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Le montant doit être uniquement numérique, sans espace ni point" }
                    }
                },
                'appbundle_cotisation[district]': {
                    validators: {
                        notEmpty: { message: "Le montant de cotisation des CD et de leurs equipes est obligatoire." },
                        stringLength: { min: 4, max: 5, message: "Veuillez entrer sans espace ni separateur de millier" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Le montant doit être uniquement numérique, sans espace ni point" }
                    }
                },
                'appbundle_cotisation[groupe]': {
                    validators: {
                        notEmpty: { message: "Le montant de cotisation des chefs d'unités est obligatoire." },
                        stringLength: { min: 4, max: 5, message: "Veuillez entrer sans espace ni separateur de millier" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Le montant doit être uniquement numérique, sans espace ni point" }
                    }
                },
                'appbundle_cotisation[jeune]': {
                    validators: {
                        notEmpty: { message: "Le montant de cotisation des jeunes est obligatoire." },
                        stringLength: { min: 4, max: 5, message: "Veuillez entrer sans espace ni separateur de millier" },
                        regexp: { regexp: /^[Z0-9\.]+$/, message: "Le montant doit être uniquement numérique, sans espace ni point" }
                    }
                },
                fonction: {
                    validators: {
                        notEmpty: { message: "La fonction est obligatoire est obligatoire." },
                        //stringLength: { min: 4, max: 5, message: "Veuillez entrer sans espace ni separateur de millier" },
                        //regexp: { regexp: /^[Z0-9\.]+$/, message: "Le montant doit être uniquement numérique, sans espace ni point" }
                    }
                },
                branche: {
                    validators: {
                        notEmpty: { message: "La branche du chef d'unité est obligatoire est obligatoire." },
                        //stringLength: { min: 4, max: 5, message: "Veuillez entrer sans espace ni separateur de millier" },
                        //regexp: { regexp: /^[Z0-9\.]+$/, message: "Le montant doit être uniquement numérique, sans espace ni point" }
                    }
                }
            }
        })
});
