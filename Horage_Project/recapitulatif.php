<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si l'ID du voyage est passé
if (!isset($_POST['voyage_id'])) {
    die("Voyage introuvable.");
}
// Récupérer les données du formulaire
$nb_adultes = isset($_POST['nb_adultes']) ? intval($_POST['nb_adultes']) : 0;
$nb_enfants = isset($_POST['nb_enfants']) ? intval($_POST['nb_enfants']) : 0;
$nombre_personnes = $nb_adultes + $nb_enfants;
$options_choisies = isset($_POST['options']) ? $_POST['options'] : [];
$voyage_id = $_POST['voyage_id'];

// Trouver le voyage sélectionné
$stmt = $pdo->prepare("SELECT * FROM voyages WHERE IdVoyage = ?");
$stmt->execute([$voyage_id]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);

// Trouver les reductions
$stmt = $pdo->prepare("SELECT * FROM reduction WHERE IdVoyage = ?");
$stmt->execute([$voyage_id]);
$reductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Trouver les étapes
$stmt = $pdo->prepare("SELECT * FROM etapes WHERE IdVoyage = ?");
$stmt->execute([$voyage_id]);
$etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$voyage) {
    die("Voyage introuvable.");
}



// Fonction pour afficher le prix avec formatage
function afficherPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}

function calculerPrixTotalAvecOptions($pdo, $voyage_id, $nb_adultes, $nb_enfants, $options_choisies) {
    // 1. Récupérer les informations de base
    $stmt = $pdo->prepare("SELECT PrixBase FROM voyages WHERE IdVoyage = ?");
    $stmt->execute([$voyage_id]);
    $voyage = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$voyage) {
        throw new Exception("Voyage introuvable");
    }
    
    $nombre_personnes = $nb_adultes + $nb_enfants;
    $prix_total = 0;

    // 2. Appliquer les réductions
    $stmt = $pdo->prepare("SELECT * FROM reduction WHERE IdVoyage = ?");
    $stmt->execute([$voyage_id]);
    $reductions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $prix_base = $voyage['PrixBase'];

    // Réduction groupe
    foreach ($reductions as $reduction) {
        if ($reduction['TypeReduction'] === 'groupe') {
            if ($nombre_personnes >= $reduction['ConditionReduction']) {
                $prix_base = $reduction['PrixReduit'];
                break;
            }
        }
    }

    // Prix pour adultes
    $prix_total = $nb_adultes * $prix_base;

    // Réduction enfants
    foreach ($reductions as $reduction) {
        if ($reduction['TypeReduction'] === 'enfant') {
            if ($nb_enfants >= $reduction['ConditionReduction']) {
                $prix_enfant = $prix_base - $reduction['PrixReduit'];
                $prix_total += $nb_enfants * $prix_enfant;
                break;
            }
        }
    }

    // Si aucune réduction enfant applicable mais qu'il y a des enfants
    if ($nb_enfants > 0 && $prix_total == $nb_adultes * $prix_base) {
        $prix_total += $nb_enfants * $prix_base;
    }

    // 3. Ajouter le prix des options
    $etapes_avec_options = [];
    
    // Récupérer toutes les options disponibles pour ce voyage
    $stmt = $pdo->prepare("
        SELECT oe.IdOption, oe.IdEtape, oe.NomOption, 
               co.IdChoix, co.Nom, co.Prix
        FROM options_etape oe
        JOIN choix_options co ON oe.IdOption = co.IdOption
        WHERE oe.IdEtape IN (
            SELECT IdEtape FROM etapes WHERE IdVoyage = ?
        )
    ");
    $stmt->execute([$voyage_id]);
    $options_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Organiser les options par étape et par option
    $options_par_etape = [];
    foreach ($options_disponibles as $option) {
        $id_etape = $option['IdEtape'];
        $id_option = $option['IdOption'];
        
        if (!isset($options_par_etape[$id_etape])) {
            $options_par_etape[$id_etape] = [];
        }
        
        if (!isset($options_par_etape[$id_etape][$id_option])) {
            $options_par_etape[$id_etape][$id_option] = [
                'nom' => $option['NomOption'],
                'choix' => []
            ];
        }
        
        $options_par_etape[$id_etape][$id_option]['choix'][] = [
            'IdChoix' => $option['IdChoix'],
            'Nom' => $option['Nom'],
            'Prix' => $option['Prix']
        ];
    }
    
    // Traiter les options choisies
    foreach ($options_choisies as $id_etape => $options) {
        if (!isset($options_par_etape[$id_etape])) continue;
        
        foreach ($options as $id_option => $choix_data) {
            if (!isset($options_par_etape[$id_etape][$id_option])) continue;
            
            $option = $options_par_etape[$id_etape][$id_option];
            
            if (is_array($choix_data)) {
                // Cas des checkbox (choix multiples)
                foreach ($choix_data as $choix_str) {
                    list($nom_choix, $prix_choix) = explode('|', $choix_str);
                    
                    foreach ($option['choix'] as $choix_possible) {
                        if ($choix_possible['Nom'] === $nom_choix) {
                            $etapes_avec_options[$id_etape][] = [
                                'id_etape' => $id_etape, // Ajout de l'ID étape
                                'id_option' => $id_option, // Ajout de l'ID option
                                'id_choix' => $choix_possible['IdChoix'], // Ajout de l'ID choix
                                'nom' => $option['nom'],
                                'choix' => $nom_choix,
                                'prix' => $prix_choix
                            ];
                            $prix_total += floatval($prix_choix) * $nombre_personnes;
                            break;
                        }
                    }
                }
            } else {
                // Cas des radio (choix unique)
                list($nom_choix, $prix_choix) = explode('|', $choix_data);
                
                foreach ($option['choix'] as $choix_possible) {
                    if ($choix_possible['Nom'] === $nom_choix) {
                        $etapes_avec_options[$id_etape][] = [
                            'id_etape' => $id_etape, // Ajout de l'ID étape
                            'id_option' => $id_option, // Ajout de l'ID option
                            'id_choix' => $choix_possible['IdChoix'], // Ajout de l'ID choix
                            'nom' => $option['nom'],
                            'choix' => $nom_choix,
                            'prix' => $prix_choix
                        ];
                        $prix_total += floatval($prix_choix) * $nombre_personnes;
                        break;
                    }
                }
            }
        }
    }
    
    return [
        'prix_total' => $prix_total,
        'etapes_avec_options' => $etapes_avec_options,
        'prix_base' => $voyage['PrixBase'],
        'nombre_personnes' => $nombre_personnes
    ];
}


