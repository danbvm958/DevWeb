<?php 
require_once 'session.php';
DemarrageSession();

// Redirection si pas admin ou SuperAdmin
if ($_SESSION['user']['type'] !== 'admin' && $_SESSION['user']['type'] !== 'SuperAdmin'){
    header("Location: accueil.php");
    exit();
}
$pdo = DemarrageSQL();

// Si SuperAdmin, voir tous les utilisateurs
if ($_SESSION['user']['type'] === 'SuperAdmin') {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Types IN ('normal', 'vip', 'bloque','admin')");
    $stmt->execute();
} else {
    // Sinon, admin NORMAL : ne voir que normal/vip/bloqué
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Types IN ('normal', 'vip', 'bloque')");
    $stmt->execute();
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Administrateur - Horage</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/Admin.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
    <style>
        
    </style>
</head>
<body>
<?php 
AfficherHeader();
?>

<h1 class="pt">Liste des utilisateurs d'Horage</h1>
<div class="container">
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Type d'utilisateur</th>
            <th>Profil</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['Nom']) ?></td>
            <td><?= htmlspecialchars($user['Prenom']) ?></td>
            <td><?= htmlspecialchars($user['Email']) ?></td>
            
            <td>
    <select class="select-type" data-email="<?= htmlspecialchars($user['Email']) ?>">
        <?php if ($_SESSION['user']['type'] === 'SuperAdmin' && $user['Types'] === 'admin'): ?>
            <option value="admin" <?= $user['Types'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
            <option value="bloque" <?= $user['Types'] === 'bloque' ? 'selected' : '' ?>>Bloqué</option>
        <?php else: ?>
            <option value="normal" <?= $user['Types'] === 'normal' ? 'selected' : '' ?>>Normal</option>
            <option value="vip" <?= $user['Types'] === 'vip' ? 'selected' : '' ?>>VIP</option>
            <option value="bloque" <?= $user['Types'] === 'bloque' ? 'selected' : '' ?>>Bloqué</option>
            
        <?php endif; ?>
    </select>
</td>

            <td>
                <a href="profil_user_admin.php?email=<?= urlencode($user['Email']) ?>" class="btn btn-profile">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

<div id="global-loader" style="display:none;">
  <div class="spinner"></div>
</div>

</body>
</html>
