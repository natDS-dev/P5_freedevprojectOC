//On récupère les boutons édit
const editButtons = document.querySelectorAll(".edit_add");
//on récupère les champs du formulaire 
const formTitle = document.getElementById("title"),
    formOptions = document.querySelectorAll("select#category option"),
    formDescription = document.getElementById("description"),
    formBasketSize = document.getElementById("basket_size"),
    formBasketQuantity = document.getElementById("basket_quantity"),
    formId = document.getElementById("id"),
    formAlert = document.getElementById("formAlert");


// Vérification données formulaire de modification annonces - Verify form datas when adds are modified
document.querySelector("form").addEventListener("submit", e => {
    if (formTitle.value === "" || formTitle.value.length > 35) {
        e.preventDefault();
        formAlert.innerText = "Le titre est obligatoire et ne doit pas être supérieur à 35 caractères ";
    }
    if (formDescription.value === "" || formDescription.value.length > 255) {
        e.preventDefault();
        formAlert.innerText = "La description est obligatoire et ne doit pas être supérieur à 255 caractères ";
    }

    if (formBasketSize.value === "") {
        e.preventDefault();
        formAlert.innerText = "La taille de panier est obligatoire";
    }

    if (formBasketQuantity.value === "" || formBasketQuantity.value < 1) {
        e.preventDefault();
        formAlert.innerText = "Le nombre de panier est obligatoire";
    }

});
//gestion du clic
editButtons.forEach(button => {
    button.addEventListener("click", e => {
        e.preventDefault();
        //récupération des valeurs cellules du tableau
        const currentLine = e.target.parentElement.parentElement.parentElement;
        const id = currentLine.querySelector("td:nth-of-type(1)").innerText;
        const category = currentLine.querySelector("td:nth-of-type(2)").innerText;
        const title = currentLine.querySelector("td:nth-of-type(3)").innerText;
        const description = currentLine.querySelector("td:nth-of-type(4)").innerText;
        const basketSize = currentLine.querySelector("td:nth-of-type(5)").innerText;
        const basketQuantity = currentLine.querySelector("td:nth-of-type(6)").innerText;
        //remplissage du formulaire avec les valeurs
        formId.value = id;
        formOptions.forEach(option => {
            if (option.innerText === category) {
                option.selected = true;
            }
        });
        formTitle.value = title;
        formDescription.value = description;
        formBasketQuantity.value = basketQuantity;
        formBasketSize.value = basketSize;
        let index;
        switch (basketSize) {
            case "S":
                index = 0;
                break;
            case "M":
                index = 1;
                break;
            case "L":
                index = 2;
                break;
            default:
                index = 0;
        }
        formBasketSize.querySelectorAll("option")[index].selected = true;
        //affichage du formulaire au clic
        document.querySelector("form").classList.remove("hidden_form");

    })
});

//Confirmer avant effacement annonce
document.querySelectorAll(".delete_add").forEach(del => {
    del.addEventListener("click", e => {
        if (!confirm("Es-tu sûr(e) de vouloir supprimer l'annonce ?")) {
            e.preventDefault();
        }
    });
});
