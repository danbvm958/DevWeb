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
                            <a href="/horage_project/accueil.php" class="a1">Accueil</a>
                        </li>
                        
                        <li>
                            <a href="/horage_project/presentation.php" class="a1">Presentation</a>
                        </li>
                        
                        <li>
                            <a href="/horage_project/Reserve.php" class="a1">Nos offres</a>
                        </li>

                        <li>
                            <a href="/horage_project/Recherche.php" class="a1">reserver</a>
                        </li>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                        <li>
                            <a href="/horage_project/profil_user.php" class="a1">Profil</a>
                        </li>
                        <?php else: ?>
                        <li>
                            <a href="/horage_project/login.php" class="a1">Connexion</a>
                        </li>
                        <?php endif; ?>

                        <li>
                            <a href="/horage_project/accueil.php" class="a1">contacts</a>
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
            <?php endforeach; ?>
        </table>
    </div>

        
        <footer>
            <h2>Copyright © Horage - Tous droits réservés</h2>
            <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
        </footer>
    </body>
</html>