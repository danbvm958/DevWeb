<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: signup.php");
    exit;
}


error_reporting(E_ALL);
ini_set('display_errors', 1);


$jsonFile = __DIR__ . '/data/voyages.json';

if (!file_exists($jsonFile)) {
    die("Erreur: Fichier de données introuvable");
}

$jsonContent = file_get_contents($jsonFile);
$data = json_decode($jsonContent, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Erreur JSON: " . json_last_error_msg());
}


if (empty($data['voyages']) || !is_array($data['voyages'])) {
    die("Erreur: Aucun voyage disponible");
}


$idVoyage = $_GET['id'] ?? null;

if (empty($idVoyage)) {
    header("Location: Reserve.php");
    exit;
}


$voyageSelectionne = null;
foreach ($data['voyages'] as $voyage) {
    if (isset($voyage['id_voyage']) && $voyage['id_voyage'] === $idVoyage) {
        $voyageSelectionne = $voyage;
        break;
    }
}

if (!$voyageSelectionne) {
    $idsDisponibles = array_map(function($v) { 
        return $v['id_voyage'] ?? 'INCONNU'; 
    }, $data['voyages']);
    
    die(sprintf(
        "Voyage '%s' introuvable. IDs disponibles: %s",
        htmlspecialchars($idVoyage),
        implode(', ', $idsDisponibles)
    ));
}


function calculerPrixTotal($voyage, $nbAdultes, $nbEnfants) {
    $prixBase = $voyage['tarification']['prix_par_personne'];
    $reductions = $voyage['tarification']['reductions'];
    $total = 0;
    
    // Réduction groupe
    $totalPersonnes = $nbAdultes + $nbEnfants;
    foreach ($reductions as $reduction) {
        if ($reduction['type_reduction'] === 'groupe' && $totalPersonnes >= $reduction['condition']['min_personnes']) {
            $prixBase = $reduction['prix_reduit'];
            break;
        }
    }
    
    // Prix adultes
    $total += $nbAdultes * $prixBase;
    
    // Réduction enfants
    foreach ($reductions as $reduction) {
        if ($reduction['type_reduction'] === 'enfant' && $nbEnfants >= $reduction['condition']['min_enfants']) {
            $remise = $reduction['remise_par_enfant'];
            $total += $nbEnfants * ($prixBase - $remise);
            return $total;
        }
    }
    
    
    $total += $nbEnfants * $prixBase;
    return $total;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nbAdultes = (int)($_POST['nb_adultes'] ?? 1);
    $nbEnfants = (int)($_POST['nb_enfants'] ?? 0);
    $optionsChoisies = $_POST['options'] ?? [];
    

    if ($nbAdultes < 1) {
        $erreur = "Nombre d'adultes invalide";
    } elseif ($nbEnfants < 0) {
        $erreur = "Nombre d'enfants invalide";
    } elseif (($nbAdultes + $nbEnfants) > $voyageSelectionne['places_disponibles']) {
        $erreur = "Nombre de places insuffisantes";
    } else {
        
        $prixTotal = calculerPrixTotal($voyageSelectionne, $nbAdultes, $nbEnfants);
        
      
        $_SESSION['reservation'] = [
            'voyage_id' => $idVoyage,
            'voyage_titre' => $voyageSelectionne['titre'],
            'nb_adultes' => $nbAdultes,
            'nb_enfants' => $nbEnfants,
            'prix_total' => $prixTotal,
            'options' => $optionsChoisies,
            'dates' => $voyageSelectionne['dates']
        ];
        
        header("Location: recapitulatif.php");
        exit;
    }
}

