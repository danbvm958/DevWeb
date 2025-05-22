<?php 
// On inclut le fichier de gestion des sessions
require_once 'session.php';
// On démarre la connexion SQL
$pdo = DemarrageSQL();
// On démarre la session
DemarrageSession();

// On vérifie si l'utilisateur est connecté
if (VerificationConnexion() == 1) {
    // On récupère le nom d'utilisateur depuis la session
    $username = $_SESSION['user']['username'];

    // On prépare et exécute la requête pour récupérer l'ID de l'utilisateur
    $stmt = $pdo->prepare("SELECT Id FROM utilisateur WHERE NomUtilisateur = ?");
    $stmt->execute([$username]);
    $Idutilisateur = $stmt->fetch(PDO::FETCH_ASSOC)['Id'];
    $typeUtilisateur = $_SESSION['user']['type'];
    
    // On récupère les voyages payés par cet utilisateur
    $stmt = $pdo->prepare("SELECT * FROM voyage_payee WHERE IdUtilisateur = ?");
    $stmt->execute([$Idutilisateur]);
    $Data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // On prépare une requête pour récupérer les détails des voyages
    $stmt = $pdo->prepare("SELECT * FROM voyages WHERE IdVoyage = ?");

    // Si on ne trouve pas l'utilisateur, on affiche une erreur
    if (!$Idutilisateur) {
        die("Erreur : Utilisateur non trouvé dans utilisateur.json");
    }

} else {
    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
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
    <!-- On inclut les feuilles de style avec un paramètre de version pour éviter le cache -->
    <link rel="stylesheet" href="CSS/profil.css?v=<?= time() ?>">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <!-- On inclut les scripts JavaScript -->
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
<?php 
    // On inclut à nouveau le fichier de session et on affiche le header
    require_once 'session.php';
    AfficherHeader();
?>

<main class="profile-container">
    <!-- Bouton pour afficher/masquer la sidebar -->
    <button class="sidebar-toggle">☰</button>
    <div class="overlay"></div>
    <aside class="sidebar">
            <a href="profil_user.php" class="menu-btn">Profil</a></li>
        <a href="profil_travel.php" class="menu-btn active">Voyages prévus</a>
        <!-- Formulaire de déconnexion -->
        <form action="logout.php" method="post">
            <button type="submit" class="menu-btn logout">Se déconnecter</button>
        </form>
    </aside>

    <section class="profile-content">
        <h2>Mes Voyages</h2>
        
        <div class="travels">
            <?php if (!empty($Data)): ?>
                <!-- On boucle sur chaque voyage réservé -->
                <?php foreach ($Data as $info): ?>
                    <?php 
                        // On exécute la requête pour récupérer les détails du voyage
                        $stmt->execute([$info['IdVoyage']]);
                        $voyage=$stmt->fetch(PDO::FETCH_ASSOC);
                        // On calcule le nombre total de personnes
                        $NbPersonne = $info['NbAdultes'] + $info['NbEnfants'];
                        // On stocke les infos du voyage dans la session
                        $_SESSION['user']['voyages'][] = $info;
                    ?>
                    <div class="travel-card">
                        <!-- On affiche les informations du voyage -->
                        <div class="price">
                            <?= htmlspecialchars(number_format($info['Prix'], 2)) ?>€
                           
                        </div>


                        <h3><?= htmlspecialchars($voyage['Titre']) ?></h3>
                        <p>Date d'achat: <?= htmlspecialchars($info['DatePaiement']) ?></p>
                        <p>Nombre de personnes: <?= htmlspecialchars($NbPersonne) ?></p>
                        <!-- Lien vers la page de détails du voyage -->
                        <a href="recapitulatif2.php?id_voyage=<?= urlencode($info['IdVoyage']) ?>" class="btn">Voir plus</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Message si aucun voyage n'est réservé -->
                <p>Aucun voyage réservé pour l'instant.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    <!-- Script pour la gestion de la sidebar -->
    <script src="js/sidebar.js"></script>
</footer>

</body>
</html>