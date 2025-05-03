<?php
// On ne fait l'action que si c'est un POST et qu'un email est transmis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $file = 'data/utilisateur.json';

    // Charger tous les utilisateurs
    $users = json_decode(file_get_contents($file), true);
    $found = false; 
    $newType = "";

    // On cherche l'utilisateur par son email, et on alterne son type
    foreach ($users as &$user) {
        if ($user['email'] === $email && $user['type'] !== 'admin') {
            if ($user['type'] == 'vip') {
                $user['type'] = 'normal';
            } else {
                $user['type'] = 'vip';
            }
            $newType = $user['type']; // garder le nouveau type
            $found = true;
            break;
        }
    }
    // Si on a trouvé l'utilisateur, on réécrit le fichier JSON
    if($found) {
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['success'=>true, 'newType'=>$newType]);
    } else {
        echo json_encode(['success'=>false]);
    }
    exit;
}
echo json_encode(['success'=>false]);
?>