function afficherPrix($prix) {
    return number_format($prix, 0, '', ' ');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($voyageSelectionne['titre']) ?> - Horage</title>
    <link rel="stylesheet" href="CSS/details.css?v=<?= time() ?>">
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
    
    <main class="container">
        <h1 id="voyage_title"><?= htmlspecialchars($voyageSelectionne['titre']) ?></h1>
        
        <?php if (isset($erreur)): ?>
            <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <section class="voyage-info">
            <div class="description">
                <?= htmlspecialchars($voyageSelectionne['description']) ?>
            </div>
            
            <div class="meta-info">
                <p><strong>Dates:</strong> <?= $voyageSelectionne['dates']['debut'] ?> au <?= $voyageSelectionne['dates']['fin'] ?></p>
                <p><strong>Prix de base:</strong> <?= afficherPrix($voyageSelectionne['tarification']['prix_par_personne']) ?> €/pers</p>
                <p><strong>Places restantes:</strong> <?= $voyageSelectionne['places_disponibles'] ?></p>
                
                <div class="reductions">
                    <h3>Réductions disponibles:</h3>
                    <ul>
                        <?php foreach ($voyageSelectionne['tarification']['reductions'] as $reduction): ?>
                            <li>
                                <?php if ($reduction['type_reduction'] === 'groupe'): ?>
                                    À partir de <?= $reduction['condition']['min_personnes'] ?> personnes : 
                                    <?= afficherPrix($reduction['prix_reduit']) ?> €/pers
                                <?php else: ?>
                                    Pour <?= $reduction['condition']['min_enfants'] ?> enfants ou plus : 
                                    Réduction de <?= afficherPrix($reduction['remise_par_enfant']) ?> € par enfant
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <form method="POST" action="recapitulatif.php">
    <input type="hidden" name="voyage_id" value="<?= $voyageSelectionne['id_voyage'] ?>">
    <h2>Réservation</h2>
    
    <div class="form-group">
        <label for="nb_adultes">Nombre d'adultes:</label>
        <input type="number" id="nb_adultes" name="nb_adultes" min="1" max="<?= $voyageSelectionne['places_disponibles'] ?>" value="<?= $_POST['nb_adultes'] ?? 1 ?>" required>
    </div>
    
    <div class="form-group">
        <label for="nb_enfants">Nombre d'enfants (moins de 12 ans):</label>
        <input type="number" id="nb_enfants" name="nb_enfants" min="0" max="<?= $voyageSelectionne['places_disponibles'] - 1 ?>" value="<?= $_POST['nb_enfants'] ?? 0 ?>">
    </div>
    
    <section class="etapes">
        <h2>Personnalisation des options</h2>
        
        <?php foreach ($voyageSelectionne['liste_etapes'] as $etape): ?>
        <div class="etape">
            <h3><?= htmlspecialchars($etape['titre']) ?></h3>
            
            <div class="options">
                <?php foreach ($etape['options'] as $option): ?>
                <div class="option-group">
                    <h4><?= htmlspecialchars($option['nom']) ?></h4>
                    <ul>
                    <?php foreach ($option['choix'] as $choix): ?>
                        <li>
                            <label>
                                <?php if (strtolower($option['nom']) === 'activité'): ?>
                                    <input type="checkbox"
                                        name="options[<?= $etape['id_etape'] ?>][<?= $option['id_option'] ?>][]" 
                                        value="<?= htmlspecialchars($choix['option']) ?>|<?= $choix['prix'] ?>">
                                <?php else: ?>
                                    <input type="radio" 
                                        name="options[<?= $etape['id_etape'] ?>][<?= $option['id_option'] ?>]" 
                                        value="<?= htmlspecialchars($choix['option']) ?>|<?= $choix['prix'] ?>"
                                        required>
                                <?php endif; ?>

                                <?= htmlspecialchars($choix['option']) ?> (+<?= afficherPrix($choix['prix']) ?> €)
                            </label>
                        </li>
                    <?php endforeach; ?>

                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    
    <div class="actions">
        <a href="Reserve.php" class="btn">Retour aux voyages</a>
        <button type="submit" class="btn btn-primary">Valider la réservation</button>
    </div>
</form>

    </main>

    <footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
</body>
</html>