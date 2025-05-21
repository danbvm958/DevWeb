<?php
require_once 'session.php';
DemarrageSession();
// Initialiser le panier depuis la session
$panier = isset($_SESSION['pending_payment']) ? $_SESSION['pending_payment'] : [];

// Calculer le total général
$totalGeneral = 0;
foreach ($panier as $voyage) {
    $totalGeneral += $voyage['prix_total'];
}

// Traitement des actions (vider ou supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'vider':
                $_SESSION['pending_payment'] = [];
                $panier = [];
                $totalGeneral = 0;
                break;
                
            case 'supprimer':
                if (isset($_POST['voyage_id'])) {
                    foreach ($panier as $key => $voyage) {
                        if ($voyage['voyage_id'] == $_POST['voyage_id']) {
                            unset($_SESSION['pending_payment'][$key]);
                            // Réindexer le tableau après suppression
                            $_SESSION['pending_payment'] = array_values($_SESSION['pending_payment']);
                            header('Location: panier.php');
                            exit();
                        }
                    }
                }
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon Panier - Horage</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Creepster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/panier.css?v=<?= time() ?>">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
    <?php 
        
        AfficherHeader();
    ?>
    <main>
        <div class="panier-container">
            <h2>Mon Panier</h2>
            
            <div id="panier-content">
                <?php if (empty($panier)): ?>
                    <div class="panier-vide">
                        <p>Votre panier est vide</p>
                        <p><a href="Reserve.php" class="a2">Découvrez nos offres effrayantes</a></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($panier as $index => $voyage): ?>
                        <div class="voyage-item">
                            <h3 class="voyage-titre"><?= htmlspecialchars($voyage['voyage_titre'] ?? '') ?></h3>
                            <div class="voyage-info">
                                <div class="voyage-details">
                                    <p>Nombre de personnes: <?= $voyage['nombre_personnes'] ?> (Adultes: <?= $voyage['nb_adultes'] ?>, Enfants: <?= $voyage['nb_enfants'] ?>)</p>
                                    <?php if (!empty($voyage['options_choisies'])): ?>
                                        <ul class="options-list">
                                            <?php foreach ($voyage['options_choisies'] as $etape => $options): ?>
                                                <?php foreach ($options as $option): ?>
                                                    <li class="option-item">
                                                        <span class="option-nom"><?= htmlspecialchars($option['nom'] ?? '') ?>: </span>
                                                        <span class="option-choix"><?= htmlspecialchars($option['choix'] ?? '') ?></span>
                                                        <span class="option-prix"><?= $option['prix'] ?? 0 ?> €</span>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="voyage-prix">
                                    <?= number_format($voyage['prix_total'], 2, ',', ' ') ?> €
                                </div>
                            </div>
                            <div class="actions-buttons">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="voyage_id" value="<?= $voyage['voyage_id'] ?>">
                                    <button type="submit" class="btn btn-supprimer">Supprimer</button>
                                </form>

                                <form method="post" action="vers_CyBank.php" style="display: inline;">
                                    <input type="hidden" name="voyage_index" value="<?= $index ?>">
                                    <input type="hidden" name="voyage_id" value="<?= $voyage['voyage_id'] ?>">
                                    <input type="hidden" name="nombre_personnes" value="<?= $voyage['nombre_personnes'] ?>">
                                    <input type="hidden" name="prix_total" value="<?= $voyage['prix_total'] ?>">
                                    <button type="submit" class="btn btn-payer">Payer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="total-panier">
                        Total du panier: <?= number_format($totalGeneral, 2, ',', ' ') ?> €
                    </div>
                    <div class="panier-actions">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="action" value="vider">
                            <button type="submit" class="btn btn-vider-panier">Vider le panier</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
</body>
</html>