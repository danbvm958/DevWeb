<?php  
session_start();
$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
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
    <link rel="stylesheet" href="CSS/voyage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/themeSwitcher.js" defer></script>
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
                        <li>
                            <a href="accueil.php" class="a1">Accueil</a>
                        </li>
                        
                        <li>
                            <a href="presentation.php" class="a1">Presentation</a>
                        </li>
                        
                        <li>
                            <a href="Reserve.php" class="a1">Nos offres</a>
                        </li>

                        <li>
                            <a href="Recherche.php" class="a1">reserver</a>
                        </li>
                        
                        <?php
                        $pageProfil = 'login.php'; // par défaut, page connexion

                        if (isset($_SESSION['user'])) {
                            $typeUser = $_SESSION['user']['type'];
                            $pageProfil = match ($typeUser) {
                                'admin'  => 'profil_admin.php',
                                'normal' => 'profil_user.php',
                                default  => 'profil_vip.php',
                            };
                        }
                        ?>
                        <li><a href="<?= $pageProfil ?>" class="a1"><?= isset($_SESSION['user']) ? 'Profil' : 'Connexion' ?></a></li>


                        <li>
                            <a href="accueil.php" class="a1">contacts</a>
                        </li>
                        <li><a href="panier.php" class="a1">Panier</a></li>
                    </ul>
                </div>
        </header>

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

