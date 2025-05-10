<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
$idVoyage = $_GET['id'] ?? null;


$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (empty($idVoyage)) {
    header("Location: Reserve.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM voyages WHERE IdVoyage = ?");
$stmt->execute([$idVoyage]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($voyage)) {
    header("Location: Reserve.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM etapes WHERE IdVoyage = ?");
$stmt->execute([$idVoyage]);
$etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM reduction WHERE IdVoyage = ?");
$stmt->execute([$idVoyage]);
$reductions = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nbAdultes = (int)($_POST['nb_adultes'] ?? 1);
    $nbEnfants = (int)($_POST['nb_enfants'] ?? 0);
    $optionsChoisies = $_POST['options'] ?? [];
    

    if ($nbAdultes < 1) {
        $erreur = "Nombre d'adultes invalide";
    } elseif ($nbEnfants < 0) {
        $erreur = "Nombre d'enfants invalide";
    } elseif (($nbAdultes + $nbEnfants) > $voyage['PlacesDispo']) {
        $erreur = "Nombre de places insuffisantes";
    } else {
        
        $prixTotal = calculerPrixTotal($voyage, $nbAdultes, $nbEnfants, $pdo, $reductions);
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
    <title><?= htmlspecialchars($voyage['Titre']) ?> - Horage</title>
    <link rel="stylesheet" href="CSS/details.css?v=<?= time() ?>">
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
                            <a href="contact.php" class="a1">contacts</a>
                        </li>
                        <li><a href="panier.php" class="a1">Panier</a></li>
                    </ul>
                </div>
        </header>
    
    <main class="container">
        <h1 id="voyage_title"><?= htmlspecialchars($voyage['Titre']) ?></h1>
        
        <?php if (isset($erreur)): ?>
            <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <section class="voyage-info">
            <div class="description">
                <?= htmlspecialchars($voyage['Description']) ?>
            </div>
            
            <div class="meta-info">
                <p><strong>Dates:</strong> <?= $voyage['DateDebut'] ?> au <?= $voyage['DateFin'] ?></p>
                <p><strong>Prix de base:</strong> <?= afficherPrix($voyage['PrixBase']) ?> €/pers</p>
                <p><strong>Places restantes:</strong> <?= $voyage['PlacesDispo'] ?></p>
                
                <div class="reductions">
                    <h3>Réductions disponibles:</h3>
                    <ul>
                        <?php
                        foreach ($reductions as $reduction):
                            // Décoder le JSON de ConditionReduction
                            $condition = json_decode($reduction['ConditionReduction'], true);

                            // Vérifier si le type de réduction est "groupe" ou autre
                            if ($reduction['TypeReduction'] === 'groupe'):
                                // Affichage pour une réduction de groupe
                                ?>
                                <li>
                                    À partir de <?= $condition['min_personnes'] ?> personnes : 
                                    <?= afficherPrix($reduction['PrixReduit']) ?> €/pers
                                </li>
                                <?php
                            else: 
                                // Affichage pour une réduction basée sur le nombre d'enfants
                                ?>
                                <li>
                                    Pour <?= $condition['min_enfants'] ?> enfants ou plus : 
                                    Réduction de <?= afficherPrix($reduction['PrixReduit']) ?> € par enfant
                                </li>
                                <?php 
                            endif; 
                        endforeach; 
                        ?>
                    </ul>

                </div>
            </div>
        </section>

    <form method="POST" action="recapitulatif.php">
    <input type="hidden" name="voyage_id" value="<?= $voyage['IdVoyage'] ?>">
    <h2>Réservation</h2>
    
    <div class="form-group">
        <label for="nb_adultes">Nombre d'adultes:</label>
        <input type="number" id="nb_adultes" name="nb_adultes" min="1" max="<?= $voyage['PlacesDispo'] ?>" value="<?= $_POST['nb_adultes'] ?? 1 ?>" required>
    </div>
    
    <div class="form-group">
        <label for="nb_enfants">Nombre d'enfants (moins de 12 ans):</label>
        <input type="number" id="nb_enfants" name="nb_enfants" min="0" max="<?= $voyage['PlacesDispo'] - 1 ?>" value="<?= $_POST['nb_enfants'] ?? 0 ?>">
    </div>
    
    <section class="etapes">
        <h2>Personnalisation des options</h2>
        
        <?php
            foreach ($etapes as $etape): ?>
                <div class="etape">
                    <h3><?= htmlspecialchars($etape['Titre']) ?></h3>
                    
                    <div class="options">
                        <?php
                        // Préparation de la requête pour récupérer les options
                        $stmtOptions = $pdo->prepare("SELECT * FROM options_etape WHERE IdEtape = ?");
                        $stmtOptions->execute([$etape['IdEtape']]);
                        $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($options as $option):
                            // Récupérer les choix disponibles pour chaque option
                            $stmtChoix = $pdo->prepare("SELECT * FROM choix_options WHERE IdOption = ?");
                            $stmtChoix->execute([$option['IdOption']]);
                            $choixOptions = $stmtChoix->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                            <div class="option-group">
                                <h4><?= htmlspecialchars($option['NomOption']) ?></h4>
                                <ul>
                                    <?php foreach ($choixOptions as $choix): ?>
                                        <li>
                                            <label>
                                                <?php if (strtolower($option['NomOption']) === 'activité'): ?>
                                                    <input type="checkbox"
                                                        name="options[<?= $etape['IdEtape'] ?>][<?= $option['IdOption'] ?>][]"
                                                        value="<?= htmlspecialchars($choix['Nom']) ?>|<?= htmlspecialchars($choix['Prix']) ?>">
                                                <?php else: ?>
                                                    <input type="radio"
                                                        name="options[<?= $etape['IdEtape'] ?>][<?= $option['IdOption'] ?>]"
                                                        value="<?= htmlspecialchars($choix['Nom']) ?>|<?= htmlspecialchars($choix['Prix']) ?>"
                                                        required>
                                                <?php endif; ?>
                                                <?= htmlspecialchars($choix['Nom']) ?> (+<?= afficherPrix($choix['Prix']) ?> €)
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <div class="option-group etape"> 
            <h3>Prix :</h3><h3 class="prix"> 0$</h3>
        </div>
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
    <script>
        const voyageData = {
            prixBase: <?= $voyage['PrixBase'] ?>,
            reductions: <?= json_encode($reductions) ?>,
            placesDisponibles: <?= $voyage['PlacesDispo'] ?>
        };
        function formatPrix(prix) {
            return prix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }
    </script>
    <script src="js/details.js"></script>


</body>
</html>