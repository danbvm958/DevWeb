<?php
session_start();

// Charger les voyages depuis le fichier JSON
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];

// Vérifier si un ID de voyage est fourni
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (!isset($voyages[$id])) {
    die("Voyage introuvable.");
}
$voyage = $voyages[$id];

// Définir la catégorie de prix selon l'utilisateur
$categorie_prix = 'adulte'; // Valeur par défaut
if (isset($_SESSION['categorie'])) {
    $categorie_prix = $_SESSION['categorie']; // 'adulte', 'enfant' ou 'senior'
}

// Prix de base
$prix_base = $voyage['tarification']['prix_par_personne'];

// Nombre de places disponibles
$places_disponibles = $voyage['places_disponibles'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($voyage['titre']) ?> - Horage</title>
    <link rel="stylesheet" href="CSS/details.css?v=<?php echo time(); ?>">
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
                <li><a href="presentation.php" class="a1">Présentation</a></li>
                <li><a href="Reserve.php" class="a1">Nos offres</a></li>
                <li><a href="Recherche.php" class="a1">Réserver</a></li>
                <li><a href="login.php" class="a1">Connexion</a></li>
                <li><a href="contact.php" class="a1">Contacts</a></li>
            </ul>
        </div>
    </header>

    <main>
        <h2 class="title">Détails du voyage</h2>
        <div class="voyage-detail">
            <h3 class="subtitle"><?= htmlspecialchars($voyage['titre']) ?></h3>
            <div class="hero1">
                <p><strong>Description :</strong> <?= htmlspecialchars($voyage['description']) ?></p>
                <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['dates']['debut']) ?> au <?= htmlspecialchars($voyage['dates']['fin']) ?> (<?= htmlspecialchars($voyage['dates']['duree']) ?>)</p>
                <p><strong>Prix de base par adulte :</strong> <?= $prix_base ?>€</p>
                <p><strong>Places disponibles :</strong> <?= $places_disponibles ?></p>
            </div>

            <h2 class="title">Options de personnalisation</h2>

            <form action="recapitulatif.php" method="post">
                <input type="hidden" name="voyage_id" value="<?= $id ?>">
                
                <label for="nombre_personnes"><strong>Nombre total de personnes :</strong></label>
                <input type="number" id="nombre_personnes" name="nombre_personnes" min="1" max="<?= $places_disponibles ?>" value="1" required><br><br>

                <input type="hidden" name="prix_base" value="<?= $prix_base ?>">

                <label>
                    <input type="checkbox" name="reduction_enfants" value="1">
                    Plus de 2 enfants ? (Réduction appliquée)
                </label><br><br>

                <h2 class="title">Étapes du voyage</h2>
                
                <?php foreach ($voyage['liste_etapes'] as $index => $etape) : ?>
                    <fieldset class="etape">
                        <legend><h5><?= htmlspecialchars($etape['titre']) ?></h5></legend>
                        <p><strong>Lieu :</strong> <?= htmlspecialchars($etape['position']['ville']) ?> (<?= htmlspecialchars($etape['position']['gps']) ?>)</p>
                        <p><strong>Dates :</strong> <?= htmlspecialchars($etape['dates']['arrivee']) ?> - <?= htmlspecialchars($etape['dates']['depart']) ?></p>
                        
                        <label>
                            <input type="checkbox" name="supprimer_etape[<?= $index ?>]" value="1">
                            Ne pas inclure cette étape
                        </label>
                        
                        <h6>Options disponibles :</h6>
                        <?php foreach ($etape['options'] as $option) : ?>
                            <div class="option">
                                <label><strong><?= htmlspecialchars($option['nom']) ?> :</strong></label><br>

                                <?php foreach ($option['valeurs'] as $valeur) : ?>
                                    <?php 
                                    // Vérification si l'option a un prix spécifique par catégorie d'âge
                                    $prix_option = isset($option['prix'][$categorie_prix]) ? $option['prix'][$categorie_prix] : $option['prix'];
                                    if ($prix_option === null) {
                                        continue;
                                    }
                                    ?>

                                    <?php if (strtolower($option['nom']) === 'activité') : ?>
                                        <!-- Option à choix multiple : afficher le prix -->
                                        <input type="checkbox" name="options[<?= $index ?>][<?= htmlspecialchars($option['nom']) ?>][]" 
                                               value="<?= htmlspecialchars($valeur) ?>"> 
                                        <?= htmlspecialchars($valeur) ?> (+<?= htmlspecialchars($prix_option) ?>€)<br>
                                    <?php else : ?>
                                        <!-- Option à choix unique : ne pas afficher le prix -->
                                        <input type="radio" name="options[<?= $index ?>][<?= htmlspecialchars($option['nom']) ?>]" 
                                               value="<?= htmlspecialchars($valeur) ?>"> 
                                        <?= htmlspecialchars($valeur) ?><br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </fieldset><br><br>
                <?php endforeach; ?>

                <button type="submit" class="sub">Valider la sélection</button>
            </form>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
</body>
</html> 
