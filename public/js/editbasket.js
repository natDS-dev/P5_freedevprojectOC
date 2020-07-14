//On récupère les boutons édit
const editButtons = document.querySelectorAll(".edit_basket");
//on récupère les champs du formulaire 
const formTitle = document.getElementById("title"),
    formOptions = document.querySelectorAll("select#category option"),
    formDescription = document.getElementById("description"),
    formAvailable = document.querySelectorAll("select#available option"),
    formId = document.getElementById("id");

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
        const available = currentLine.querySelector("td:nth-of-type(5)").innerText;
        //remplissage du formulaire avec les valeurs
        formId.value = id;
        formOptions.forEach(option => {
            if (option.innerText === category) {
                option.selected = true;
            }
        });
        formAvailable.forEach(option => {
            if (option.innerText === available) {
                option.selected = true;
            }
        });
        formTitle.value = title;
        formDescription.value = description;
        //affichage du formulaire au clic
        document.querySelector("form").classList.remove("hidden_form");

    })
});

//Confirmer avant effacement panier
document.querySelectorAll(".delete_basket").forEach(del => {
    del.addEventListener("click", e => {
        if (!confirm("Es-tu sûr(e) de vouloir supprimer ce panier ?")) {
            e.preventDefault();
        }
    });
});