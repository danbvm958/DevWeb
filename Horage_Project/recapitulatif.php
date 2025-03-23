<?php  
session_start();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès interdit.");
}

// Charger les voyages depuis le fichier JSON
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];

// Récupérer l'ID du voyage sélectionné
$voyage_id = isset($_POST['voyage_id']) ? (int)$_POST['voyage_id'] : -1;
if (!isset($voyages[$voyage_id])) {
    die("Voyage introuvable.");
}
$voyage = $voyages[$voyage_id];

// Récupérer les choix de l'utilisateur
$nombre_personnes = isset($_POST['nombre_personnes']) ? (int)$_POST['nombre_personnes'] : 1;
$reduction_enfants = isset($_POST['reduction_enfants']); // Option cochée ou non
$options_choisies = isset($_POST['options']) ? $_POST['options'] : [];
$etapes_supprimees = isset($_POST['supprimer_etape']) ? array_map('intval', $_POST['supprimer_etape']) : [];

// Prix de base par personne
$prix_par_personne = $voyage['tarification']['prix_par_personne'];

// Appliquer la réduction groupe si le nombre de personnes est suffisant
if ($nombre_personnes >= $voyage['tarification']['reduction_groupe']['min_personnes']) {
    $prix_par_personne = $voyage['tarification']['reduction_groupe']['prix_reduit'];
}

// Appliquer réduction enfant uniquement si les conditions sont remplies
if ($reduction_enfants && $nombre_personnes >= 4) {
    $remise_par_enfant = $voyage['tarification']['reduction_enfants']['remise'];
    $prix_par_personne -= $remise_par_enfant;
}

// Calcul du prix total en excluant les étapes supprimées
$prix_total = 0;

foreach ($voyage['liste_etapes'] as $index => $etape) {
    if (in_array($index, $etapes_supprimees, true)) {
        continue; // Ne pas facturer les étapes supprimées
    }

    // Ajouter le prix de base de l'étape
    $prix_total += $etape['prix_etape'];

    // Ajouter le coût des options sélectionnées
    if (!empty($options_choisies[$index])) {
        foreach ($options_choisies[$index] as $categorie => $choix) {
            if (is_array($choix)) {
                foreach ($choix as $option_val) {
                    foreach ($etape['options'] as $option) {
                        if (in_array($option_val, $option['valeurs'], true)) {
                            $prix_total += $option['prix'] * $nombre_personnes;
                        }
                    }
                }
            }
        }
    }
}

// Appliquer le prix par personne sur le prix total
$prix_total *= $nombre_personnes;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif du voyage - Horage</title>
    <link rel="stylesheet" href="CSS/recapitulatif.css?v=<?php echo time(); ?>">
</head>
<body>
<header>
    <div class="header_1">
        <h1>Horage</h1>
        <img src="img_horage/logo-Photoroom.png" alt="logo de Horage" width="200px">
    </div>   

    <div class="nav">
        <ul>
            <li>
                <a href="/horage_project/accueil.php" class="a1">Accueil</a>
            </li>

            <li>
                <a href="/horage_project/presentation.php" class="a1">Présentation</a>
            </li>

            <li>
                <a href="/horage_project/Reserve.php" class="a1">Nos offres</a>
            </li>

            <li>
                <a href="/horage_project/Recherche.php" class="a1">Réserver</a>
            </li>

            <?php if (isset($_SESSION['user'])): ?>
            <li>
                <a href="/horage_project/profil_user.php" class="a1">Profil</a>
            </li>
            <?php else: ?>
            <li>
                <a href="/horage_project/login.php" class="a1">Connexion</a>
            </li>
            <?php endif; ?>

            <li>
                <a href="/horage_project/contact.php" class="a1">Contacts</a>
            </li>

        </ul>
    </div>
</header>

<main>
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['description']) ?></p>
        <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['dates']['debut']) ?> au <?= htmlspecialchars($voyage['dates']['fin']) ?> (<?= htmlspecialchars($voyage['dates']['duree']) ?>)</p>
        <p><strong>Nombre de personnes :</strong> <?= $nombre_personnes ?></p>
        <p><strong>Prix total mis à jour :</strong> <?= number_format($prix_total, 2, ',', ' ') ?> €</p>
    </div>
    <h3 id="subtitle">Étapes du voyage</h3>
    <ul>
        <?php foreach ($voyage['liste_etapes'] as $index => $etape) : ?>
            <?php if (!in_array($index, $etapes_supprimees, true)) : ?>
                <li class="parent">
                    <strong><?= htmlspecialchars($etape['titre']) ?></strong> - <?= htmlspecialchars($etape['position']['ville']) ?>
                    <ul>
                        <?php if (!empty($options_choisies[$index])) : ?>
                            <?php foreach ($options_choisies[$index] as $categorie => $choix) : ?>
                                <?php if (is_array($choix)) : ?>
                                    <li><strong><?= htmlspecialchars($categorie) ?> :</strong> <?= implode(', ', array_map('htmlspecialchars', $choix)) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>Aucune option choisie</li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <!-- Correction du bouton Modifier -->
    <form action="voyages_details.php" method="get">
        <input type="hidden" name="id" value="<?= $voyage_id ?>">
        <button type="submit" class="sub">Modifier</button> 
    </form>

    <br><br>

    <form action="paiement.php" method="post">
        <input type="hidden" name="voyage_id" value="<?= $voyage_id ?>">
        <input type="hidden" name="nombre_personnes" value="<?= $nombre_personnes ?>">
        <input type="hidden" name="prix_total" value="<?= $prix_total ?>">
        <button type="submit" class="sub">Confirmer et payer</button>
    </form>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

</body>
</html>
