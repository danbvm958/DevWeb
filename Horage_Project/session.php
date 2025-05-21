<?php

function DemarrageSession(){
    session_start();
}

function DemarrageSQL(){
    $dsn = 'mysql:host=sql112.infinityfree.com;dbname=if0_38962226_ma_bdd;charset=utf8';
    $user = 'if0_38962226';
    $password = 'DanChadyZied95';
    try {
        $pdo = new PDO($dsn, $user, $password);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function VerificationConnexion(){
    if(isset($_SESSION['user'])){
        if($_SESSION['user']['type'] == "bloque"){
            header("Location: banni.php");
            exit();
        }
        else{
            return 1;
        }
    }
    else{
        return 0;
    }
}

function AfficherHeader() {
    $pageProfil = 'login.php'; 
    $profilLabel = 'Connexion';

    if (VerificationConnexion()== 1) {
        $typeUser = $_SESSION['user']['type'];
        $pageProfil = match ($typeUser) {
            'admin'  => 'profil_admin.php',
            'normal' => 'profil_user.php',
            default  => 'login.php'
        };
        $profilLabel = 'Profil';
    }

    echo <<<HTML
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
            <li><a href="Recherche.php" class="a1">Reserver</a></li>
            <li><a href="$pageProfil" class="a1">$profilLabel</a></li>
            <li><a href="contact.php" class="a1">Contacts</a></li>
            <li><a href="panier.php" class="a1">Panier</a></li>
        </ul>
    </div>
</header>
HTML;
}






?>