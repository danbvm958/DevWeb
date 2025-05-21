<?php  
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();
$stmt = $pdo->prepare("SELECT * FROM voyages");
$stmt->execute();
$voyages = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Trier les voyages du plus récent au plus ancien
usort($voyages, function ($a, $b) {
    return strtotime($a['DateDebut']) - strtotime($b['DateDebut']);
});

$voyages_par_page = 3;
$page_actuelle = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page_actuelle - 1) * $voyages_par_page;
$voyages_a_afficher = array_slice($voyages, $offset, $voyages_par_page);
$nombre_total_pages = ceil(count($voyages) / $voyages_par_page);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos offres - Horage</title>
    <link rel="stylesheet" href="CSS/voyage.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
</head>
<body>

<?php 
    AfficherHeader();
?>

    <main>
        <h2 class="tv">Les plus récents 🔍</h2>
        <div class="travels">
            <?php if (!empty($voyages_a_afficher)) : ?>
                <?php foreach ($voyages_a_afficher as $voyage) : ?>
                    <div class="travel-card">
                        <div class="price"><?= htmlspecialchars($voyage['PrixBase']) ?>€</div>
                        <h3><?= htmlspecialchars($voyage['Titre']) ?></h3>
                        <p>Date: <?= htmlspecialchars($voyage['DateDebut']) ?></p>
                        <p>Description: <?= htmlspecialchars($voyage['Description']) ?></p>
                        <a href="voyages_details.php?id=<?= urlencode($voyage['IdVoyage']) ?>" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun voyage disponible.</p>
            <?php endif; ?>
        </div>

        <?php if ($nombre_total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page_actuelle > 1): ?>
                <a class="pagination-btn" href="?page=<?= $page_actuelle - 1 ?>">&laquo; Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $nombre_total_pages; $i++) : ?>
                <a class="pagination-btn" href="?page=<?= $i ?>" class="<?= ($i == $page_actuelle) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page_actuelle < $nombre_total_pages): ?>
                <a class="pagination-btn" href="?page=<?= $page_actuelle + 1 ?>">Suivant &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>

    </footer>

</body>
</html>

