<?php
session_start();

// Charger les données de voyage
$json = file_get_contents('data/voyages.json');
$voyages = json_decode($json, true)['voyages'];

// Vérifier si l'ID du voyage est passé
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

// Récupérer les données du formulaire
$nb_adultes = isset($_POST['nb_adultes']) ? intval($_POST['nb_adultes']) : 0;
$nb_enfants = isset($_POST['nb_enfants']) ? intval($_POST['nb_enfants']) : 0;
$nombre_personnes = $nb_adultes + $nb_enfants;
$options_choisies = isset($_POST['options']) ? $_POST['options'] : [];

// Fonction pour afficher le prix avec formatage
function afficherPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}

// Fonction pour calculer le prix de base avec réductions
function calculerPrixBase($voyage, $nb_adultes, $nb_enfants) {
    $prix_base = $voyage['tarification']['prix_par_personne'];
    $total_personnes = $nb_adultes + $nb_enfants;
    
    // Appliquer réduction groupe si applicable
    foreach ($voyage['tarification']['reductions'] as $reduction) {
        if ($reduction['type_reduction'] === 'groupe' && $total_personnes >= $reduction['condition']['min_personnes']) {
            $prix_base = $reduction['prix_reduit'];
            break;
        }
    }
    
    // Calculer prix pour adultes
    $prix_total = $nb_adultes * $prix_base;
    
    // Appliquer réduction enfants si applicable
    foreach ($voyage['tarification']['reductions'] as $reduction) {
        if ($reduction['type_reduction'] === 'enfant' && $nb_enfants >= $reduction['condition']['min_enfants']) {
            $prix_enfant = $prix_base - $reduction['remise_par_enfant'];
            $prix_total += $nb_enfants * $prix_enfant;
            return $prix_total;
        }
    }
    
    // Si pas de réduction enfant applicable
    $prix_total += $nb_enfants * $prix_base;
    return $prix_total;
}

// Calcul du prix de base avec réductions
$prix_total = calculerPrixBase($voyage, $nb_adultes, $nb_enfants);

// Ajouter le prix des options choisies
$etapes_avec_options = [];

foreach ($voyage['liste_etapes'] as $etape) {
    $etapes_avec_options[$etape['id_etape']] = [];

    if (isset($options_choisies[$etape['id_etape']])) {
        foreach ($options_choisies[$etape['id_etape']] as $option_id => $choix_data) {
            // Vérifier si c'est un tableau (checkbox multiple)
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
                                $prix_total += floatval($choix_prix) * $nombre_personnes;
                            }
                        }
                    }
                }
            } else { // Si ce n'est pas un tableau, c'est un radio (simple)
                list($choix_nom, $choix_prix) = explode('|', $choix_data);
                
                foreach ($etape['options'] as $option_categorie) {
                    foreach ($option_categorie['choix'] as $choix_possible) {
                        if ($choix_possible['option'] === $choix_nom) {
                            $etapes_avec_options[$etape['id_etape']][] = [
                                'nom' => $option_categorie['nom'],
                                'choix' => $choix_nom,
                                'prix' => $choix_prix
                            ];
                            $prix_total += floatval($choix_prix) * $nombre_personnes;
                        }
                    }
                }
            }
        }
    }
}

// Appliquer réduction supplémentaire (étudiant, etc.)
if (isset($_POST['reduction']) && $_POST['reduction'] === 'etudiant') {
    $prix_total *= 0.9; // 10% de réduction
}

// Stocker en session pour le paiement
$_SESSION['pending_payment'] = [
    'voyage_id' => $voyage['id_voyage'],
    'voyage_titre' => $voyage['titre'],
    'nombre_personnes' => $nombre_personnes,
    'nb_adultes' => $nb_adultes,
    'nb_enfants' => $nb_enfants,
    'options_choisies' => $etapes_avec_options,
    'prix_total' => $prix_total
];
$email_utilisateur = $_SESSION['user']['email'];
$fichier_users = __DIR__ . '/data/utilisateur.json';
$utilisateurs = json_decode(file_get_contents($fichier_users), true);
foreach ($utilisateurs as &$user) {
    if ($user['email'] === $email_utilisateur) {
        $user['panier'][] = $_SESSION['pending_payment'];
        break;
    }
}
file_put_contents($fichier_users, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif du voyage - Horage</title>
    <link rel="stylesheet" href="CSS/recapitulatif.css?v=<?php echo time(); ?>">
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
        <p><strong>Nombre d'adultes :</strong> <?= $nb_adultes ?></p>
        <p><strong>Nombre d'enfants :</strong> <?= $nb_enfants ?></p>
        <p><strong>Prix total :</strong> <?= afficherPrix($prix_total) ?></p>
    </div>

    <h3 id="subtitle">Étapes du voyage</h3>
    <ul>
        <?php foreach ($voyage['liste_etapes'] as $etape) : ?>
            <li class="parent">
                <strong><?= htmlspecialchars($etape['titre']) ?></strong> - <?= htmlspecialchars($etape['position']['ville']) ?>
                <ul>
                    <?php if (isset($etapes_avec_options[$etape['id_etape']]) && !empty($etapes_avec_options[$etape['id_etape']])) : ?>
                        <?php foreach ($etapes_avec_options[$etape['id_etape']] as $option) : ?>
                            <li>
                                <strong><?= htmlspecialchars($option['nom']) ?> :</strong> 
                                <?= htmlspecialchars($option['choix']) ?> - 
                                <?= afficherPrix($option['prix'] * $nombre_personnes) ?> (<?= afficherPrix($option['prix']) ?> × <?= $nombre_personnes ?> pers)
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>Aucune option choisie</li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Formulaire pour modifier la réservation -->
    <form action="voyages_details.php" method="get">
        <input type="hidden" name="id" value="<?= $voyage_id ?>">
        <input type="hidden" name="nb_adultes" value="<?= $nb_adultes ?>">
        <input type="hidden" name="nb_enfants" value="<?= $nb_enfants ?>">
        <button type="submit" class="sub">Modifier</button> 
    </form>

    <!-- Formulaire pour confirmer et payer -->
    <form action="vers_CyBank.php" method="post">
        <input type="hidden" name="voyage_id" value="<?= $voyage_id ?>">
        <input type="hidden" name="nombre_personnes" value="<?= $nombre_personnes ?>">
        <input type="hidden" name="prix_total" value="<?= $prix_total ?>">
        <button type="submit" class="sub">Confirmer et payer</button>
    </form>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>
</body>
</html>