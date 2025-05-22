<?php
require_once 'session.php';
$pdo = DemarrageSQL();
DemarrageSession();
// Fonction pour afficher les prix proprement
function afficherPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}


// Récupérer l'id du voyage depuis l'URL
$id_voyage = $_GET['id_voyage'] ?? null;

if (!$id_voyage) {
    die('Identifiant du voyage manquant.');
}

$utilisateur = $_SESSION['user'] ?? null;
if (!$utilisateur) {
    die('Utilisateur non connecté.');
}

// Récupérer les informations du voyage
$stmt = $pdo->prepare("SELECT * FROM voyages WHERE IdVoyage = ?");
$stmt->execute([$id_voyage]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voyage) {
    die('Voyage introuvable.');
}

// Récupérer les étapes du voyage
$stmt = $pdo->prepare("SELECT * FROM etapes WHERE IdVoyage = ? ORDER BY DateArrive");
$stmt->execute([$id_voyage]);
$etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les options commandées pour ce voyage
$stmt = $pdo->prepare("SELECT oc.*, co.Nom, co.Prix, oe.NomOption 
                       FROM options_commande oc
                       JOIN choix_options co ON oc.IdChoix = co.IdChoix
                       JOIN options_etape oe ON oc.IdOption = oe.IdOption
                       WHERE oc.IdCommande IN (SELECT IdCommande FROM voyage_payee WHERE IdVoyage = ? AND IdUtilisateur = ?)");
$stmt->execute([$id_voyage, $utilisateur['id']]);
$options_commandees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organiser les options par étape
$options_par_etape = [];
foreach ($options_commandees as $option) {
    $options_par_etape[$option['IdEtape']][] = $option;
}

$stmt = $pdo->prepare("SELECT * FROM voyage_payee WHERE IdVoyage = ? AND IdUtilisateur = ?");
$stmt->execute([$id_voyage, $_SESSION['user']['id']]);
$vp=$stmt->fetch(PDO::FETCH_ASSOC);
$prix_total = $vp['Prix'] ;

$typeUtilisateur = $_SESSION['user']['type'];


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif du voyage - Horage</title>
    <link rel="stylesheet" href="CSS/recapitulatif.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
<?php 
    AfficherHeader();
?>

<main>
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['Titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['Description']) ?></p>
        <p><strong>Dates :</strong> Du <?= date('d/m/Y', strtotime($voyage['DateDebut'])) ?> au <?= date('d/m/Y', strtotime($voyage['DateFin'])) ?></p>
        <p><strong>Places disponibles :</strong> <?= htmlspecialchars($voyage['PlacesDispo']) ?></p>
        
<p><strong>Prix total :</strong> <?= afficherPrix($prix_total) ?>
    <?php if ($typeUtilisateur === "vip"): ?>
        <span style="color:green;">(-20% VIP)</span>
    <?php endif; ?>
</p>

    
    </div>

    <h3 id="subtitle">Étapes du voyage</h3>
    <ul>
        <?php foreach ($etapes as $etape) : ?>
            <li class="parent">
                <strong><?= htmlspecialchars($etape['Titre']) ?></strong> - 
                <?= htmlspecialchars($etape['Position']) ?> 
                (<?= date('d/m/Y', strtotime($etape['DateArrive'])) ?> au <?= date('d/m/Y', strtotime($etape['DateDepart'])) ?>)
                <ul>
                    <?php if (isset($options_par_etape[$etape['IdEtape']])): ?>
                        <?php foreach ($options_par_etape[$etape['IdEtape']] as $option): ?>
                            <li>
                                <strong><?= htmlspecialchars($option['NomOption']) ?>:</strong> 
                                <?= htmlspecialchars($option['Nom']) ?> - <?= afficherPrix($option['Prix']*($vp['NbAdultes']+$vp['NbEnfants'])) ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Aucune option choisie pour cette étape</li>
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