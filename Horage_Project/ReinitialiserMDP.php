<?php
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();

$errors = [];
$success = false;

// Récupère le token de l'URL
$token = $_GET['token'] ?? '';

if (empty($token)) {
    $errors[] = "Lien de réinitialisation invalide.";
} else {
    // Vérifie si le token est valide
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Token = ? AND TokenExpiration > NOW()");
    $stmt->execute([$token]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur) {
        $errors[] = "Lien de réinitialisation invalide ou expiré.";
    }
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($utilisateur)) {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "Tous les champs doivent être remplis.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($newPassword) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Met à jour le mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateur SET MotDePasse = ?, Token = NULL WHERE Id = ?");
        $stmt->execute([$hashedPassword, $utilisateur['Id']]);
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nouveau mot de passe - Horage</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/login_signup.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/navHighlighter.js" defer></script>
    <style>
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .success { color: green; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>
<header>
    <div class="header_1">
        <h1>Horage</h1>
        <img src="img_horage/logo-Photoroom.png" alt="logo de Horage" width="200px">
    </div>
</header>

<main>
    <div class="form-container">
        <h1>Nouveau mot de passe</h1>

        <?php if ($success): ?>
            <div class="success">Mot de passe réinitialisé avec succès. <a href="login.php">Se connecter</a></div>
        <?php elseif (!empty($token) && isset($utilisateur)): ?>
            <form method="POST" action="">
                <label>Nouveau mot de passe :</label>
                <input type="password" name="password" required maxlength="30" placeholder="Nouveau mot de passe" />

                <label>Confirmez le mot de passe :</label>
                <input type="password" name="confirm_password" required maxlength="30" placeholder="Confirmation" />

                <?php foreach ($errors as $error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>

                <input type="submit" value="Réinitialiser le mot de passe">
            </form>
        <?php else: ?>
            <div class="error">Lien invalide ou expiré.</div>
        <?php endif; ?>
    </div>
</main>

<footer>
    <h2>Copyright © Horage</h2>
    <p>Le contenu de ce site est protégé par les lois sur la propriété intellectuelle.</p>
</footer>
</body>
</html>