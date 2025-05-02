<?php
session_start();

// Chemin vers votre fichier JSON
$jsonFile = 'data/utilisateur.json';

// Lire le contenu du fichier JSON
$jsonData = file_get_contents($jsonFile);

// Décoder le JSON en tableau PHP
$users = json_decode($jsonData, true);

// Vérifier si le décodage a réussi
if ($users === null && json_last_error() !== JSON_ERROR_NONE) {
    die("Erreur lors de la lecture du fichier JSON: " . json_last_error_msg());
}
?>
<!DOCTYPE>
<html>
    <head>
        <title>Page Administrateur - Horage</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="CSS/admin.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
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
                <?php if ($user['type'] !== 'admin'): // Ne pas afficher les admins ?>
                <tr>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><span class="btn <?= ($user['type'] === 'vip') ? 'btn-vip' : 'btn-blocked' ?>">
                        <?= ($user['type'] === 'vip') ? 'Oui' : 'Non' ?>
                    </span></td>
                    <td><span class="btn btn-blocked">Non</span></td>
                    <td>
                        <a href="profil_user_admin.php?email=<?= urlencode($user['email']) ?>" 
                           class="btn btn-profile">
                           Voir
                        </a>
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