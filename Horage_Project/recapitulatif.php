<?php
session_start();
// Charger les données de voyage
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];

// Vérifier si l'ID du voyage est passé dans le formulaire
if (!isset($_POST['voyage_id'])) {
    die("Voyage introuvable.");
}

$voyage_id = $_POST['voyage_id'];

// Trouver le voyage sélectionné
$voyage = null;
foreach ($voyages as $v) {
    if ($v['id_voyage'] === $voyage_id) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    die("Voyage introuvable.");
}

// Récupérer les options choisies
$options_choisies = isset($_POST['options']) ? $_POST['options'] : [];
$nombre_personnes = $_POST['nb_adultes'] + $_POST['nb_enfants'];

// Fonction pour afficher le prix avec formatage
function afficherPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}

$prix_total = 0;
$etapes_avec_options = [];

foreach ($voyage['liste_etapes'] as $etape) {
    $etapes_avec_options[$etape['id_etape']] = [];

    if (isset($options_choisies[$etape['id_etape']])) {
        foreach ($options_choisies[$etape['id_etape']] as $option_id => $choix_data) {
            if (is_array($choix_data)) {
                foreach ($choix_data as $choix_unique) {
                    list($choix_nom, $choix_prix) = explode('|', $choix_unique);
                    foreach ($etape['options'] as $option_categorie) {
                        foreach ($option_categorie['choix'] as $choix_possible) {
                            if ($choix_possible['option'] === $choix_nom) {
                                $etapes_avec_options[$etape['id_etape']][] = [
                                    'nom' => $option_categorie['nom'],
                                    'choix' => $choix_nom,
                                    'prix' => $choix_prix
                                ];
                                $prix_total += floatval($choix_prix);
                            }
                        }
                    }
                }
            } else {
                list($choix_nom, $choix_prix) = explode('|', $choix_data);
                foreach ($etape['options'] as $option_categorie) {
                    foreach ($option_categorie['choix'] as $choix_possible) {
                        if ($choix_possible['option'] === $choix_nom) {
                            $etapes_avec_options[$etape['id_etape']][] = [
                                'nom' => $option_categorie['nom'],
                                'choix' => $choix_nom,
                                'prix' => $choix_prix
                            ];
                            $prix_total += floatval($choix_prix);
                        }
                    }
                }
            }
        }
    }
}

$_SESSION['pending_payment'] = [
    'voyage_id' => $voyage['id_voyage'],
    'voyage_titre' => $voyage['titre'],
    'nombre_personnes' => $nombre_personnes,
    'options_choisies' => $etapes_avec_options,
    'etapes_supprimees' => [],
    'prix_total' => $prix_total
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif du voyage - Horage</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/root.css">
    <link rel="stylesheet" href="CSS/details.css">
    
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
            <li><a href="<?= $pageProfil ?>" class="a1"><?= isset($_SESSION['user']) ? 'Profil' : 'Connexion' ?></a></li>
            <li><a href="accueil.php" class="a1">contacts</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['description']) ?></p>
        <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['dates']['debut']) ?> au <?= htmlspecialchars($voyage['dates']['fin']) ?> (<?= htmlspecialchars($voyage['dates']['duree']) ?>)</p>
        <p><strong>Nombre de personnes :</strong> <?= $nombre_personnes ?></p>
        <p><strong>Prix total :</strong> <span style="color: var(--color-red-light)"><?= afficherPrix($prix_total) ?></span></p>
    </div>

    <h3 id="subtitle">Étapes du voyage</h3>
    <div class="steps-container">
        <?php foreach ($voyage['liste_etapes'] as $index => $etape) : ?>
            <div class="step-card">
                <strong><?= htmlspecialchars($etape['titre']) ?></strong> - <?= htmlspecialchars($etape['position']['ville']) ?>
                <ul class="step-options">
                    <?php if (isset($etapes_avec_options[$etape['id_etape']]) && !empty($etapes_avec_options[$etape['id_etape']])) : ?>
                        <?php foreach ($etapes_avec_options[$etape['id_etape']] as $option) : ?>
                            <li>
                                <strong><?= htmlspecialchars($option['nom']) ?> :</strong> 
                                <?= htmlspecialchars($option['choix']) ?> - 
                                <span style="color: var(--color-red-light)"><?= afficherPrix($option['prix']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>Aucune option choisie</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-actions">
        <form action="voyages_details.php" method="get">
            <input type="hidden" name="id" value="<?= $voyage_id ?>">
            <button type="submit" class="btn btn-modify">Modifier</button> 
        </form>

        <form action="vers_CyBank.php" method="post">
            <input type="hidden" name="voyage_id" value="<?= $voyage_id ?>">
            <input type="hidden" name="nombre_personnes" value="<?= $nombre_personnes ?>">
            <input type="hidden" name="prix_total" value="<?= $prix_total ?>">
            <button type="submit" class="btn btn-confirm">Confirmer et payer</button>
        </form>
    </div>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>
</body>
</html>