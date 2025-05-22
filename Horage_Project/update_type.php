<?php
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['newType'])) {
    $email = $_POST['email'];
    $newType = $_POST['newType'];

    $validTypes = ['normal', 'vip', 'bloque','admin'];

    if (!in_array($newType, $validTypes)) {
        echo json_encode(['success' => false, 'msg' => 'Type invalide']);
        exit;
    }

    // Vérifie que l’utilisateur existe
    $stmt = $pdo->prepare("SELECT Types FROM utilisateur WHERE Email = ?");
    $stmt->execute([$email]);
    $currentType = $stmt->fetchColumn();

    if ($currentType === false) {
        echo json_encode(['success' => false, 'msg' => 'Utilisateur introuvable']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE utilisateur SET Types = ? WHERE Email = ?");
    $stmt->execute([$newType, $email]);

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'msg' => 'Requête invalide']);
exit;
