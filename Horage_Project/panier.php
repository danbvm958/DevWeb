<?php
require_once 'session.php';
DemarrageSession();
// Initialiser le panier depuis la session
$panier = isset($_SESSION['pending_payment']) ? $_SESSION['pending_payment'] : [];

// --- Début logique VIP (Vérifie 'type') ---
// Vérifie si l'utilisateur est connecté et a le type 'vip'
$isVIP = isset($_SESSION['user']['type']) && $_SESSION['user']['type'] === 'vip';
$discountRate = 0.20; // 20% de réduction
// --- Fin logique VIP ---


// Calculer le total final (somme des prix réduits pour VIP, originaux pour non-VIP)
$totalFinal = 0;
foreach ($panier as $index => $voyage) {
    $prixItem = $voyage['prix_total']; // Prix de base de l'item dans le panier

    // Appliquer la réduction PAR ITEM si l'utilisateur est VIP
    if ($isVIP) {
        $prixItem = $voyage['prix_total'] * (1 - $discountRate);
        // Optionnel : stocker le prix réduit temporairement dans le tableau pour l'affichage
        // Attention : ne pas modifier $_SESSION['pending_payment'] directement ici si vous voulez conserver le prix original dans la session.
        // On va plutôt calculer le prix réduit pour l'affichage dans la boucle d'affichage HTML.
    }
    
    $totalFinal += $prixItem;
}


// Traitement des actions (vider ou supprimer)
// Note : Il est souvent préférable de traiter les actions POST *avant* d'afficher quoi que ce soit.
// J'ai déplacé ce bloc ici.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'vider':
                $_SESSION['pending_payment'] = [];
                // Rediriger pour éviter la soumission multiple en cas de rafraîchissement
                header('Location: panier.php');
                exit();
                
            case 'supprimer':
                if (isset($_POST['index_a_supprimer'])) {
                    $index = $_POST['index_a_supprimer'];
                    if (isset($_SESSION['pending_payment'][$index])) {
                        unset($_SESSION['pending_payment'][$index]);
                        $_SESSION['pending_payment'] = array_values($_SESSION['pending_payment']);
                    }
                    header('Location: panier.php');
                    exit();
                }
                break;
        }
    }
}


