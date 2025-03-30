<?php  
session_start();


$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];


$mot_cle = isset($_POST['mot_cle']) ? trim($_POST['mot_cle']) : (isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : "");


$recherche_effectuee = !empty($mot_cle);


$voyages_filtres = $recherche_effectuee ? array_filter($voyages, function ($voyage) use ($mot_cle) {
    return stripos($voyage['titre'], $mot_cle) !== false || stripos($voyage['description'], $mot_cle) !== false;
}) : $voyages;

$voyages_filtres = array_values($voyages_filtres);


$voyages_par_page = 3;
$page_actuelle = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$total_voyages = count($voyages_filtres);
$nombre_total_pages = ($total_voyages > 0) ? ceil($total_voyages / $voyages_par_page) : 1;
$offset = ($page_actuelle - 1) * $voyages_par_page;
$voyages_a_afficher = array_slice($voyages_filtres, $offset, $voyages_par_page);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - Horage</title>
    <link rel="stylesheet" href="CSS/voyage.css?v=<?php echo time(); ?>">
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

    <div class="intro">
        <img src="img_horage/sang.png" alt="sang" width="80px" id="sang">
        <h2 class="tr">Recherche des voyages</h2>
        <p>Recherchez votre voyage en fonction de vos envies.</p>
    </div>

    <div class="formulaire_search">
        <form action="Recherche.php" method="post">
            <div class="form-group">
                <label for="mot_cle">Rechercher par mot-clé</label>
                <input type="text" name="mot_cle" id="mot_cle" placeholder="Ex : Forêt" value="<?= htmlspecialchars($mot_cle) ?>">
                <br><br><br>
            </div>
            <div>
                <input type="submit" value="Rechercher">
            </div>
        </form>
    </div>

    <main>
        <h2 class="tv">Résultats des voyages 🔍</h2>
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

                
                <?php if ($nombre_total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page_actuelle > 1): ?>
                            <a href="?page=<?= $page_actuelle - 1 ?>&mot_cle=<?= urlencode($mot_cle) ?>">&laquo; Précédent</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $nombre_total_pages; $i++) : ?>
                            <a href="?page=<?= $i ?>&mot_cle=<?= urlencode($mot_cle) ?>" class="<?= ($i == $page_actuelle) ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page_actuelle < $nombre_total_pages): ?>
                            <a href="?page=<?= $page_actuelle + 1 ?>&mot_cle=<?= urlencode($mot_cle) ?>">Suivant &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <p>Aucun voyage trouvé pour "<?= htmlspecialchars($mot_cle) ?>".</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

</body>
</html>
