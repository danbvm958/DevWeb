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
        <div id="voyagesContainer" class="travels">
            <?php if (!empty($voyages)) : ?>
                <?php foreach ($voyages as $index => $voyage) : 
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
                        data-pays="<?= htmlspecialchars($voyage['Pays'] ?? '') ?>">

                        <div class="price"><?= htmlspecialchars($voyage['PrixBase']) ?>€</div>
                        <h3><?= htmlspecialchars($voyage['Titre']) ?></h3>
                        <p><?= htmlspecialchars($voyage['Description']) ?></p>
                        <p><strong>Durée :</strong> <?= $duree ?> jour(s)</p>
                        <?php if (!empty($voyage['Pays'])): ?>
                            <p><strong>Pays :</strong> <?= htmlspecialchars($voyage['Pays']) ?></p>
                        <?php endif; ?>
                        <p><strong>Période :</strong> <?= htmlspecialchars($voyage['DateDebut']) ?> - <?= htmlspecialchars($voyage['DateFin']) ?></p>
                        <a href="voyages_details.php?id=<?= urlencode($voyage['IdVoyage']) ?>" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun voyage disponible.</p>
            <?php endif; ?>
        </div>
        <div id="pagination-controls" class="pagination"></div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

    <script>
    const PAGE_SIZE = 3;
    let currentPage = 1;

    function getAllCards() {
        return Array.from(document.querySelectorAll('.travel-card'));
    }

    function updatePagination() {
        const container = document.getElementById('voyagesContainer');
        const cards = getAllCards();
        const pagination = document.getElementById('pagination-controls');
        const nPages = Math.ceil(cards.length / PAGE_SIZE) || 1;

        if(currentPage > nPages) currentPage = nPages;
        if(currentPage < 1) currentPage = 1;

        getAllCards().forEach(card => card.style.display = "none");
        cards.forEach((card, idx) => {
            card.style.display = (idx >= (currentPage-1)*PAGE_SIZE && idx < currentPage*PAGE_SIZE) ? "" : "none";
        });

        pagination.innerHTML = '';
        if(cards.length === 0) return;

        const prevBtn = document.createElement("a");
        prevBtn.textContent = "« Précédent";
        prevBtn.classList.add('pagination-btn');
        if(currentPage === 1){
            prevBtn.classList.add('disabled');
        } else {
            prevBtn.href = "#";
            prevBtn.onclick = (e) => { e.preventDefault(); currentPage--; updatePagination(); };
        }
        pagination.appendChild(prevBtn);

        const nMaxPagesShow = 3;
        let first = Math.max(1, currentPage - Math.floor(nMaxPagesShow/2));
        let last = Math.min(nPages, first + nMaxPagesShow - 1);
        first = Math.max(1, last - nMaxPagesShow + 1);

        for(let i=first; i<=last; ++i) {
            let b = document.createElement("a");
            b.textContent = i;
            b.classList.add('pagination-btn');
            if(i === currentPage) {
                b.classList.add('active');
            } else {
                b.href = "#";
                b.onclick = (e) => { e.preventDefault(); currentPage = i; updatePagination(); };
            }
            pagination.appendChild(b);
        }

        const nextBtn = document.createElement("a");
        nextBtn.textContent = "Suivant »";
        nextBtn.classList.add('pagination-btn');
        if(currentPage === nPages){
            nextBtn.classList.add('disabled');
        } else {
            nextBtn.href = "#";
            nextBtn.onclick = (e) => { e.preventDefault(); currentPage++; updatePagination(); };
        }
        pagination.appendChild(nextBtn);
    }

    window.addEventListener('DOMContentLoaded', function() {
        updatePagination();
    });
    </script>
</body>
</html>