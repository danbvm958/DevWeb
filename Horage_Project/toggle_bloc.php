<?php
DemarrageSession();
$pdo = DemarrageSQL();
// On ne fait l'action que si c'est un POST et qu'un email est transmis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("UPDATE utilisateur SET Types = ? WHERE OR Email = ?");
    $stmt->execute(["bloque",$email]);
    $_SESSION['user']['type'] = "bloque";
    
?>
