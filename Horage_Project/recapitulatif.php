<?php
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
            list($choix_nom, $choix_prix) = explode('|', $choix_data);

            foreach ($etape['options'] as $option_categorie) {
                foreach ($option_categorie['choix'] as $choix_possible) {
                    if ($choix_possible['option'] === $choix_nom) {
                        // Stocker la catégorie dans "nom" (qui est affiché en gras dans ton HTML)
                        $etapes_avec_options[$etape['id_etape']][] = [
                            'nom' => $option_categorie['nom'], // Catégorie (Activité, Hébergement...)
                            'choix' => $choix_nom,             // Choix précis effectué
                            'prix' => $choix_prix
                        ];

                        $prix_total += floatval($choix_prix);
                    }
                }
            }
        }
    }
}
$_SESSION['panier'] = [
    'id_voyage' => $voyage['id_voyage'],
    'options' => $etapes_avec_options,
    'prix_total' => $prix_total
];

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif du voyage - Horage</title>
    <link rel="stylesheet" href="CSS/recapitulatif.css?v=<?php echo time(); ?>">
</head>
<body>
<header>
    <div class="header_1">
        <h1>Horage</h1>
        <img src="img_horage/logo-Photoroom.png" alt="logo de Horage" width="200px">
    </div>   

    <div class="nav">
        <ul>
            <li><a href="/horage_project/accueil.php" class="a1">Accueil</a></li>
            <li><a href="/horage_project/presentation.php" class="a1">Présentation</a></li>
            <li><a href="/horage_project/Reserve.php" class="a1">Nos offres</a></li>
            <li><a href="/horage_project/Recherche.php" class="a1">Réserver</a></li>
            <?php if (isset($_SESSION['user'])): ?>
            <li><a href="/horage_project/profil_user.php" class="a1">Profil</a></li>
            <?php else: ?>
            <li><a href="/horage_project/login.php" class="a1">Connexion</a></li>
            <?php endif; ?>
            <li><a href="/horage_project/contact.php" class="a1">Contacts</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="hero1">
        <h2 id="main_title"><?= htmlspecialchars($voyage['titre']) ?></h2>
        <p><strong>Description :</strong> <?= htmlspecialchars($voyage['description']) ?></p>
        <p><strong>Dates :</strong> Du <?= htmlspecialchars($voyage['dates']['debut']) ?> au <?= htmlspecialchars($voyage['dates']['fin']) ?> (<?= htmlspecialchars($voyage['dates']['duree']) ?>)</p>
        <p><strong>Nombre de personnes :</strong> <?= $nombre_personnes ?></p>
        <p><strong>Prix total mis à jour :</strong> <?= afficherPrix($prix_total) ?></p>
    </div>

    <h3 id="subtitle">Étapes du voyage</h3>
        <ul>
            <?php foreach ($voyage['liste_etapes'] as $index => $etape) : ?>
                <li class="parent">
                    <strong><?= htmlspecialchars($etape['titre']) ?></strong> - <?= htmlspecialchars($etape['position']['ville']) ?>
                    <ul>
                        <?php if (isset($etapes_avec_options[$etape['id_etape']]) && !empty($etapes_avec_options[$etape['id_etape']])) : ?>
                            <?php foreach ($etapes_avec_options[$etape['id_etape']] as $option) : ?>
                                <li>
                                    <strong><?= htmlspecialchars($option['nom']) ?> :</strong> 
                                    <?= htmlspecialchars($option['choix']) ?> - 
                                    <?= afficherPrix($option['prix']) ?> €
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
        <button type="submit" class="sub">Modifier</button> 
    </form>

    <br><br>

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