// Fonction pour formater l'affichage des prix (avec 2 décimales)
function afficherPrixPanier($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
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
    <style>
    /* Ajoutez ou assurez-vous que ces classes existent dans votre CSS/panier.css */
    .texte-barre {
      text-decoration: line-through;
      margin-right: 10px; /* Espace entre le prix barré et le prix réduit */
      color: #999; /* Couleur grise pour le prix original barré */
    }
    .prix-reduit-item {
        color: green; /* Ou une autre couleur pour le prix réduit */
        font-weight: bold;
    }
    .total-avec-reduction {
        color: green; /* Couleur pour le total réduit */
        font-weight: bold;
    }
     .reduction-info {
        font-size: 0.9em;
        color: green;
        margin-top: 5px;
    }
    </style>
</head>
<body>
    <?php 
        // Assurez-vous que AfficherHeader() est défini (probablement dans session.php ou un autre include)
        if (function_exists('AfficherHeader')) {
             AfficherHeader();
        } else {
            echo "<!-- AfficherHeader() function not found -->";
            // Vous pouvez mettre ici un simple header HTML si la fonction n'est pas disponible
        }
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
                        <?php
                            $prixOriginalItem = $voyage['prix_total'];
                            $prixReduitItem = $prixOriginalItem * (1 - $discountRate);
                        ?>
                        <div class="voyage-item">
                            <!-- Afficher le titre du voyage - assurez-vous que voyage_titre est bien dans votre session data -->
                            <h3 class="voyage-titre"><?= htmlspecialchars($voyage['voyage_titre'] ?? 'Voyage inconnu') ?></h3>
                            <div class="voyage-info">
                                <div class="voyage-details">
                                    <p>Nombre de personnes: <?= $voyage['nombre_personnes'] ?? 0 ?> (Adultes: <?= $voyage['nb_adultes'] ?? 0 ?>, Enfants: <?= $voyage['nb_enfants'] ?? 0 ?>)</p>
                                    <?php if (!empty($voyage['options_choisies'])): ?>
                                        <ul class="options-list">
                                            <?php foreach ($voyage['options_choisies'] as $etapeId => $options): // Utiliser l'ID de l'étape comme clé ?>
                                                <?php foreach ($options as $option): ?>
                                                    <li class="option-item">
                                                        <span class="option-nom"><?= htmlspecialchars($option['nom'] ?? 'Option inconnue') ?>: </span>
                                                        <span class="option-choix"><?= htmlspecialchars($option['choix'] ?? '') ?></span>
                                                        <!-- Option price might need recalculation based on number of people if stored per person -->
                                                        <!-- Assuming $option['prix'] is per person and needs to be multiplied by total people -->
                                                        <span class="option-prix"><?= afficherPrixPanier(($option['prix'] ?? 0) * ($voyage['nombre_personnes'] ?? 1)) ?></span>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="voyage-prix">
                                    <?php if ($isVIP): ?>
                                        <!-- Affiche le prix original barré si l'utilisateur est VIP -->
                                        <span class="texte-barre"><?= afficherPrixPanier($prixOriginalItem) ?></span>
                                        <!-- Affiche le prix réduit par article -->
                                        <span class="prix-reduit-item"><?= afficherPrixPanier($prixReduitItem) ?></span>
                                    <?php else: ?>
                                        <!-- Affiche le prix normal de l'article -->
                                        <?= afficherPrixPanier($prixOriginalItem) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="actions-buttons">
                                <!-- Formulaire de suppression (utilise l'index du voyage dans le panier, pas l'ID du voyage de la DB) -->
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="supprimer">
                                    <!-- IMPORTANT: Utilisez l'INDEX du tableau $panier pour supprimer le bon article -->
                                    <input type="hidden" name="index_a_supprimer" value="<?= $index ?>">
                                    <button type="submit" class="btn btn-supprimer">Supprimer</button>
                                </form>


                                <!-- Formulaire de paiement pour cet article spécifique (si vous voulez payer article par article) -->
                                <!-- Si le paiement se fait toujours sur le total, ce bouton peut être supprimé ou modifié -->
                                <!-- Laissez-le si chaque article est un paiement potentiel séparé -->
                                <form method="post" action="vers_CyBank.php" style="display: inline;">
                                     <input type="hidden" name="action" value="payer_item"> <!-- Action spécifique si paiement par item -->
                                     <input type="hidden" name="voyage_index" value="<?= $index ?>"> <!-- Index de l'item à payer -->
                                    <!-- IMPORTANT: Passez le prix réduit si VIP, original sinon -->
                                     <input type="hidden" name="prix_total" value="<?= $isVIP ? $prixReduitItem : $prixOriginalItem ?>">
                                    <!-- ... autres champs nécessaires pour le paiement de CET article ... -->
                                     <button type="submit" class="btn btn-payer">Payer cet article</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Section Totaux -->
                    <div class="total-panier">
                        Total du panier:
                        <!-- Affiche le total final, qui est déjà la somme des prix réduits si VIP -->
                         <span class="total-avec-reduction"><?= afficherPrixPanier($totalFinal) ?></span>
                         <?php if ($isVIP && $totalFinal > 0): ?>
                             <div class="reduction-info">Réduction VIP (20%) appliquée par article !</div>
                         <?php endif; ?>
                    </div>

                    <!-- Formulaire pour vider le panier -->
                     <form method="post" style="display: inline; margin-right: 10px;">
                        <input type="hidden" name="action" value="vider">
                        <button type="submit" class="btn btn-vider">Vider le panier</button>
                    </form>


                <?php endif; // Fin de l'if empty($panier) ?>
            </div>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
</body>
</html>
