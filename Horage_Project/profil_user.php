<?php
session_start();

// Vérifie si l'utilisateur est connecté et les informations sont dans la session
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $user = $_SESSION['user'];  // Récupère le tableau des informations utilisateur
} else {
    // Si l'utilisateur n'est pas connecté ou si les données de session sont manquantes
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Horage</title>
    <link rel="stylesheet" href="CSS/profil.css">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="header_1">
            <h1>Horage</h1>
            <img src="img_horage/logo-Photoroom.png" alt="logo de Horage" width="200px">
        </div>   

        <div class="nav">
            <ul>
                <li><a href="accueil.php" class="a1">Accueil</a></li>
                <li><a href="presentation.php" class="a1">Presentation</a></li>
                <li><a href="Reserve.php" class="a1">Nos offres</a></li>
                <li><a href="Recherche.php" class="a1">reserver</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="profil_user.php" class="a1">Profil</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="a1">Connexion</a></li>
                <?php endif; ?>
                <li><a href="accueil.php" class="a1">contacts</a></li>
            </ul>
        </div>
    </header>

    <main class="profile-container">
        <aside class="sidebar">
            <a href="profil_user.php" class="menu-btn active">Profil</a>
            <a href="profil_travel.php" class="menu-btn">Voyages prévus</a>
            <a href="#parametres" class="menu-btn">Paramètres</a>
            <a href="logout.php" class="menu-btn logout">Se déconnecter</a>
        </aside>

        <section class="profile-content">
            <h2>Mon Profil</h2>
            <div class="profile-info">
                <img src="img_horage/profil.jpg" alt="Photo de profil" class="profile-pic">
                <div class="info">
                    <p><strong>Nom :</strong> <span data-field="nom"><?php echo htmlspecialchars($user['nom']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Prenom :</strong> <span data-field="prenom"><?php echo htmlspecialchars($user['prenom']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Email :</strong> <span data-field="email"><?php echo htmlspecialchars($user['email']); ?></span> <button class="edit-btn">✏️</button></p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

    <script>
document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function () {
        let span = this.previousElementSibling;
        let field = span.parentElement.querySelector("strong").textContent.toLowerCase().replace(" :", ""); 

        let input = document.createElement("input");
        input.type = "text";
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
            input.replaceWith(span);
        } else {
            alert("Erreur : " + data);
        }
    })
    .catch(error => console.error("Erreur :", error));
}

    </script>
</body>
</html>
