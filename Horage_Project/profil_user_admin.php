<?php
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();

// Récupérer l'email depuis l'URL si on vient de l'admin
$userEmail = $_GET['email'] ?? null;

try {
    if ($userEmail) {
        // Mode admin : récupérer le profil utilisateur par email
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Email = :email");
        $stmt->execute(['email' => $userEmail]);
        $displayUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$displayUser) {
            header("Location: admin.php");
            exit();
        }
    } elseif (isset($_SESSION['user_id'])) {
        // Mode utilisateur connecté
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $displayUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$displayUser) {
            header("Location: login.php");
            exit();
        }
    } else {
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Horage</title>
    <link rel="stylesheet" href="CSS/profil.css">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
    <?php AfficherHeader(); ?>

    <main class="profile-container">
        <section class="profile-content">
            <h2>Profil <?= $userEmail ? 'de '.htmlspecialchars($displayUser['Prenom']) : 'Mon Profil' ?></h2>
            
            <div class="profile-info">
                <img src="img_horage/profil.jpg" alt="Photo de profil" class="profile-pic">
                <div class="info">
                    <p><strong>Nom :</strong> <?= htmlspecialchars($displayUser['Nom']) ?></p>
                    <p><strong>Prénom :</strong> <?= htmlspecialchars($displayUser['Prenom']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($displayUser['Email']) ?></p>
                    <p><strong>Date de naissance :</strong> <?= htmlspecialchars($displayUser['Anniversaire']) ?></p>
                    <p><strong>Type :</strong> <?= htmlspecialchars($displayUser['Types']) ?></p>
                </div>
            </div>

            <?php
            $voyages = []; 
            ?>

            <?php if (!empty($voyages)): ?>
                <h3>Historique des voyages</h3>
                <div class="travel-history">
                    <?php foreach ($voyages as $voyage): ?>
                        <div class="voyage">
                            <h4><?= htmlspecialchars($voyage['voyage_titre']) ?></h4>
                            <p>Date: <?= htmlspecialchars($voyage['date_achat']) ?></p>
                            <p>Prix: <?= htmlspecialchars($voyage['montant']) ?> €</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site est protégé par les lois sur la propriété intellectuelle.</p>
    </footer>
</body>
</html>
