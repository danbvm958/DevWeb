<?php
// Charger les voyages depuis un fichier JSON
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos offres - Horage</title>
    <link rel="stylesheet" href="CSS/voyage.css">
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
                <li><a href="Recherche.php" class="a1">Réserver</a></li>
                <li><a href="login.php" class="a1">Connexion</a></li>
                <li><a href="contact.php" class="a1">Contacts</a></li>
            </ul>
        </div>
    </header>

    <main>
    <h2>Les plus récents</h2>
        <div class="travels">
            <?php foreach ($voyages as $voyage) : ?>
                <div class="travel-card">
                    <div class="price"><?= htmlspecialchars($voyage['prix_total']) ?>€</div>
                    <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                    <p>Date: <?= htmlspecialchars($voyage['dates']['debut']) ?></p>
                    <p>Description: <?= htmlspecialchars($voyage['description']) ?></p>
                    <a href="#" class="btn">Voir plus</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

</body>
</html>
