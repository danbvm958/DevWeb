<?php
// On inclut les fichiers nécessaires pour la session et les calculs
require_once 'session.php';
require_once 'calcul.php';

// Je démarre la connexion à la base de données
$pdo = DemarrageSQL();
// On démarre la session
DemarrageSession();

// On vérifie si l'ID du voyage est bien présent
if (!isset($_POST['voyage_id'])) {
    die("Voyage introuvable.");
}

// Je récupère les données du formulaire
$nb_adultes = isset($_POST['nb_adultes']) ? intval($_POST['nb_adultes']) : 0;
$nb_enfants = isset($_POST['nb_enfants']) ? intval($_POST['nb_enfants']) : 0;
$nombre_personnes = $nb_adultes + $nb_enfants;
$options_choisies = isset($_POST['options']) ? $_POST['options'] : [];
$voyage_id = $_POST['voyage_id'];

// Je recherche le voyage sélectionné dans la base de données
$stmt = $pdo->prepare("SELECT * FROM voyages WHERE IdVoyage = ?");
$stmt->execute([$voyage_id]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);

// On récupère les réductions applicables à ce voyage
$stmt = $pdo->prepare("SELECT * FROM reduction WHERE IdVoyage = ?");
$stmt->execute([$voyage_id]);
$reductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Je récupère les étapes du voyage
$stmt = $pdo->prepare("SELECT * FROM etapes WHERE IdVoyage = ?");
$stmt->execute([$voyage_id]);
$etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$voyage) {
    die("Voyage introuvable.");
}

// Fonction pour formater l'affichage des prix
function afficherPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}

// On calcule le prix total avec les options choisies
$resultat = calculerPrixTotalAvecOptions($pdo, $voyage_id, $nb_adultes, $nb_enfants, $options_choisies);

$prix_total = $resultat['prix_total'];
$etapes_avec_options = $resultat['etapes_avec_options'];
// Je stocke les informations de réservation en session pour le paiement
$_SESSION['pending_payment'][] = [
    'voyage_id' => $voyage_id,
    'nombre_personnes' => $resultat['nombre_personnes'],
    'nb_adultes' => $nb_adultes,
    'nb_enfants' => $nb_enfants,
    'options_choisies' => $etapes_avec_options,
    'prix_total' => $prix_total
];
// On enregistre l'index du dernier paiement en attente
$_SESSION['npayment'] = count($_SESSION['pending_payment'])-1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Je définis les métadonnées de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- On donne un titre à la page -->
    <title>Récapitulatif du voyage - Horage</title>
    <!-- Je lie la feuille de style CSS avec un paramètre de version -->
    <link rel="stylesheet" href="CSS/recapitulatif.css?v=<?php echo time(); ?>">
    
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <!-- On charge les scripts JavaScript -->
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
<?php 
    // J'affiche le header du site
    AfficherHeader();
?>

<main>
    <!-- Section principale du récapitulatif -->
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['Titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['Description']) ?></p>
        <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['DateDebut']) ?> au <?= htmlspecialchars($voyage['DateFin']) ?> </p>
        <p><strong>Nombre d'adultes :</strong> <?= $nb_adultes ?></p>
        <p><strong>Nombre d'enfants :</strong> <?= $nb_enfants ?></p>
        <p><strong>Prix total :</strong> <?= afficherPrix($prix_total) ?></p>
    </div>

    <!-- Section des étapes du voyage -->
    <h3 id="subtitle">Étapes du voyage</h3>
    <ul>
    <?php foreach ($etapes as $etape) : ?>
        <li class="parent">
            <strong><?= htmlspecialchars($etape['Titre']) ?></strong> - <?= htmlspecialchars($etape['Position']) ?>
            <ul>
                <?php if (isset($etapes_avec_options[$etape['IdEtape']]) && !empty($etapes_avec_options[$etape['IdEtape']])) : ?>
                    <?php foreach ($etapes_avec_options[$etape['IdEtape']] as $option) : ?>
                        <li>
                            <strong><?= htmlspecialchars($option['nom']) ?> :</strong> 
                            <?= htmlspecialchars($option['choix']) ?> - 
                            <?= afficherPrix($option['prix'] * $nombre_personnes) ?> (<?= afficherPrix($option['prix']) ?> × <?= $nombre_personnes ?> pers)
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li>Aucune option choisie</li>
                <?php endif; ?>
            </ul>
        </li>
    <?php endforeach; ?>
    </ul>

    <!-- Formulaire pour modifier la réservation -->
    <form action="voyages_details.php" method="get">
        <input type="hidden" name="id" value="<?= $voyage_id ?>">
        <input type="hidden" name="nb_adultes" value="<?= $nb_adultes ?>">
        <input type="hidden" name="nb_enfants" value="<?= $nb_enfants ?>">
        <button type="submit" class="sub">Modifier</button> 
    </form>

    <!-- Formulaire pour confirmer et payer -->
    <form action="vers_CyBank.php" method="post">
        <input type="hidden" name="voyage_id" value="<?= $voyage_id ?>">
        <input type="hidden" name="nombre_personnes" value="<?= $nombre_personnes ?>">
        <input type="hidden" name="prix_total" value="<?= $prix_total ?>">
        <input type="hidden" name="voyage_index" value="<?= count($_SESSION['pending_payment'])-1?>">
        <button type="submit" class="sub">Confirmer et payer</button>
    </form>
</main>

<!-- Pied de page -->
<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>
</body>
</html>