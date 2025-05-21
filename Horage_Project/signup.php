<?php
// On inclut le fichier de session nécessaire
require_once 'session.php';
// Je démarre la connexion à la base de données
$pdo = DemarrageSQL();
// On démarre la session
DemarrageSession();

// Je crée un tableau pour stocker les erreurs
$errors = [];
// On initialise un message de succès vide
$successMessage = "";

// On vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Je récupère et nettoie les données du formulaire
    $username = trim($_POST['username']);
    $mail1 = trim($_POST['mail1']);
    $mail2 = trim($_POST['mail2']);
    $password1 = trim($_POST['password1']);
    $password2 = trim($_POST['password2']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $birthdate = trim($_POST['birthdate']);

    // On vérifie que tous les champs sont remplis
    if (empty($username) || empty($mail1) || empty($mail2) || empty($password1) || empty($password2) || empty($nom) || empty($prenom) || empty($birthdate)) {
        $errors[] = "Tous les champs doivent être remplis.";
    }

    // Validation de la date de naissance
    if (!empty($birthdate)) {
        // Je crée des objets DateTime pour comparer les dates
        $today = new DateTime();
        $inputDate = new DateTime($birthdate);
        
        // On vérifie que la date n'est pas dans le futur
        if ($inputDate > $today) {
            $errors[] = "La date de naissance ne peut pas être dans le futur.";
        }
    }

    // Je vérifie que les emails correspondent
    if ($mail1 !== $mail2) {
        $errors[] = "Les emails ne correspondent pas.";
    }

    // On valide le format de l'email
    if (!filter_var($mail1, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    // Je vérifie que les mots de passe correspondent
    if ($password1 !== $password2) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // On hache le mot de passe pour le stockage sécurisé
    $hash = password_hash($password1, PASSWORD_DEFAULT);
    // Je prépare la requête pour vérifier si l'email ou le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Email = ? OR NomUtilisateur = ? ");
    $stmt->execute([$mail1, $username]);
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si pas d'erreurs jusqu'ici, on vérifie les doublons
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

    // Si aucune erreur, on procède à l'inscription
    if (empty($errors)) {
        // J'insère le nouvel utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO utilisateur (NomUtilisateur,Email, MotDePasse, Nom, Prenom, Anniversaire, Types) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username,$mail1,$hash, $nom, $prenom, $birthdate, "normal"]);
        // On redirige vers la page de connexion
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Je définis les métadonnées de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- On donne un titre à la page -->
    <title>Inscription - Horage</title>
    <!-- Je lie la feuille de style CSS -->
    <link rel="stylesheet" href="CSS/login_signup.css">
    <!-- On définit l'icône du site -->
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <!-- Je charge les scripts JavaScript -->
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/signup_rules.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
<?php 
    // On affiche le header du site
    AfficherHeader();
?>

    <main>
        <!-- Conteneur du formulaire d'inscription -->
        <div class="form-container">
            <h1>Inscription à Horage</h1>
            <!-- Formulaire d'inscription -->
            <form action="signup.php" method="POST">

                <label>Nom:</label>
                <input type="text" name="nom" placeholder="Entrez votre nom" required />

                <label>Prénom:</label>
                <input type="text" name="prenom" placeholder="Entrez votre prenom" required />

                <label>Date de naissance :</label>
                <input type="date" name="birthdate" max="<?php echo date('Y-m-d'); ?>" required />
                <?php if (in_array("La date de naissance ne peut pas être dans le futur.", $errors)): ?>
                    <!-- On affiche l'erreur si la date est invalide -->
                    <div class="error">Date invalide (future)</div>
                <?php endif; ?>

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
                <!-- On affiche le message de succès s'il existe -->
                <div class="success-message"><?php echo $successMessage; ?></div>
                <?php endif; ?>
                <input type="submit" value="S'inscrire" />
            </form>

            <br>
            <!-- Lien vers la page de connexion -->
            <a href="login.php">Déjà membre ?</a>
        </div>
    </main>

    <!-- Pied de page -->
    <footer>
        <h2>Copyright © Horage - Tous droits réservés</h2>
        <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
    </footer>

</body>
</html>