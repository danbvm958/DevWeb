<?php 
session_start();
//Connection 
$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];

    $stmt = $pdo->prepare("SELECT Id FROM  utilisateur WHERE NomUtilisateur = ?");
    $stmt->execute([$username]);
    $Idutilisateur = $stmt->fetch(PDO::FETCH_ASSOC)['Id'];
    $stmt = $pdo->prepare("SELECT * FROM  voyage_payee WHERE IdUtilisateur = ?");
    $stmt->execute([$Idutilisateur]);
    $Data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT * FROM  voyages WHERE IdVoyage = ?");

    if (!$Idutilisateur) {
        die("Erreur : Utilisateur non trouvé dans utilisateur.json");
    }

} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Voyages - Horage</title>
    <link rel="stylesheet" href="CSS/profil.css">
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
            <li><a href="presentation.php" class="a1">Présentation</a></li>
            <li><a href="Reserve.php" class="a1">Nos offres</a></li>
            <li><a href="Recherche.php" class="a1">Réserver</a></li>

            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['type'] == "vip"):?>
                    <li><a href="profil_vip.php" class="a1">Profil</a></li>
                <?php else: ?>
                    <li><a href="profil_user.php" class="a1">Profil</a></li>
                <?php endif;?>
                
            <?php else: ?>
                <li><a href="login.php" class="a1">Connexion</a></li>
            <?php endif; ?>

            <li><a href="contact.php" class="a1">Contacts</a></li>
            <li><a href="panier.php" class="a1">Panier</a></li>
        </ul>
    </div>
</header>

<main class="profile-container">
    <aside class="sidebar">
        <?php if ($_SESSION['user']['type'] == "vip"):?>
            <a href="profil_vip.php" class="menu-btn">Profil</a></li>
        <?php else: ?>
            <a href="profil_user.php" class="menu-btn">Profil</a></li>
        <?php endif;?>
        <a href="profil_travel.php" class="menu-btn active">Voyages prévus</a>
        <form action="logout.php" method="post">
            <button type="submit" class="menu-btn logout">Se déconnecter</button>
        </form>
    </aside>

    <section class="profile-content">
        <h2>Mes Voyages</h2>
        
        <div class="travels">
            <?php if (!empty($Data)): ?>
                <?php foreach ($Data as $info): ?>
                    <?php 
                        $stmt->execute([$info['IdVoyage']]);
                        $voyage=$stmt->fetch(PDO::FETCH_ASSOC);
                        $NbPersonne = $info['NbAdultes'] + $info['NbEnfants'];
                        $_SESSION['user']['voyages'][] = $info;
                    ?>
                    <div class="travel-card">
                        <div class="price">€ <?= htmlspecialchars($info['Prix']) ?></div>
                        <h3><?= htmlspecialchars($voyage['Titre']) ?></h3>
                        <p>Date d'achat: <?= htmlspecialchars($info['DatePaiement']) ?></p>
                        <p>Nombre de personnes: <?= htmlspecialchars($NbPersonne) ?></p>
                        <a href="recapitulatif2.php?id_voyage=<?= urlencode($info['IdVoyage']) ?>" class="btn">Voir plus</a>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun voyage réservé pour l'instant.</p>
            <?php endif; ?>
        </div>

    </section>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

</body>
</html>
