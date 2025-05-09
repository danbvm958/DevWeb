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

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header("Location: profil_user.php"); // Redirige vers la page de profil
    exit();
}


$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $mail1 = trim($_POST['mail1']);
    $mail2 = trim($_POST['mail2']);
    $password1 = trim($_POST['password1']);
    $password2 = trim($_POST['password2']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $birthdate = trim($_POST['birthdate']);

    if (empty($username) || empty($mail1) || empty($mail2) || empty($password1) || empty($password2) || empty($nom) || empty($prenom)) {
        $errors[] = "Tous les champs doivent être remplis.";
    }

    if ($mail1 !== $mail2) {
        $errors[] = "Les emails ne correspondent pas.";
    }

    if (!filter_var($mail1, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if ($password1 !== $password2) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    $hash = password_hash($password1, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Email = ? OR NomUtilisateur = ? ");
    $stmt->execute([$mail1, $username]);
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($errors)) {
        foreach ($utilisateurs as $utilisateur) {
            if ($utilisateur['Email'] == $mail1) {
                $errors[] = "L'email est déjà utilisé.";
                break;
            }
            if ($utilisateur['NomUtilisateur'] == $username) {
                $errors[] = "Le nom d'utilisateur est déjà pris.";
                break;
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO utilisateur (NomUtilisateur,Email, MotDePasse, Nom, Prenom, Anniversaire, Types) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username,$mail1,$hash, $nom, $prenom, $birthdate, "normal"]);
        header("Location: login.php");
    }
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Horage</title>
    <link rel="stylesheet" href="CSS/login_signup.css">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/themeSwitcher.js" defer></script>
    <script src="js/signup_rules.js" defer></script>
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
                        <li><a href="panier.php" class="a1">Panier</a></li>
                    </ul>
                </div>
        </header>

    <main>
        <div class="form-container">
            <h1>Inscription à Horage</h1>
            <form action="signup.php" method="POST">

                <label>Nom:</label>
                <input type="text" name="nom" placeholder="Entrez votre nom" required />

                <label>Prénom:</label>
                <input type="text" name="prenom" placeholder="Entrez votre prenom" required />

                <label>Date de naissance :</label>
                <input type="date" name="birthdate" required />

                <label>Nom d'utilisateur:</label>
                <input type="text" name="username" placeholder="Entrez un nom d'utilisateur" required />
                <?php if (in_array("Le nom d'utilisateur est déjà pris.", $errors)): ?>
                    <div class="error">Nom d'utilisateur déjà pris</div>
                <?php endif; ?>
                <br/>

                <label>Email:</label>
                <input type="mail" name="mail1" placeholder="Entrez un mail" required />
                <?php if (in_array("L'email est déjà utilisé.", $errors)): ?>
                    <div class="error">Email déjà utilisé</div>
                <?php endif; ?>
                <?php if (in_array("L'email n'est pas valide.", $errors)): ?>
                    <div class="error">Email invalide</div>
                <?php endif; ?>

                <br/>

                <label>Confirmez votre email:</label>
                <input type="mail" name="mail2" placeholder="Confirmez votre mail" required />
                <?php if (in_array("Les emails ne correspondent pas.", $errors)): ?>
                    <div class="error">Les emails ne correspondent pas</div>
                <?php endif; ?>

                <br/>

                <label>Mot de passe:</label>
                <input type="password" name="password1" placeholder="Entrez un mot de passe" required />
                <br/>

                <label>Confirmez votre mot de passe:</label>
                <input type="password" name="password2" placeholder="Entrez un mot de passe" required />
                <?php if (in_array("Les mots de passe ne correspondent pas.", $errors)): ?>
                    <div class="error">Les mots de passe ne correspondent pas</div>
                <?php endif; ?>

                <br/>
                <?php if ($successMessage): ?>
                <div class="success-message"><?php echo $successMessage; ?></div>
                <?php endif; ?>
                <input type="submit" value="S'inscrire" />
            </form>

            <br>
            <a href="login.php">Déjà membre ?</a>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

</body>
</html>
