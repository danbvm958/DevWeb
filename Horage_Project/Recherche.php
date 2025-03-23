<?php    
// Charger les voyages depuis un fichier JSON
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];

// Récupérer le mot-clé depuis le formulaire ou l'URL
$mot_cle = isset($_POST['mot_cle']) ? trim($_POST['mot_cle']) : (isset($_GET['mot_cle']) ? trim($_GET['mot_cle']) : "");

// Vérifier si une recherche a été effectuée
$recherche_effectuee = !empty($mot_cle);

// Filtrer les voyages si un mot-clé est entré
$voyages_filtres = $recherche_effectuee ? array_filter($voyages, function ($voyage) use ($mot_cle) {
    return stripos($voyage['titre'], $mot_cle) !== false || stripos($voyage['description'], $mot_cle) !== false;
}) : $voyages;

// Réindexer le tableau pour garder les bons ID
$voyages_filtres = array_values($voyages_filtres);

// Pagination
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
                <li><a href="/horage_project/login.php" class="a1">Connexion</a></li>
                <li><a href="/horage_project/contact.php" class="a1">Contacts</a></li>
            </ul>
        </div>
    </header>

    <div class="intro">
        <img src="img_horage/sang.png" alt="sang" width="80px" id="sang">
        <h2 class="tr">Recherche des voyages</h2>
        <p>Ici, c'est l'endroit parfait pour chercher ton voyage selon tes désirs ! Une envie de château fort, de maison hantée, d'une visite avec des fantômes, d'une chasse aux sorcières... Alors fais-le savoir à ton ami le moteur de recherche, il sera là pour t'accompagner !</p>
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
                <?php foreach ($voyages_a_afficher as $index => $voyage) : ?>
                    <div class="travel-card">
                        <div class="price"><?= htmlspecialchars($voyage['prix_total']) ?>€</div>
                        <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                        <p>Date: <?= htmlspecialchars($voyage['dates']['debut']) ?></p>
                        <p>Description: <?= htmlspecialchars($voyage['description']) ?></p>
                        <a href="voyages_details.php?id=<?= array_search($voyage, $voyages) ?>" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>


                <!-- Pagination -->
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
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

</body>
</html>
