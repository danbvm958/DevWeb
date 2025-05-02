<?php
session_start();

// Récupérer l'email depuis l'URL si on vient de l'admin
$userEmail = $_GET['email'] ?? null;

if ($userEmail) {
    // Mode admin - charger les données depuis le JSON
    $jsonFile = 'data/utilisateur.json';
    $jsonData = file_get_contents($jsonFile);
    $users = json_decode($jsonData, true);
    
    foreach ($users as $user) {
        if ($user['email'] === $userEmail) {
            $displayUser = $user;
            break;
        }
    }
    
    if (!isset($displayUser)) {
        header("Location: admin.php");
        exit();
    }
} 
// Mode normal (utilisateur connecté)
elseif (isset($_SESSION['user'])) {
    $displayUser = $_SESSION['user'];
} 
else {
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
    <script src="js/themeSwitcher.js" defer></script>
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
        <section class="profile-content">
            <h2>Profil <?= $userEmail ? 'de '.htmlspecialchars($displayUser['prenom']) : 'Mon Profil' ?></h2>
            
            <div class="profile-info">
                <img src="img_horage/profil.jpg" alt="Photo de profil" class="profile-pic">
                <div class="info">
                    <p><strong>Nom :</strong> <?= htmlspecialchars($displayUser['nom']) ?></p>
                    <p><strong>Prénom :</strong> <?= htmlspecialchars($displayUser['prenom']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($displayUser['email']) ?></p>
                </div>
            </div>
            
            <!-- Section voyages -->
            <?php if (!empty($displayUser['voyages'])): ?>
            <h3>Historique des voyages</h3>
            <div class="travel-history">
                <?php foreach ($displayUser['voyages'] as $voyage): ?>
                    <div class="voyage">
                        <h4><?= htmlspecialchars($voyage['voyage_titre']) ?></h4>
                        <p>Date: <?= htmlspecialchars($voyage['date_achat']) ?></p>
                        <p>Prix: <?= htmlspecialchars($voyage['montant']) ?> €</p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
</body>
</html>