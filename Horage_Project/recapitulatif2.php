<?php
session_start();

// Fonction pour afficher les prix proprement
function afficherPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}

// Charger les voyages depuis voyages.json
$voyages = json_decode(file_get_contents('data/voyages.json'), true)['voyages'];

// Récupérer l'id du voyage depuis l'URL
$id_voyage = $_GET['id_voyage'] ?? null;

if (!$id_voyage) {
    die('Identifiant du voyage manquant.');
}

// Charger les données utilisateur depuis la session (adapter si nécessaire)
$utilisateur = $_SESSION['user'] ?? null;
if (!$utilisateur) {
    die('Utilisateur non connecté.');
}

// Trouver le voyage sauvegardé dans les données utilisateur
$voyage_sauvegarde = null;
foreach ($utilisateur['voyages'] as $v) {
    if ($v['voyage_id'] === $id_voyage) {
        $voyage_sauvegarde = $v;
        break;
    }
}

if (!$voyage_sauvegarde) {
    die('Voyage sauvegardé introuvable.');
}

// Rechercher le voyage demandé
$voyage = null;
foreach ($voyages as $v) {
    if ($v['id_voyage'] === $id_voyage) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    die('Voyage introuvable dans la liste des voyages.');
}

// Données du voyage
$nombre_personnes = $voyage_sauvegarde['nombre_personnes'];
$prix_total = $voyage_sauvegarde['montant'];
$options_choisies = $voyage_sauvegarde['options_choisies'];

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
            <li><a href="/horage_project/accueil.php" class="a1">Accueil</a></li>
            <li><a href="/horage_project/presentation.php" class="a1">Présentation</a></li>
            <li><a href="/horage_project/Reserve.php" class="a1">Nos offres</a></li>
            <li><a href="/horage_project/Recherche.php" class="a1">Réserver</a></li>
            <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/horage_project/profil_user.php" class="a1">Profil</a></li>
            <?php else: ?>
            <li><a href="/horage_project/login.php" class="a1">Connexion</a></li>
            <?php endif; ?>
            <li><a href="/horage_project/contact.php" class="a1">Contacts</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['description']) ?></p>
        <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['dates']['debut']) ?> au <?= htmlspecialchars($voyage['dates']['fin']) ?> (<?= htmlspecialchars($voyage['dates']['duree']) ?>)</p>
        <p><strong>Nombre de personnes :</strong> <?= $nombre_personnes ?></p>
        <p><strong>Prix total :</strong> <?= afficherPrix($prix_total) ?></p>
    </div>

    <h3 id="subtitle">Étapes du voyage</h3>
    <ul>
        <?php foreach ($voyage['liste_etapes'] as $etape) : ?>
            <li class="parent">
                <strong><?= htmlspecialchars($etape['titre']) ?></strong> - <?= htmlspecialchars($etape['position']['ville']) ?>
                <ul>
                <?php if (isset($options_choisies[$etape['id_etape']])): ?>
    <?php foreach ($options_choisies[$etape['id_etape']] as $choix_data): ?>
        <li>
            <?= htmlspecialchars($choix_data['choix']) ?> - <?= afficherPrix($choix_data['prix']) ?>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li>Aucune option choisie</li>
<?php endif; ?>

                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="profil_travel.php" class="btn"> Retour au profil </a>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

</body>
</html>