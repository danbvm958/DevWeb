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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - Horage</title>
    <link rel="stylesheet" href="CSS/recherche.css?v=<?= time() ?>">
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
            <li><a href="accueil.php" class="a1">Accueil</a></li>
            <li><a href="presentation.php" class="a1">Presentation</a></li>
            <li><a href="Reserve.php" class="a1">Nos offres</a></li>
            <li><a href="Recherche.php" class="a1">reserver</a></li>
            <?php
            $pageProfil = 'login.php';
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
            <li><a href="contact.php" class="a1">contacts</a></li>
            <li><a href="panier.php" class="a1">Panier</a></li>
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
            <input type="text" id="mot_cle" name="mot_cle" placeholder="Ex : Forêt" value="<?= htmlspecialchars($mot_cle) ?>">
        </div>
        <div style="text-align:right;">
            <input type="submit" value="Rechercher" class="submit_search">
        </div>
    </form>
</div>

<main>
    <h2 class="tv">Résultats des voyages 🔍</h2>

    <!-- TRI avec croissant/décroissant -->
    <div style="text-align:right; margin-bottom:10px;">
        <label for="triVoyages">Trier par : </label>
        <select id="triVoyages">
            <option value="prix_total">Prix</option>
            <option value="date">Date</option>
            <option value="duree">Durée</option>
            <!-- <option value="etapes">Nombre d'étapes</option> --> <!-- SUPPRIMÉ -->
        </select>
        <select id="sensTri" style="margin-left:8px;">
            <option value="asc">Croissant</option>
            <option value="desc">Décroissant</option>
        </select>
    </div>

    <div class="travels" id="voyagesContainer">
        <?php if (!empty($voyages_filtres)) : ?>
            <?php foreach ($voyages_filtres as $voyage) : ?>
                <?php
                // Calcul sûr de la durée en jours
                $duree = 0;
                if (isset($voyage['duree']) && is_numeric($voyage['duree'])) {
                    $duree = (int)$voyage['duree'];
                } elseif (isset($voyage['dates']['debut']) && isset($voyage['dates']['fin'])) {
                    try {
                        $date1 = new DateTime($voyage['dates']['debut']);
                        $date2 = new DateTime($voyage['dates']['fin']);
                        $duree = $date1->diff($date2)->days;
                    } catch (Exception $e) {
                        $duree = 0;
                    }
                }

                // Calcul sûr du nombre d'étapes, toujours avec "liste_etapes"
                $etapes = (isset($voyage['liste_etapes']) && is_array($voyage['liste_etapes'])) ? count($voyage['liste_etapes']) : 0;
                ?>
                <div class="travel-card"
                    data-prix="<?= htmlspecialchars($voyage['prix_total']) ?>"
                    data-date="<?= htmlspecialchars($voyage['dates']['debut']) ?>"
                    data-duree="<?= $duree ?>"
                    <!-- data-etapes="<?= $etapes ?>" SUPPRIMÉ -->
                    >
                    <div class="price"><?= htmlspecialchars($voyage['prix_total']) ?>€</div>
                    <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                    <p>Date: <?= htmlspecialchars($voyage['dates']['debut']) ?></p>
                    <p>Description: <?= htmlspecialchars($voyage['description']) ?></p>
                    <p>Nombre d'étapes : <?= $etapes ?></p>
                    <a href="voyages_details.php?id=<?= urlencode($voyage['id_voyage']) ?>" class="btn">Voir plus</a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun voyage trouvé pour "<?= htmlspecialchars($mot_cle) ?>".</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

<script>
function trierVoyages() {
    const type = document.getElementById('triVoyages').value;
    const sens = document.getElementById('sensTri').value;

    const cards = Array.from(document.querySelectorAll('.travel-card'));

    cards.sort((a, b) => {
        let valA = 0, valB = 0;

        if (type === 'prix_total') {
            valA = parseFloat(a.dataset.prix) || 0;
            valB = parseFloat(b.dataset.prix) || 0;
        } else if (type === 'date') {
            valA = new Date(a.dataset.date).getTime() || 0;
            valB = new Date(b.dataset.date).getTime() || 0;
        } else if (type === 'duree') {
            valA = parseInt(a.dataset.duree, 10) || 0;
            valB = parseInt(b.dataset.duree, 10) || 0;
        }
        // Bloc "etapes" SUPPRIMÉ

        return sens === 'asc' ? valA - valB : valB - valA;
    });

    const container = document.getElementById('voyagesContainer');
    cards.forEach(card => container.appendChild(card));
}

document.getElementById('triVoyages').addEventListener('change', trierVoyages);
document.getElementById('sensTri').addEventListener('change', trierVoyages);
window.addEventListener('DOMContentLoaded', trierVoyages);
</script>

</body>
</html>
