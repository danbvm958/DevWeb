<?php 
session_start();

// Charger les utilisateurs depuis le fichier JSON
$jsonData = file_get_contents('data/utilisateur.json');
if (!$jsonData) {
    die("Erreur : Impossible de lire le fichier utilisateur.json");
}

$users = json_decode($jsonData, true);
if (!is_array($users)) {
    die("Erreur : Le fichier utilisateur.json ne contient pas un tableau valide");
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];

    $userFound = false;
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $_SESSION['user']['voyages'] = isset($user['voyages']) ? $user['voyages'] : [];
            $userFound = true;
            break;
        }
    }

    if (!$userFound) {
        die("Erreur : Utilisateur non trouvé dans utilisateur.json");
    }

    $voyages = $_SESSION['user']['voyages'];
} else {
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Voyages - Horage</title>
    <link rel="stylesheet" href="CSS/profil.css">
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
            <li><a href="presentation.php" class="a1">Présentation</a></li>
            <li><a href="Reserve.php" class="a1">Nos offres</a></li>
            <li><a href="Recherche.php" class="a1">Réserver</a></li>

            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="profil_user.php" class="a1">Profil</a></li>
            <?php else: ?>
                <li><a href="login.php" class="a1">Connexion</a></li>
            <?php endif; ?>

            <li><a href="contact.php" class="a1">Contacts</a></li>
        </ul>
    </div>
</header>

<main class="profile-container">
    <aside class="sidebar">
        <a href="profil_user.php" class="menu-btn">Profil</a>
        <a href="profil_travel.php" class="menu-btn active">Voyages prévus</a>
        <a href="#parametres" class="menu-btn">Paramètres</a>
        <form action="logout.php" method="post">
            <button type="submit" class="menu-btn logout">Se déconnecter</button>
        </form>
    </aside>

    <section class="profile-content">
        <h2>Mes Voyages</h2>
        
        <div class="travels">
            <?php if (!empty($voyages)): ?>
                <?php foreach ($voyages as $voyage): ?>
                    <div class="travel-card">
                        <div class="price">€ <?= htmlspecialchars($voyage['montant']) ?></div>
                        <h3><?= htmlspecialchars($voyage['voyage_titre']) ?></h3>
                        <p>Date d'achat: <?= htmlspecialchars($voyage['date_achat']) ?></p>
                        <p>Nombre de personnes: <?= htmlspecialchars($voyage['nombre_personnes']) ?></p>
                        <a href="#" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun voyage réservé pour l'instant.</p>
            <?php endif; ?>
        </div>

    </section>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

</body>
</html>
