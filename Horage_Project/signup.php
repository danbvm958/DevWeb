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
            'type' => 'normal',
            'travel' => [],
            'panier' => []
        ];
        $users[] = $newUser;
        saveUsers($jsonFile, $users);
    }
    header("Location: login.php");
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
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = {
        username: document.querySelector('input[name="username"]'),
        mail1: document.querySelector('input[name="mail1"]'),
        mail2: document.querySelector('input[name="mail2"]'),
        password1: document.querySelector('input[name="password1"]'),
        password2: document.querySelector('input[name="password2"]'),
        nom: document.querySelector('input[name="nom"]'),
        prenom: document.querySelector('input[name="prenom"]'),
        birthdate: document.querySelector('input[name="birthdate"]')
    };
    
    // Fonctions utilitaires
    function showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error';
        errorDiv.textContent = message;
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
    
    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => {
            if (!error.classList.contains('php-error')) {
                error.remove();
            }
        });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function validateDate(date) {
        const today = new Date();
        const birthDate = new Date(date);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        return age >= 18;
    }
    
    // Validation en temps réel
    Object.keys(inputs).forEach(key => {
        inputs[key].addEventListener('input', function() {
            clearErrors();
            
            // Validation spécifique pour chaque champ
            if (key === 'mail1' && !validateEmail(this.value)) {
                showError(this, "Email invalide");
            }
            
            if (key === 'mail2' && this.value !== inputs.mail1.value) {
                showError(this, "Les emails ne correspondent pas");
            }
            
            if (key === 'password1' && this.value.length < 8) {
                showError(this, "Le mot de passe doit faire au moins 8 caractères");
            }
            
            if (key === 'password2' && this.value !== inputs.password1.value) {
                showError(this, "Les mots de passe ne correspondent pas");
            }
            
            if (key === 'birthdate' && !validateDate(this.value)) {
                showError(this, "Vous devez avoir au moins 18 ans");
            }
        });
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        clearErrors();
        let isValid = true;
        
        // Vérification des champs vides
        Object.keys(inputs).forEach(key => {
            if (!inputs[key].value.trim()) {
                showError(inputs[key], "Ce champ est obligatoire");
                isValid = false;
            }
        });
        
        // Validation spécifique
        if (!validateEmail(inputs.mail1.value)) {
            showError(inputs.mail1, "Email invalide");
            isValid = false;
        }
        
        if (inputs.mail1.value !== inputs.mail2.value) {
            showError(inputs.mail2, "Les emails ne correspondent pas");
            isValid = false;
        }
        
        if (inputs.password1.value.length < 8) {
            showError(inputs.password1, "Le mot de passe doit faire au moins 8 caractères");
            isValid = false;
        }
        
        if (inputs.password1.value !== inputs.password2.value) {
            showError(inputs.password2, "Les mots de passe ne correspondent pas");
            isValid = false;
        }
        
        if (!validateDate(inputs.birthdate.value)) {
            showError(inputs.birthdate, "Vous devez avoir au moins 18 ans");
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Fonctionnalité œil pour les mots de passe
    function togglePasswordVisibility(input, button) {
        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '👁️';
        } else {
            input.type = 'password';
            button.textContent = '👁️';
        }
    }
    
    // Ajout des boutons œil
    [inputs.password1, inputs.password2].forEach(input => {
        const eyeButton = document.createElement('button');
        eyeButton.type = 'button';
        eyeButton.textContent = '👁️';
        eyeButton.style.marginLeft = '5px';
        eyeButton.style.background = 'none';
        eyeButton.style.border = 'none';
        eyeButton.style.cursor = 'pointer';
        
        eyeButton.addEventListener('click', () => {
            togglePasswordVisibility(input, eyeButton);
        });
        
        input.parentNode.appendChild(eyeButton);
    });
    
    // Compteurs de caractères
    function setupCharacterCounter(input, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.fontSize = '0.8em';
        counter.style.color = '#666';
        input.parentNode.appendChild(counter);
        
        input.addEventListener('input', () => {
            const remaining = maxLength - input.value.length;
            counter.textContent = `${input.value.length}/${maxLength} caractères`;
            
            if (remaining < 0) {
                counter.style.color = 'red';
                input.value = input.value.substring(0, maxLength);
            } else if (remaining < 10) {
                counter.style.color = 'orange';
            } else {
                counter.style.color = '#666';
            }
        });
    }
    
    // Configuration des compteurs
    setupCharacterCounter(inputs.username, 20);
    setupCharacterCounter(inputs.password1, 30);
    setupCharacterCounter(inputs.password2, 30);
});
</script>
</body>
</html>
</body>
</html>
