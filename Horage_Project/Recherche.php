<?php
require_once 'session.php';

DemarrageSession();
$pdo = DemarrageSQL();

$mot_cle = isset($_POST['mot_cle']) ? trim($_POST['mot_cle']) : (isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : "");
$recherche_effectuee = !empty($mot_cle);

$sql = "SELECT * FROM voyages";
$params = [];
if ($recherche_effectuee) {
    $sql .= " WHERE titre LIKE :mot_cle OR description LIKE :mot_cle";
    $params[':mot_cle'] = '%' . $mot_cle . '%';
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$voyages_filtres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tous les pays uniques pour le filtre
$pays = [];
foreach ($voyages_filtres as $voyage) {
    if ($voyage['Pays'] && !in_array($voyage['Pays'], $pays)) {
        $pays[] = $voyage['Pays'];
    }
}
sort($pays);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - Horage</title>
    <link rel="stylesheet" href="CSS/recherche.css?v=<?= time() ?>">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>

<?php AfficherHeader(); ?>

<div class="intro">
    <img src="img_horage/sang.png" alt="sang" width="80px" id="sang">
    <h2 class="tr">Recherche des voyages</h2>
    <p>Recherchez votre voyage en fonction de vos envies.</p>
</div>

<div class="formulaire_search">
    <form action="Recherche.php" method="post" id="bigSearchForm">
        <div class="search-filters">
            <div class="form-group filter-item">
                <label for="mot_cle">Mot-clé</label>
                <input type="text" id="mot_cle" name="mot_cle" placeholder="Ex : Forêt" value="<?= htmlspecialchars($mot_cle) ?>">
            </div>

            <input type="submit" value="Rechercher" class="submit_search bouton search-button">

            <div class="filter-item">
                <label for="filtrePays">Pays</label>
                <select id="filtrePays" name="filtrePays" class="bouton">
                    <option value="tous">Tous les pays</option>
                    <?php foreach($pays as $p): ?>
                        <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-item">
                <label>Tri principal</label>
                <div class="filter-combo">
                    <select id="tri1" class="bouton">
                        <option value="none" selected>Aucun tri</option>
                        <option value="prix">Prix</option>
                        <option value="date">Date</option>
                        <option value="duree">Durée</option>
                    </select>
                    <select id="sens1" class="bouton">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            </div>

            <div class="filter-item">
                <label>Tri secondaire</label>
                <div class="filter-combo">
                    <select id="tri2" class="bouton">
                        <option value="none" selected>Aucun tri</option>
                        <option value="prix">Prix</option>
                        <option value="date">Date</option>
                        <option value="duree">Durée</option>
                    </select>
                    <select id="sens2" class="bouton">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            </div>

             <div class="filter-item">
                <label>Tri secondaire</label>
                <div class="filter-combo">
                    <select id="tri3" class="bouton">
                        <option value="none" selected>Aucun tri</option>
                        <option value="prix">Prix</option>
                        <option value="date">Date</option>
                        <option value="duree">Durée</option>
                    </select>
                    <select id="sens3" class="bouton">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<main>
    <h2 class="tv">Résultats des voyages 🔍</h2>

    <?php if (!empty($voyages_filtres)) : ?>
    <div id="voyagesContainer">
        <?php foreach ($voyages_filtres as $index => $voyage):
            $duree = 1;
            if (!empty($voyage['DateDebut']) && !empty($voyage['DateFin'])) {
                $d1 = new DateTime($voyage['DateDebut']);
                $d2 = new DateTime($voyage['DateFin']);
                $duree = $d2->diff($d1)->days + 1;
            }
        ?>
            <div class="travel-card"
                data-index="<?= $index ?>"
                data-prix="<?= (int)$voyage['PrixBase'] ?>"
                data-date="<?= htmlspecialchars($voyage['DateDebut']) ?>"
                data-duree="<?= $duree ?>"
                data-pays="<?= htmlspecialchars($voyage['Pays']) ?>">

                <div class="price"><?= htmlspecialchars($voyage['PrixBase']) ?>€</div>
                <h3><?= htmlspecialchars($voyage['Titre']) ?></h3>
                <p><?= htmlspecialchars($voyage['Description']) ?></p>
                <p><strong>Durée :</strong> <?= $duree ?> jour(s)</p>
                <p><strong>Pays :</strong> <?= htmlspecialchars($voyage['Pays']) ?></p>
                <p><strong>Période :</strong> <?= htmlspecialchars($voyage['DateDebut']) ?> - <?= htmlspecialchars($voyage['DateFin']) ?></p>
                <a href="voyages_details.php?id=<?= urlencode($voyage['IdVoyage']) ?>" class="btn">Voir plus</a>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="pagination-controls" class="pagination"></div>

    <?php else: ?>
        <div id="voyagesContainer">
            <p>Aucun voyage trouvé pour votre recherche<?php if ($mot_cle) echo " « ".htmlspecialchars($mot_cle)." »"; ?>.</p>
        </div>
    <?php endif; ?>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

<script src="js/recherche.js" defer></script>
</body>
</html>
