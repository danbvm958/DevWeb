<?php
$jsonFile = 'data/utilisateur.json';
session_start();
function loadUsers($file) {
    if (!file_exists($file)) {
        return [];
    }
    $data = file_get_contents($file);
    return json_decode($data, true) ?? [];
}

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header("Location: profil_user.php"); // Redirige vers la page de profil
    exit();
}


function saveUsers($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
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

    $users = loadUsers($jsonFile);

    if (empty($errors)) {
        foreach ($users as $user) {
            if ($user['email'] == $mail1) {
                $errors[] = "L'email est déjà utilisé.";
                break;
            }
            if ($user['username'] == $username) {
                $errors[] = "Le nom d'utilisateur est déjà pris.";
                break;
            }
        }
    }
    if (empty($errors)) {
        $newUser = [
            'username' => $username,
            'email' => $mail1,
            'password' => password_hash($password1, PASSWORD_DEFAULT),
            'nom' => $nom,
            'prenom' => $prenom,
            'birthdate' => $birthdate,
            'type' => 'normal'
        ];
        $users[] = $newUser;
        saveUsers($jsonFile, $users);

        $successMessage = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
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
                <li><a href="presentation.php" class="a1">Présentation</a></li>
                <li><a href="Reserve.php" class="a1">Nos offres</a></li>
                <li><a href="Recherche.php" class="a1">Réserver</a></li>
                <li><a href="login.php" class="a1">Connexion</a></li>
                <li><a href="accueil.php" class="a1">Contacts</a></li>
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
