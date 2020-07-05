//On récupère les boutons édit
const editButtons = document.querySelectorAll(".edit_add");
//on récupère les champs du formulaire 
const formTitle = document.getElementById("title"),
    formDescription = document.getElementById("description"),
    formBasketSize = document.getElementById("basket_size"),
    formBasketQuantity = document.getElementById("basket_quantity"),
    formId = document.getElementById("id");

//gestion du clic
editButtons.forEach(button => {
    button.addEventListener("click", e => {
        e.preventDefault();
        //récupération des valeurs cellules du tableau
        const currentLine = e.target.parentElement.parentElement.parentElement;
        const id = currentLine.querySelector("td:nth-of-type(1)").innerText;
        const title = currentLine.querySelector("td:nth-of-type(2)").innerText;
        const description = currentLine.querySelector("td:nth-of-type(3)").innerText;
        const basketSize = currentLine.querySelector("td:nth-of-type(4)").innerText;
        const basketQuantity = currentLine.querySelector("td:nth-of-type(5)").innerText;
        //remplissage du formulaire avec les valeurs
        formId.value = id;
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