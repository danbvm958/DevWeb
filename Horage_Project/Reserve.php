<?php  
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];
session_start();
// Ajouter un identifiant unique basé sur l'ordre initial du fichier JSON
foreach ($voyages as $key => $voyage) {
    $voyages[$key]['id'] = $key;
}

// Trier les voyages du plus récent au plus ancien
usort($voyages, function ($a, $b) {
    return strtotime($b['dates']['debut']) - strtotime($a['dates']['debut']);
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
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/themeSwitcher.js" defer></script>
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
                    </ul>
                </div>
        </header>

    <main>
        <h2 class="tv">Les plus récents 🔍</h2>
        <div class="travels">
            <?php if (!empty($voyages_a_afficher)) : ?>
                <?php foreach ($voyages_a_afficher as $voyage) : ?>
                    <div class="travel-card">
                        <div class="price"><?= htmlspecialchars($voyage['prix_total']) ?>€</div>
                        <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                        <p>Date: <?= htmlspecialchars($voyage['dates']['debut']) ?></p>
                        <p>Description: <?= htmlspecialchars($voyage['description']) ?></p>
                        <a href="voyages_details.php?id=<?= urlencode($voyage['id_voyage']) ?>" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun voyage disponible.</p>
            <?php endif; ?>
        </div>

        <?php if ($nombre_total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page_actuelle > 1): ?>
                <a href="?page=<?= $page_actuelle - 1 ?>">&laquo; Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $nombre_total_pages; $i++) : ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page_actuelle) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page_actuelle < $nombre_total_pages): ?>
                <a href="?page=<?= $page_actuelle + 1 ?>">Suivant &raquo;</a>
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


