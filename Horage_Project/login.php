<?php
session_start();

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    // Si l'utilisateur est connecté, redirige vers la page profil ou accueil
    header("Location: profil_user.php"); 
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifie si les champs sont remplis
    if (empty($username) || empty($password)) {
        $errors[] = "Tous les champs doivent être remplis.";
    } else {
        // Lire le fichier utilisateur.json
        $file = 'data/utilisateur.json';
        if (file_exists($file)) {
            $users = json_decode(file_get_contents($file), true);
        } else {
            $users = [];
        }

        // Vérifier si l'utilisateur existe
        $foundUser = null;
        foreach ($users as $user) {
            if ($user['username'] === $username || $user['email'] === $username) {
                $foundUser = $user;
                break;
            }
        }

        if ($foundUser) {
            // Vérifier le mot de passe hashé
            if (password_verify($password, $foundUser['password'])) {
                // Stocker toutes les informations dans la session sous forme de tableau
                $_SESSION['user'] = [
                    'username' => $foundUser['username'],
                    'nom' => $foundUser['nom'],
                    'prenom' => $foundUser['prenom'],
                    'email' => $foundUser['email'],
                    'type' => $foundUser['type'],
                    'voyages' => isset($foundUser['voyages']) ? $foundUser['voyages'] : []
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
                            <a href="accueil.php" class="a1">contacts</a>
                        </li>
                    </ul>
                </div>
        </header>

    <main>
        <div class="form-container">
            <h1>Connexion à Horage</h1>
            <form action="login.php" method="POST">
                <label>Nom d'utilisateur ou mail :</label>
                <input type="text" name="username" placeholder="Entrez un nom d'utilisateur ou un email" required />
                <?php if (in_array("Utilisateur non trouvé.", $errors)): ?>
                    <div class="error">Utilisateur non trouvé.</div>
                <?php endif; ?>

                <label>Mot de passe :</label>
                <input type="password" name="password" placeholder="Entrez un mot de passe" required />
                <?php if (in_array("Mot de passe incorrect.", $errors)): ?>
                    <div class="error">Mot de passe incorrect.</div>
                <?php endif; ?>

                <input type="submit" value="Log in"/>
            </form>

            <br/>
            <a href="signup.php">Rejoignez les Voyageurs de l’Ombre</a>
        </div>
    </main>

    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');
    
    // Fonction pour afficher les erreurs
    function showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error';
        errorDiv.textContent = message;
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
    
    // Fonction pour supprimer les erreurs existantes
    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => error.remove());
    }
    
    // Validation en temps réel
    usernameInput.addEventListener('input', function() {
        clearErrors();
        if (this.value.length < 3) {
            showError(this, "Le nom d'utilisateur doit faire au moins 3 caractères");
        }
    });
    
    passwordInput.addEventListener('input', function() {
        clearErrors();
        if (this.value.length < 6) {
            showError(this, "Le mot de passe doit faire au moins 6 caractères");
        }
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        clearErrors();
        let isValid = true;
        
        if (usernameInput.value.length < 3) {
            showError(usernameInput, "Le nom d'utilisateur doit faire au moins 3 caractères");
            isValid = false;
        }
        
        if (passwordInput.value.length < 6) {
            showError(passwordInput, "Le mot de passe doit faire au moins 6 caractères");
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
