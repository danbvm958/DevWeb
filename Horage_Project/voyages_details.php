<?php
require_once 'session.php';
$pdo = DemarrageSQL();
DemarrageSession();
if(VerificationConnexion() == 0 ){
    header("Location: login.php");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
$idVoyage = $_GET['id'] ?? null;

if (empty($idVoyage)) {
    header("Location: Reserve.php");
    exit;
}

// Récupération des données de base du voyage
$stmt = $pdo->prepare("SELECT * FROM voyages WHERE IdVoyage = ?");
$stmt->execute([$idVoyage]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($voyage)) {
    header("Location: Reserve.php");
    exit;
}

// Récupération des réductions
$stmt = $pdo->prepare("SELECT * FROM reduction WHERE IdVoyage = ?");
$stmt->execute([$idVoyage]);
$reductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour formater le prix
function afficherPrix($prix) {
    return number_format($prix, 0, '', ' ');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($voyage['Titre']) ?> - Horage</title>
    <link rel="stylesheet" href="CSS/details.css?v=<?= time() ?>">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>

<?php AfficherHeader(); ?>

<main class="container">
    <h1 id="voyage_title"><?= htmlspecialchars($voyage['Titre']) ?></h1>
    
    <section class="voyage-info">
        <div class="description">
            <?= htmlspecialchars($voyage['Description']) ?>
        </div>
        
        <div class="meta-info">
            <p><strong>Dates:</strong> <?= $voyage['DateDebut'] ?> au <?= $voyage['DateFin'] ?></p>
            <p><strong>Prix de base:</strong> <?= afficherPrix($voyage['PrixBase']) ?> €/pers</p>
            <p><strong>Places restantes:</strong> <?= $voyage['PlacesDispo'] ?></p>
            
            <div class="reductions">
                <h3>Réductions disponibles:</h3>
                <ul>
                    <?php foreach ($reductions as $reduction): ?>
                        <li>
                            <?php if ($reduction['TypeReduction'] === 'groupe'): ?>
                                À partir de <?= $reduction['ConditionReduction'] ?> personnes : 
                                <?= afficherPrix($reduction['PrixReduit']) ?> €/pers
                            <?php else: ?>
                                Pour <?= $reduction['ConditionReduction'] ?> enfants ou plus : 
                                Réduction de <?= afficherPrix($reduction['PrixReduit']) ?> € par enfant
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <form method="POST" action="recapitulatif.php" id="reservation-form">
        <input type="hidden" name="voyage_id" value="<?= $voyage['IdVoyage'] ?>">
        <h2>Réservation</h2>
        
        <div class="form-group">
            <label for="nb_adultes">Nombre d'adultes:</label>
            <input type="number" id="nb_adultes" name="nb_adultes" min="1" max="<?= $voyage['PlacesDispo'] ?>" value="1" required>
        </div>
        
        <div class="form-group">
            <label for="nb_enfants">Nombre d'enfants (moins de 12 ans):</label>
            <input type="number" id="nb_enfants" name="nb_enfants" min="0" max="<?= $voyage['PlacesDispo'] - 1 ?>" value="0">
        </div>
        
        <section class="etapes" id="etapes-container">
        
            <div class="loading">Chargement des options...</div>
        </section>
        
        <div class="option-group etape"> 
            <h3>Prix :</h3><h3 class="prix" id="total-price">0 €</h3>
        </div>
        
        <div class="actions">
            <a href="Reserve.php" class="btn">Retour aux voyages</a>
            <button type="submit" class="btn btn-primary">Valider la réservation</button>
        </div>
    </form>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est la propriété exclusive d'Horage.</p>
</footer>

<script>
    const voyageData = {
        id: <?= $voyage['IdVoyage'] ?>,
        prixBase: <?= $voyage['PrixBase'] ?>,
        reductions: <?= json_encode($reductions) ?>,
        placesDisponibles: <?= $voyage['PlacesDispo'] ?>
    };
    
    function formatPrix(prix) {
        return prix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' €';
    }
</script>
<script src="js/details.js" defer></script>
<script>
document.querySelector("form").addEventListener("submit", (e) => {
  console.log("== Champs sélectionnés ==");
  const inputs = document.querySelectorAll("input[type=radio]:checked, input[type=checkbox]:checked");
  inputs.forEach(input => {
    console.log(input.name + " = " + input.value);
  });
});
</script>
</body>
</html>