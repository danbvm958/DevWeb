<?php
// On inclut les fichiers nécessaires pour la session et la base de données
require_once 'session.php';
// Je démarre la connexion SQL
$pdo = DemarrageSQL();
// On démarre la session utilisateur
DemarrageSession();
// Je crée un tableau pour stocker les erreurs
$errors = [];

// On vérifie si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Je récupère le nom d'utilisateur et le mot de passe
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // On vérifie si les champs sont vides
    if (empty($username) || empty($password)) {
        $errors[] = "Tous les champs doivent être remplis.";
    } else {
        // Je prépare la requête SQL pour chercher l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE NomUtilisateur = ? OR Email = ?");
        $stmt->execute([$username, $username]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {
            // On vérifie si le mot de passe correspond
            if (password_verify($password, $utilisateur['MotDePasse'])) {
                // Je stocke les informations de l'utilisateur dans la session
                $_SESSION['user'] = [
                    'username' => $utilisateur['NomUtilisateur'],
                    'nom' =>$utilisateur['Nom'],
                    'prenom' => $utilisateur['Prenom'],
                    'email' =>$utilisateur['Email'],
                    'type' =>$utilisateur['Types'],
                    'id' =>$utilisateur['Id']
                ];
                // On redirige vers la page d'accueil après connexion
                header("Location: accueil.php");
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
    <!-- Je définis le titre de la page -->
    <title>Login - Horage</title>
    <!-- On spécifie l'encodage des caractères -->
    <meta charset="UTF-8">
    <!-- Je lie la feuille de style CSS -->
    <link rel="stylesheet" href="CSS/login_signup.css">
    <!-- On adapte la page pour les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Je définis l'icône de la page (favicon) -->
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <style>
        /* On style le bouton pour afficher/masquer le mot de passe */
        .eye-button {
            margin-left: 5px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1em;
            transition: transform 0.2s;
        }
        
        /* Je rend le bouton plus grand quand on passe la souris */
        .eye-button.large {
            transform: scale(1.3);
        }
        
        /* On style le compteur de caractères */
        .character-counter {
            font-size: 0.8em;
            color: #666;
        }
        
        /* Je définis le style des messages d'erreur */
        .error {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }
    </style>
    <!-- On charge les scripts JavaScript avec defer pour qu'ils s'exécutent après le chargement de la page -->
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/login_rules.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
<?php 
    // J'affiche le header du site
    AfficherHeader();
?>

<main>
    <!-- Conteneur principal du formulaire -->
    <div class="form-container">
        <h1>Connexion à Horage</h1>
        <!-- Formulaire de connexion -->
        <form action="login.php" method="POST">
            <label>Nom d'utilisateur ou mail :</label>
            <input type="text" name="username" placeholder="Entrez un nom d'utilisateur ou un email" required maxlength="20" />
            <div class="character-counter" id="username-counter">0/20 caractères</div>
            <?php if (in_array("Utilisateur non trouvé.", $errors)): ?>
                <!-- On affiche l'erreur si l'utilisateur n'existe pas -->
                <div class="error">Utilisateur non trouvé.</div>
            <?php endif; ?>

            <label>Mot de passe :</label>
            <input type="password" name="password" id="password-input" placeholder="Entrez un mot de passe" required maxlength="30" />
            <div class="character-counter" id="password-counter">0/30 caractères</div>
            <?php if (in_array("Mot de passe incorrect.", $errors)): ?>
                <!-- J'affiche l'erreur si le mot de passe est incorrect -->
                <div class="error">Mot de passe incorrect.</div>
            <?php endif; ?>

            <input type="submit" value="Log in"/>
        </form>

        <br/>
        <!-- Liens vers l'inscription et la récupération de mot de passe -->
        <a href="signup.php">Rejoignez les Voyageurs de l'Ombre</a>
        <br/>
        <a href="MdpOublie.php">Mot de passe oublié ?</a>
    </div>
</main>

<!-- Pied de page avec copyright -->
<footer>
    <h2>Copyright © Horage - Tous droits réservés</h2>
    <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
</footer>
</body>
</html>