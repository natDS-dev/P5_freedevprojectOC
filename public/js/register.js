const registerForm = document.getElementById("register_form");

const inputCompany = document.getElementById("input_company");

const btnIndividual = document.getElementById("btn_individual");

const btnProfessional = document.getElementById("btn_professional");

const roleUser = document.getElementById("role_user");

function showIndividualForm() {
    registerForm.classList.remove("hidden_form");
    inputCompany.disabled = true;
    roleUser.value = "1";
}

function showProfessionalForm() {
    registerForm.classList.remove("hidden_form");
    inputCompany.disabled = false;
    roleUser.value = "2";
}

if (roleUser.value === "1") {
    showIndividualForm();
} else if (roleUser.value === "2") {
    showProfessionalForm();
}

btnIndividual.addEventListener("click", e => {
    e.preventDefault();
    showIndividualForm();
});

btnProfessional.addEventListener("click", e => {
    e.preventDefault();
    showProfessionalForm();
});
