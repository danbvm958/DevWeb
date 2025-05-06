<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once 'gestion_panier.php';

// Charger les données de l'utilisateur
$utilisateurs = json_decode(file_get_contents('data/utilisateur.json'), true);
$utilisateur = null;

foreach ($utilisateurs as $u) {
    if ($u['email'] === $_SESSION['user']['email']) {
        $utilisateur = $u;
        break;
    }
}

// Gestion du panier
$panier = $utilisateur['panier'] ?? [];

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'supprimer':
            if (isset($_POST['voyage_id'])) {
                supprimerVoyageDuPanier($_SESSION['user']['email'], $_POST['voyage_id']);
            }
            break;
        case 'vider':
            viderPanier($_SESSION['user']['email']);
            break;
    }
    
    // Rafraîchir la page pour éviter resoumission
    header('Location: panier.php');
    exit();
}

// Calcul du total
$totalGeneral = 0;
foreach ($panier as $voyage) {
    $totalGeneral += $voyage['prix_total'];
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
                <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['type'] == "vip"):?>
                    <li><a href="profil_vip.php" class="a1">Profil</a></li>
                <?php else: ?>
                    <li><a href="profil_user.php" class="a1">Profil</a></li>
                <?php endif;?>
                
            <?php else: ?>
                <li><a href="login.php" class="a1">Connexion</a></li>
            <?php endif; ?>
                <li><a href="accueil.php" class="a1">contacts</a></li>
                <li><a href="panier.php" class="a1">Panier</a></li>
            </ul>
        </div>
    </header>

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
                    <?php foreach ($panier as $voyage): ?>
                        <div class="voyage-item">
                            <h3 class="voyage-titre"><?= htmlspecialchars($voyage['voyage_titre']) ?></h3>
                            <div class="voyage-info">
                                <div class="voyage-details">
                                    <p>Nombre de personnes: <?= $voyage['nombre_personnes'] ?> (Adultes: <?= $voyage['nb_adultes'] ?>, Enfants: <?= $voyage['nb_enfants'] ?>)</p>
                                    <ul class="options-list">
                                        <?php foreach ($voyage['options_choisies'] as $etape => $options): ?>
                                            <?php foreach ($options as $option): ?>
                                                <li class="option-item">
                                                    <span class="option-nom"><?= htmlspecialchars($option['nom']) ?>: </span>
                                                    <span class="option-choix"><?= htmlspecialchars($option['choix']) ?></span>
                                                    <span class="option-prix"><?= $option['prix']?> €</span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="voyage-prix">
                                    <?= number_format($voyage['prix_total'] / 100, 4, ',', ' ') ?> €
                                </div>
                            </div>
                            <div class="actions-buttons">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="voyage_id" value="<?= $voyage['voyage_id'] ?>">
                                    <button type="submit" class="btn btn-supprimer">Supprimer</button>
                                </form>
    

                                
                                <form method="post" action="vers_CyBank.php" style="display: inline;">
                                    <input type="hidden" name="voyage_id" value="<?= $voyage['voyage_id'] ?>">
                                    <input type="hidden" name="nombre_personnes" value="<?= $voyage['nombre_personnes'] ?>">
                                    <input type="hidden" name="prix_total" value="<?= $voyage['prix_total'] ?>">
                                    <input type="hidden" name="voyage_titre" value="<?= htmlspecialchars($voyage['voyage_titre']) ?>">
                                    <button type="submit" class="btn btn-payer">Payer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="total-panier">
                        Total du panier: <?= number_format($totalGeneral / 100, 2, ',', ' ') ?> €
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