$resultat = calculerPrixTotalAvecOptions($pdo, $voyage_id, $nb_adultes, $nb_enfants, $options_choisies);

$prix_total = $resultat['prix_total'];
$etapes_avec_options = $resultat['etapes_avec_options'];

// Stocker en session pour le paiement
$_SESSION['pending_payment'][] = [
    'voyage_id' => $voyage_id,
    'nombre_personnes' => $resultat['nombre_personnes'],
    'nb_adultes' => $nb_adultes,
    'nb_enfants' => $nb_enfants,
    'options_choisies' => $etapes_avec_options,
    'prix_total' => $prix_total
];
$_SESSION['npayment'] = count($_SESSION['pending_payment'])-1;



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
            <li><a href="accueil.php" class="a1">Contacts</a></li>
            <li><a href="panier.php" class="a1">Panier</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['Titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['Description']) ?></p>
        <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['DateDebut']) ?> au <?= htmlspecialchars($voyage['DateFin']) ?> </p>
        <p><strong>Nombre d'adultes :</strong> <?= $nb_adultes ?></p>
        <p><strong>Nombre d'enfants :</strong> <?= $nb_enfants ?></p>
        <p><strong>Prix total :</strong> <?= afficherPrix($prix_total) ?></p>
    </div>

    <h3 id="subtitle">Étapes du voyage</h3>
    <ul>
    <?php foreach ($etapes as $etape) : ?>
        <li class="parent">
            <strong><?= htmlspecialchars($etape['Titre']) ?></strong> - <?= htmlspecialchars($etape['Position']) ?>
            <ul>
                <?php if (isset($etapes_avec_options[$etape['IdEtape']]) && !empty($etapes_avec_options[$etape['IdEtape']])) : ?>
                    <?php foreach ($etapes_avec_options[$etape['IdEtape']] as $option) : ?>
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