<?php
session_start();
require('getapikey.php'); // Pour la connexion CY Bank

if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Traitement du paiement VIP (invisible)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vip_payment'])) {
    $transaction = bin2hex(random_bytes(12));
    $montant = "60000.00";
    $vendeur = "MI-1_A";
    $retour = "http://localhost/horage_project/retour_paiement2.php?session=".session_id();
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

    <main class="profile-container">
        <aside class="sidebar">
            <a href="profil_user.php" class="menu-btn active">Profil</a>
            <a href="profil_travel.php" class="menu-btn">Voyages prévus</a>
            <a href="#parametres" class="menu-btn">Paramètres</a>
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
</body>
</html>
