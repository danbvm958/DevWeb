<?php 
    require_once 'session.php';
    DemarrageSession();
    if ($_SESSION['user']['type'] !== 'admin'){
        header(Location : "accueil.php");
    }
    $pdo = DemarrageSQL();
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Types != ?");
    $stmt->execute(["admin"]);
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
        .en-attente {
            background-color: #bbb !important;
            color: #fff !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }
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
            <th>VIP</th>
            <th>Bloqué</th>
            <th>Profil</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <?php if ($user['Types'] !== 'admin'): ?>
            <tr>
                <td><?= htmlspecialchars($user['Nom']) ?></td>
                <td><?= htmlspecialchars($user['Prenom']) ?></td>
                <td><?= htmlspecialchars($user['Email']) ?></td>
                <td>
                    <button 
                        class="btn btn-toggle-vip <?= ($user['Types']=='vip') ? 'btn-vip' : 'btn-blocked' ?>" 
                        data-email="<?= htmlspecialchars($user['Email']) ?>"
                        data-type="<?= htmlspecialchars($user['Types']) ?>">
                        <?= ($user['Types'] == 'vip') ? 'Oui' : 'Non' ?>
                    </button>
                </td>
                <td>
                    <button 
                        class="btn btn-toggle-bloc <?= (!empty($user['bloque']) && $user['bloque']) ? 'btn-blocked' : 'btn-vip' ?>" 
                        data-email="<?= htmlspecialchars($user['Email']) ?>"
                        data-bloque="<?= !empty($user['bloque']) ? 'oui' : 'non' ?>">
                        <?= (!empty($user['bloque']) && $user['bloque']) ? 'Oui' : 'Non' ?>
                    </button>
                </td>
                <td>
                    <a href="profil_user_admin.php?email=<?= urlencode($user['Email']) ?>" class="btn btn-profile">Voir</a>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>

</body>
</html>
