<?php
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header("Location: profil_user.php"); 
    exit();
}
//Connection 
$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifie si les champs sont remplis
    if (empty($username) || empty($password)) {
        $errors[] = "Tous les champs doivent être remplis.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE NomUtilisateur = ? OR Email = ?");
        $stmt->execute([$username, $username]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            // Vérifier le mot de passe hashé
            if (password_verify($password, $utilisateur['MotDePasse'])) {
                // Stocker toutes les informations dans la session sous forme de tableau
                $_SESSION['user'] = [
                    'username' => $utilisateur['NomUtilisateur'],
                    'nom' =>$utilisateur['Nom'],
                    'prenom' => $utilisateur['Prenom'],
                    'email' =>$utilisateur['Email'],
                    'type' =>$utilisateur['Types'],
                    'id' =>$utilisateur['Id']
                ];
                header("Location: accueil.php"); // Redirige vers la page d'accueil
                exit();
            } else {
                $errors[] = "Mot de passe incorrect.";
            }
        } else {
            $errors[] = "Utilisateur non trouvé.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Horage</title>
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
    </style>
    <script src="js/themeSwitcher.js" defer></script>
    <script src="js/login_rules.js" defer></script>
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
            <li><a href="panier.php" class="a1">Panier</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="form-container">
        <h1>Connexion à Horage</h1>
        <form action="login.php" method="POST">
            <label>Nom d'utilisateur ou mail :</label>
            <input type="text" name="username" placeholder="Entrez un nom d'utilisateur ou un email" required maxlength="20" />
            <div class="character-counter" id="username-counter">0/20 caractères</div>
            <?php if (in_array("Utilisateur non trouvé.", $errors)): ?>
                <div class="error">Utilisateur non trouvé.</div>
            <?php endif; ?>

            <label>Mot de passe :</label>
            <input type="password" name="password" id="password-input" placeholder="Entrez un mot de passe" required maxlength="30" />
            <div class="character-counter" id="password-counter">0/30 caractères</div>
            <?php if (in_array("Mot de passe incorrect.", $errors)): ?>
                <div class="error">Mot de passe incorrect.</div>
            <?php endif; ?>

            <input type="submit" value="Log in"/>
        </form>

        <br/>
        <a href="signup.php">Rejoignez les Voyageurs de l'Ombre</a>
    </div>
</main>

<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>
</body>
</html>