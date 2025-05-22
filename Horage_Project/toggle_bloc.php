<?php
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT Types FROM utilisateur WHERE Email = ?");
    $stmt->execute([$email]);
    $currentType = $stmt->fetchColumn();

    if ($currentType === false) {
        echo json_encode(['success' => false, 'msg' => 'Utilisateur introuvable']);
        exit;
    }

    if ($currentType === "bloque") {
        // Débloquer = repasser en "normal" SYSTEMATIQUEMENT
        $newType = "normal";
    } else {
        // Quel que soit l'état, bloquer = "bloque"
        $newType = "bloque";
    }

    $stmt = $pdo->prepare("UPDATE utilisateur SET Types = ? WHERE Email = ?");
    $stmt->execute([$newType, $email]);
    echo json_encode([
        'success' => true,
        'newType' => $newType
    ]);
    exit;
}
echo json_encode(['success' => false, 'msg' => 'Requête invalide']);
exit;
