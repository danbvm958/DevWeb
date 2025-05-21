<?php
// On démarre la session pour pouvoir utiliser les variables de session
session_start();

// On vérifie si l'utilisateur est déjà connecté en vérifiant la présence de 'user' dans la session
if (isset($_SESSION['user'])) {
    // Si l'utilisateur est connecté, on le redirige vers son profil
    header("Location: profil_user.php"); 
    exit();
}

// On définit les paramètres de connexion à la base de données
$dsn = 'mysql:host=sql112.infinityfree.com;dbname=if0_38962226_ma_bdd;charset=utf8';
$user = 'if0_38962226';
$password = 'DanChadyZied95';
try {
    // On tente de se connecter à la base de données avec PDO
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    // En cas d'échec de connexion, on affiche un message d'erreur et on arrête le script
    die("Erreur de connexion : " . $e->getMessage());
}

// On initialise un tableau pour stocker les éventuelles erreurs
$errors = [];
// On initialise un booléen pour indiquer si l'opération a réussi
$success = false;

// On vérifie si le formulaire a été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère l'email depuis le formulaire, avec une valeur par défaut vide si non défini
    $email = $_POST['email'] ?? '';

    // On vérifie si l'email est vide
    if (empty($email)) {
        // Si l'email est vide, on ajoute un message d'erreur
        $errors[] = "L'email doit être rempli.";
    } else {
        // On prépare et exécute une requête pour trouver l'utilisateur correspondant à l'email
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si on a trouvé un utilisateur avec cet email
        if ($utilisateur) {
            // On génère un premier token aléatoire
            $token = bin2hex(random_bytes(16));
            // On met à jour le token dans la base de données
            $stmt = $pdo->prepare("UPDATE utilisateur SET Token = ? WHERE Email = ?");
            $stmt->execute([$token, $email]);
            // On génère un nouveau token (plus sécurisé)
            $token = bin2hex(random_bytes(16));
            // On définit la date d'expiration du token (1 heure plus tard)
            $expiration = date('Y-m-d H:i:s', time() + 3600);

            // On met à jour le token et sa date d'expiration dans la base de données
            $stmt = $pdo->prepare("UPDATE utilisateur SET Token = ?, TokenExpiration = ? WHERE Email = ?");
            $stmt->execute([$token, $expiration, $email]);

            // On inclut le fichier qui contient la fonction d'envoi d'email
            require 'EnvoieLien.php';
            // On tente d'envoyer l'email de réinitialisation
            if (sendPasswordResetEmail($email, $token)) {
                // Si l'envoi réussit, on passe success à true
                $success = true;
            } else {
                // Si l'envoi échoue, on ajoute un message d'erreur
                $errors[] = "Échec de l'envoi de l'email. Veuillez réessayer plus tard.";
            }
        } else {
            // Si aucun utilisateur n'est trouvé avec cet email, on ajoute un message d'erreur
            $errors[] = "Aucun utilisateur n'est associé à cet email.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation du mot de passe - Horage</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/login_signup.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <style>
        .eye-button {
            margin-left: 5px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: transform 0.2s;
        }

        .eye-button.large {
            transform: scale(1.3);
        }

        .character-counter {
            font-size: 0.8em;
            color: #666;
        }

        .error {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }

        .success {
            color: green;
            font-size: 0.8em;
            margin-top: 5px;
        }
    </style>
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
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
            <li><a href="login.php" class="a1">Connexion</a></li>
            <li><a href="contact.php" class="a1">contacts</a></li>
            <li><a href="panier.php" class="a1">Panier</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="form-container">
        <h1>Réinitialisation du mot de passe</h1>
        
        <?php if ($success): ?>
            <!-- On affiche un message de succès si l'email a été envoyé -->
            <div class="success">Un lien de réinitialisation a été envoyé à votre adresse email.</div>
            <p><a href="login.php">Retour à la connexion</a></p>
        <?php else: ?>
            <!-- On affiche le formulaire si l'email n'a pas encore été envoyé -->
            <form action="MdpOublie.php" method="POST">
                <label>Adresse Email :</label>
                <input type="email" name="email" placeholder="Entrez votre adresse email" required maxlength="50" />
                <div class="character-counter" id="email-counter">0/50 caractères</div>

                <!-- On affiche les éventuelles erreurs -->
                <?php if (in_array("L'email doit être rempli.", $errors)): ?>
                    <div class="error">L'email doit être rempli.</div>
                <?php endif; ?>

                <?php if (in_array("Aucun utilisateur n'est associé à cet email.", $errors)): ?>
                    <div class="error">Aucun utilisateur n'est associé à cet email.</div>
                <?php endif; ?>

                <input type="submit" value="Envoyer le lien de réinitialisation" />
            </form>

            <br />
            <a href="login.php">Retour à la connexion</a>
        <?php endif; ?>
    </div>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>
</body>
</html>