<?php
session_start();

$jsonFile = 'data/utilisateur.json';
$jsonData = file_get_contents($jsonFile);
$users = json_decode($jsonData, true);

if ($users === null && json_last_error() !== JSON_ERROR_NONE) {
    die("Erreur lors de la lecture du fichier JSON: " . json_last_error_msg());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Page Administrateur - Horage</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/themeSwitcher.js" defer></script>
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
            <?php
            $pageProfil = 'login.php';
            if (isset($_SESSION['user'])) {
                $typeUser = $_SESSION['user']['type'];
                $pageProfil = match ($typeUser) {
                    'admin'  => 'profil_admin.php',
                    'normal' => 'profil_user.php',
                    default  => 'profil_vip.php',
                };
            }
            ?>
            <li><a href="<?= $pageProfil ?>" class="a1">Mon Profil</a></li>
            <li><a href="panier.php" class="a1">Panier</a></li>
        </ul>
    </div>
</header>

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
            <?php if ($user['type'] !== 'admin'): ?>
            <tr>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['prenom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <button 
                        class="btn btn-toggle-vip <?= ($user['type']=='vip') ? 'btn-vip' : 'btn-blocked' ?>" 
                        data-email="<?= htmlspecialchars($user['email']) ?>"
                        data-type="<?= htmlspecialchars($user['type']) ?>">
                        <?= ($user['type'] == 'vip') ? 'Oui' : 'Non' ?>
                    </button>
                </td>
                <td>
                    <button 
                        class="btn btn-toggle-bloc <?= (!empty($user['bloque']) && $user['bloque']) ? 'btn-blocked' : 'btn-vip' ?>" 
                        data-email="<?= htmlspecialchars($user['email']) ?>"
                        data-bloque="<?= !empty($user['bloque']) ? 'oui' : 'non' ?>">
                        <?= (!empty($user['bloque']) && $user['bloque']) ? 'Oui' : 'Non' ?>
                    </button>
                </td>
                <td>
                    <a href="profil_user_admin.php?email=<?= urlencode($user['email']) ?>" class="btn btn-profile">Voir</a>
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

<!-- JS POUR SIMULER L'ATTENTE SUR LES BOUTONS VIP ET BLOQUÉ -->
<script>
document.querySelectorAll('.btn-toggle-vip').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var email = this.dataset.email;
        var btnRef = this;

        btnRef.disabled = true;
        btnRef.classList.add('en-attente');

        setTimeout(function() {
            fetch('toggle_vip.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btnRef.textContent = data.newType === "vip" ? "Oui" : "Non";
                    btnRef.dataset.type = data.newType;
                    if (data.newType === "vip") {
                        btnRef.classList.add('btn-vip');
                        btnRef.classList.remove('btn-blocked');
                    } else {
                        btnRef.classList.remove('btn-vip');
                        btnRef.classList.add('btn-blocked');
                    }
                } else {
                    alert("Impossible de modifier le statut VIP !");
                }
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            })
            .catch(error => {
                alert("Erreur lors de la requête.");
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            });
        }, 2000);
    });
});
// Même principe pour le bouton "Bloqué"
document.querySelectorAll('.btn-toggle-bloc').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var email = this.dataset.email;
        var btnRef = this;

        btnRef.disabled = true;
        btnRef.classList.add('en-attente');

        setTimeout(function() {
            fetch('toggle_bloc.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btnRef.textContent = data.newEtat === "oui" ? "Oui" : "Non";
                    btnRef.dataset.bloque = data.newEtat;
                    if (data.newEtat === "oui") {
                        btnRef.classList.add('btn-blocked');
                        btnRef.classList.remove('btn-vip');
                    } else {
                        btnRef.classList.remove('btn-blocked');
                        btnRef.classList.add('btn-vip');
                    }
                } else {
                    alert("Impossible de modifier le statut BLOQUÉ !");
                }
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            })
            .catch(error => {
                alert("Erreur lors de la requête.");
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            });
        }, 2000);
    });
});
</script>
</body>
</html>
