<?php
require_once 'session.php';
DemarrageSession();
require('getapikey.php'); // Pour la connexion CY Bank
$user = $_SESSION['user'];

// Traitement du paiement VIP (invisible)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vip_payment'])) {
    $transaction = bin2hex(random_bytes(12));
    $montant = "60000.00";
    $vendeur = "MI-1_A";
    $retour = "https://horage.infinityfreeapp.com/Horage_Project/retour_paiement2.php?session=".session_id();
    $api_key = getAPIKey($vendeur);
    $control = md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");
    
    $_SESSION['vip_payment'] = [
        'transaction_id' => $transaction,
        'user_id' => $user['id']
    ];
    
    // Redirection transparente vers CY Bank
    header("Location: https://www.plateforme-smc.fr/cybank/index.php?" . http_build_query([
        'transaction' => $transaction,
        'montant' => $montant,
        'vendeur' => $vendeur,
        'retour' => $retour,
        'control' => $control
    ]));
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Horage</title>
    <link rel="stylesheet" href="CSS/profil.css?v=<?= time() ?>">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
    
</head>
<body>
<?php 
    DemarrageSession();
    AfficherHeader();
?>

    <main class="profile-container">
        <!-- Bouton pour ouvrir/fermer la sidebar -->
        <button class="sidebar-toggle">☰</button>
        
        <!-- Overlay (fond semi-transparent) -->
        <div class="overlay"></div>
        <aside class="sidebar">
            <a href="profil_user.php" class="menu-btn active">Profil</a>
            <a href="profil_travel.php" class="menu-btn">Voyages prévus</a>
            <a href="logout.php" class="menu-btn logout">Se déconnecter</a>
        </aside>

        <section class="profile-content">
            <h2>Mon Profil</h2>
            <div class="profile-info">
                <img src="img_horage/profil.jpg" alt="Photo de profil" class="profile-pic">
                <div class="info">
                    <p><strong>Username :</strong> <span data-field="username"><?php echo htmlspecialchars($user['username']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Nom :</strong> <span data-field="nom"><?php echo htmlspecialchars($user['nom']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Prenom :</strong> <span data-field="prenom"><?php echo htmlspecialchars($user['prenom']); ?></span> <button class="edit-btn">✏️</button></p>
                    <p><strong>Email :</strong> <span data-field="email"><?php echo htmlspecialchars($user['email']); ?></span> <button class="edit-btn">✏️</button></p>

                    <div class="vip-container">
                        <?php if ($user['type'] === 'vip'): ?>
                            <p class="vip-status">✓ Vous êtes VIP</p>
                        <?php else: ?>
                            <form method="POST" style="display: inline;">
                                <button type="submit" name="vip_payment" class="vip-btn">Devenir VIP - 60000€/mois</button>
                            </form>
                            <p style="margin-top: 8px; font-size: 0.9em; color: #666;">Accédez aux avantages exclusifs</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
        </section>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
    <script src="js/profil_user.js"></script>
    <script src="js/sidebar.js"></script>
</body>
</html>
