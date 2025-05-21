document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function () {
        let span = this.previousElementSibling;
        let field = span.dataset.field; // Utilisation directe de data-field

        let input = document.createElement("input");
        input.type = field === 'email' ? 'email' : 'text';
        input.value = span.textContent;
        input.dataset.field = field;

        span.replaceWith(input);
        input.focus();

        input.addEventListener("blur", function () {
            updateUser(input);
        });

        input.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                updateUser(input);
            }
        });
    });
});

function updateUser(input) {
    let field = input.dataset.field;
    let newValue = input.value.trim();

    if (newValue === "") {
        alert("Le champ ne peut pas être vide !");
        return;
    }

    let formData = new FormData();
    formData.append("field", field);
    formData.append("value", newValue);

    fetch("modif_user.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Réponse serveur:", data);

        if (data.includes("réussie")) {
            let span = document.createElement("span");
            span.textContent = newValue;
            span.dataset.field = field;
            input.replaceWith(span);
        } else {
            alert("Erreur : " + data);
        }
    })
    .catch(error => console.error("Erreur :", error));
}
