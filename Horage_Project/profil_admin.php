<?php
    require_once 'session.php'; // On inclut le fichier session.php
    DemarrageSession(); // On démarre la session
    $user = $_SESSION['user']; // On récupère les informations de l'utilisateur depuis la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Horage</title>
    <link rel="stylesheet" href="CSS/profil.css"> <!-- On lie la feuille de style CSS pour le profil -->
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon"> <!-- On définit l'icône du site -->
    <script src="js/ThemeSwitcher.js" defer></script> <!-- On inclut le script pour changer de thème -->
    <script src="js/Profil_admin.js" defer></script> <!-- On inclut le script pour le profil admin -->
    <script src="js/navHighlighter.js" defer></script> <!-- On inclut le script pour surligner la navigation -->
</head>
<body>
<?php
    AfficherHeader(); // On affiche l'en-tête de la page
?>

    <main class="profile-container">
        <button class="sidebar-toggle">☰</button> <!-- Bouton pour afficher/masquer la barre latérale -->
        <div class="overlay"></div> <!-- Calque de superposition pour l'effet de la barre latérale -->
        <aside class="sidebar">
            <a href="profil_user.php" class="menu-btn active">Profil</a> <!-- Lien vers la page de profil -->
            <a href="page_admin.php" class="menu-btn">Gestion utilisateurs</a> <!-- Lien vers la page de gestion des utilisateurs -->
            <a href="logout.php" class="menu-btn logout">Se déconnecter</a> <!-- Lien pour se déconnecter -->
        </aside>

        <section class="profile-content">
            <h2>Mon Profil</h2> <!-- Titre de la section profil -->
            <div class="profile-info">
                <img src="img_horage/profil.jpg" alt="Photo de profil" class="profile-pic"> <!-- Image de profil -->
                <div class="info">
                    <!-- Affichage des informations de l'utilisateur -->
                    <p><strong>Nom :</strong> <span data-field="nom"><?php echo htmlspecialchars($user['nom']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Prenom :</strong> <span data-field="prenom"><?php echo htmlspecialchars($user['prenom']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Email :</strong> <span data-field="email"><?php echo htmlspecialchars($user['email']); ?></span> <button class="edit-btn">✏️</button></p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2> <!-- Pied de page avec copyright -->
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
    <script src="js/sidebar.js"></script> <!-- On inclut le script pour gérer la barre latérale -->
</body>
</html>
