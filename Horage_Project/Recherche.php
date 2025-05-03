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
    <link rel="stylesheet" href="CSS/recherche.css?v=<?= time() ?>">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/themeSwitcher.js" defer></script>
    <style>
        /* Ajoute éventuellement un peu de style pour le tri */
        .block-tri {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5em;
        }
        .block-tri select, .block-tri button {
            height: 30px;
            border-radius: 6px;
        }
    </style>
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

<!-- Module de tri étendu : tri JS sur la page courante (pas sur tout le serveur) -->
<div class="block-tri">
    <label>Trier par :</label>
    <select id="tri-critere">
        <option value="prix_total">Prix total</option>
        <option value="date_debut">Date de début</option>
        <option value="titre">Titre</option>
        <option value="duree">Durée (jours)</option>
        <option value="nb_etapes">Nombre d'étapes</option>
        <option value="places_disponibles">Places disponibles</option>
        <option value="prix_personne">Prix par personne</option>
    </select>
    <select id="tri-ordre">
        <option value="asc">⬆️ Croissant</option>
        <option value="desc">⬇️ Décroissant</option>
    </select>
    <button type="button" id="btn-trier">Trier</button>
</div>

<main>
    <h2 class="tv">Résultats des voyages 🔍</h2>
    <div class="travels" id="travels">
        <?php if (!empty($voyages_a_afficher)) : ?>
            <?php foreach ($voyages_a_afficher as $voyage) : ?>
                <div class="travel-card"
                    data-prix_total="<?= htmlspecialchars($voyage['prix_total']) ?>"
                    data-titre="<?= htmlspecialchars(strtolower($voyage['titre'])) ?>"
                    data-date_debut="<?= htmlspecialchars($voyage['dates']['debut']) ?>"
                    data-duree="<?= isset($voyage['dates']['duree']) ? htmlspecialchars((int) filter_var($voyage['dates']['duree'], FILTER_SANITIZE_NUMBER_INT)) : 0 ?>"
                    data-nb_etapes="<?= isset($voyage['liste_etapes']) ? count($voyage['liste_etapes']) : 0 ?>"
                    data-places_disponibles="<?= htmlspecialchars($voyage['places_disponibles']) ?>"
                    data-prix_personne="<?= isset($voyage['tarification']['prix_par_personne']) ? htmlspecialchars($voyage['tarification']['prix_par_personne']) : 0 ?>"
                >
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
                        <a class="pagination-btn" href="?page=<?= $page_actuelle - 1 ?>&mot_cle=<?= urlencode($mot_cle) ?>">&laquo; Précédent</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $nombre_total_pages; $i++): ?>
                        <a class="pagination-btn <?= ($i == $page_actuelle) ? 'active' : '' ?>" href="?page=<?= $i ?>&mot_cle=<?= urlencode($mot_cle) ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page_actuelle < $nombre_total_pages): ?>
                        <a class="pagination-btn" href="?page=<?= $page_actuelle + 1 ?>&mot_cle=<?= urlencode($mot_cle) ?>">Suivant &raquo;</a>
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

<script>
document.getElementById('btn-trier').onclick = function() {
    let critere = document.getElementById('tri-critere').value;
    let ordre = document.getElementById('tri-ordre').value;

    // On ne trie que les cards affichées (page courante)
    let travelCards = Array.from(document.querySelectorAll('.travel-card'));

    travelCards.sort((a, b) => {
        let vA, vB;
        if (critere === "prix_total" || critere === "places_disponibles" || critere === "prix_personne" || critere === "nb_etapes" || critere === "duree") {
            vA = parseInt(a.dataset[critere]) || 0;
            vB = parseInt(b.dataset[critere]) || 0;
        } else if (critere === "date_debut") {
            vA = Date.parse(a.dataset.date_debut) || 0;
            vB = Date.parse(b.dataset.date_debut) || 0;
        } else if (critere === "titre") {
            vA = (a.dataset.titre || "");
            vB = (b.dataset.titre || "");
        } else {
            vA = a.dataset[critere] || "";
            vB = b.dataset[critere] || "";
        }
        if (vA < vB) return ordre === "asc" ? -1 : 1;
        if (vA > vB) return ordre === "asc" ? 1 : -1;
        return 0;
    });

    let travelsDiv = document.getElementById('travels');
    travelCards.forEach(card => travelsDiv.appendChild(card)); // Réordonne l'affichage DOM
};

document.getElementById('tri-critere').onchange =
document.getElementById('tri-ordre').onchange =
    () => document.getElementById('btn-trier').click();
</script>

</body>
</html>
