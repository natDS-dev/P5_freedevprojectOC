//On récupère les boutons édit - Get edit buttons
const editButtons = document.querySelectorAll(".edit_basket");
//on récupère les champs du formulaire - Get form fields
const formTitle = document.getElementById("title"),
    formOptions = document.querySelectorAll("select#category option"),
    formDescription = document.getElementById("description"),
    formAvailable = document.querySelectorAll("select#available option"),
    formId = document.getElementById("id"),
    formAlert = document.getElementById("formAlert"),
    formPicture = document.getElementById("picture"),
    formPictureDisplay = document.getElementById("pictureDisplay");

//Vérification données formulaire de modification paniers - Verify form datas when baskets are modified
document.querySelector("form").addEventListener("submit", e => {
    if (formTitle.value === "" || formTitle.value.length > 35) {
        e.preventDefault();
        formAlert.innerText = "Le titre est obligatoire et ne doit pas être supérieur à 35 caractères ";
    }
    if (formDescription.value === "" || formDescription.value.length > 255) {
        e.preventDefault();
        formAlert.innerText = "La description est obligatoire et ne doit pas être supérieur à 255 caractères ";
    }
});

//gestion du clic - Edit basket button
editButtons.forEach(button => {
    button.addEventListener("click", e => {
        e.preventDefault();
        //récupération des valeurs cellules du tableau - Get array cells values 
        const currentLine = e.target.parentElement.parentElement.parentElement;
        const id = currentLine.querySelector("td:nth-of-type(1)").innerText;
        const category = currentLine.querySelector("td:nth-of-type(2)").innerText;
        const title = currentLine.querySelector("td:nth-of-type(3)").innerText;
        const description = currentLine.querySelector("td:nth-of-type(4)").innerText;
        const available = currentLine.querySelector("td:nth-of-type(5)").innerText;
        const picture = currentLine.querySelector("td:nth-of-type(6)").innerText;

        //Edition panier : remplissage du formulaire avec les valeurs - Edit basket : Fill add form with right values
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
        formPictureDisplay.src = "./images/uploads/basketPictures/" + picture;

        //affichage du formulaire au clic - Edit basket button =>Display form 
        document.querySelector("form").classList.remove("hidden_form");
    })
});

//Confirmer avant effacement panier - Confirm before deleting a basket
document.querySelectorAll(".delete_basket").forEach(del => {
    del.addEventListener("click", e => {
        if (!confirm("Es-tu sûr(e) de vouloir supprimer ce panier ?")) {
            e.preventDefault();
        }
    });
});