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
$js_voyages = json_encode($voyages_filtres);
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
            <li><a href="Recherche.php" class="a1">Reserver</a></li>
            <?php
            $pageProfil = 'login.php';
            if (isset($_SESSION['user'])) {
                $type = $_SESSION['user']['type'];
                $pageProfil = ($type == "admin") ? "admin.php" : "profil.php";
            }
            ?>
            <li><a href="<?= $pageProfil ?>" class="a1">Profil</a></li>
        </ul>
    </div>
</header>

<div class="mainRecherche">
    <form action="Recherche.php" method="POST" class="barre-recherche">
        <input type="text" name="mot_cle" placeholder="Entrez un mot-clé..." value="<?= htmlspecialchars($mot_cle) ?>">
        <button type="submit">Rechercher</button>
    </form>
</div>

<main>
    <h2 class="tv">Résultats des voyages 🔍</h2>

    <!-- Module de tri étendu -->
    <div class="block-tri">
        <label>Trier par : </label>
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

    <div id="voyages-root">
        <!-- Le JS injecte ici les résultats paginés ! -->
    </div>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

<script>
// Toutes les données des voyages du résultat (depuis PHP, recherches comprise)
let voyages = <?= $js_voyages ?>;
let voyagesParPage = 3;
let pageActuelle = 1;
let motCle = "<?= htmlspecialchars($mot_cle, ENT_QUOTES) ?>";

function afficherVoyages() {
    const root = document.getElementById('voyages-root');
    root.innerHTML = "";

    if (voyages.length === 0) {
        root.innerHTML = `<p>Aucun voyage trouvé pour "${motCle}".</p>`;
        return;
    }

    // Pagination
    const nombre_total_pages = Math.ceil(voyages.length / voyagesParPage);
    if (pageActuelle > nombre_total_pages) pageActuelle = 1;
    const offset = (pageActuelle - 1) * voyagesParPage;
    const voyagesPage = voyages.slice(offset, offset + voyagesParPage);

    // Affichage des voyages
    let html = `<div class="travels">`;
    voyagesPage.forEach(voyage => {
        html += `
        <div class="travel-card"
            data-prix="${voyage.prix_total}"
            data-titre="${encodeURIComponent((voyage.titre || ""))}"
            data-date="${voyage.dates && voyage.dates.debut ? voyage.dates.debut : ""}">
            <div class="price">${voyage.prix_total}€</div>
            <h3>${voyage.titre}</h3>
            <p>Date: ${voyage.dates && voyage.dates.debut ? voyage.dates.debut : ""}</p>
            <p>Description: ${voyage.description}</p>
            <a href="voyages_details.php?id=${encodeURIComponent(voyage.id_voyage)}" class="btn">Voir plus</a>
        </div>`;
    });
    html += `</div>`;

    // Pagination boutons
    if (nombre_total_pages > 1) {
        html += `<div class="pagination">`;
        if (pageActuelle > 1)
            html += `<button class="pagination-btn" onclick="changerPage(${pageActuelle-1})">&laquo; Précédent</button>`;
        for (let i = 1; i <= nombre_total_pages; i++)
            html += `<button class="pagination-btn${i===pageActuelle?' active':''}" onclick="changerPage(${i})">${i}</button>`;
        if (pageActuelle < nombre_total_pages)
            html += `<button class="pagination-btn" onclick="changerPage(${pageActuelle+1})">Suivant &raquo;</button>`;
        html += `</div>`;
    }

    root.innerHTML = html;
}

function changerPage(page) {
    pageActuelle = page;
    afficherVoyages();
}

// Tri JS sur tous les voyages filtrés pour la recherche
document.getElementById('btn-trier').onclick = function() {
    let critere = document.getElementById('tri-critere').value;
    let ordre = document.getElementById('tri-ordre').value;

    voyages.sort((a, b) => {
        let vA, vB;
        if (critere === "prix_total") {
            vA = parseFloat(a.prix_total) || 0;
            vB = parseFloat(b.prix_total) || 0;
        } else if (critere === "date_debut") {
            vA = Date.parse(a.dates && a.dates.debut ? a.dates.debut : "") || 0;
            vB = Date.parse(b.dates && b.dates.debut ? b.dates.debut : "") || 0;
        } else if (critere === "titre") {
            vA = (a.titre||"").toLowerCase();
            vB = (b.titre||"").toLowerCase();
        } else if (critere === "duree") {
            vA = a.dates && a.dates.duree ? parseInt(a.dates.duree) || parseInt((a.dates.duree+"").replace(/\D/g,"")) : 0;
            vB = b.dates && b.dates.duree ? parseInt(b.dates.duree) || parseInt((b.dates.duree+"").replace(/\D/g,"")) : 0;
        } else if (critere === "nb_etapes") {
            vA = (a.liste_etapes && a.liste_etapes.length) || 0;
            vB = (b.liste_etapes && b.liste_etapes.length) || 0;
        } else if (critere === "places_disponibles") {
            vA = a.places_disponibles || 0;
            vB = b.places_disponibles || 0;
        } else if (critere === "prix_personne") {
            vA = (a.tarification && a.tarification.prix_par_personne) || 0;
            vB = (b.tarification && b.tarification.prix_par_personne) || 0;
        } else {
            vA = a[critere] || "";
            vB = b[critere] || "";
        }
        if (vA < vB) return ordre === "asc" ? -1 : 1;
        if (vA > vB) return ordre === "asc" ? 1 : -1;
        return 0;
    });

    pageActuelle = 1;
    afficherVoyages();
};

document.getElementById('tri-critere').onchange =
document.getElementById('tri-ordre').onchange =
    () => document.getElementById('btn-trier').click();

// Afficher la 1ère page au chargement
document.addEventListener("DOMContentLoaded", afficherVoyages);
</script>
</body>
</html>
