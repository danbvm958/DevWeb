<?php
require_once 'session.php';
$pdo = DemarrageSQL();

$idVoyage = $_GET['id'] ?? null;

if (empty($idVoyage)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'ID de voyage manquant']);
    exit;
}

try {
    // Récupérer les étapes du voyage
    $stmt = $pdo->prepare("SELECT * FROM etapes WHERE IdVoyage = ? ORDER BY IdEtape");
    $stmt->execute([$idVoyage]);
    $etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $optionsParEtape = [];

    foreach ($etapes as $etape) {
        // Récupérer les options pour chaque étape
        $stmt = $pdo->prepare("
            SELECT oe.IdOption, oe.NomOption, co.IdChoix, co.Nom, co.Prix 
            FROM options_etape oe
            LEFT JOIN choix_options co ON oe.IdOption = co.IdOption
            WHERE oe.IdEtape = ?
            ORDER BY oe.IdOption
        ");
        $stmt->execute([$etape['IdEtape']]);
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organiser les options par catégorie
        $optionsOrganisees = [];
        foreach ($options as $option) {
            $idOption = $option['IdOption'];
            if (!isset($optionsOrganisees[$idOption])) {
                $optionsOrganisees[$idOption] = [
                    'id' => $option['IdOption'],
                    'nom' => $option['NomOption'],
                    'choix' => []
                ];
            }
            if ($option['IdChoix']) {
                $optionsOrganisees[$idOption]['choix'][] = [
                    'id' => $option['IdChoix'],
                    'nom' => $option['Nom'],
                    'prix' => $option['Prix']
                ];
            }
        }

        if (!empty($optionsOrganisees)) {
            $optionsParEtape[] = [
                'etape' => [
                    'id' => $etape['IdEtape'],
                    'titre' => $etape['Titre'],
                    'dates' => $etape['DateArrive'] . ' au ' . $etape['DateDepart']
                ],
                'options' => array_values($optionsOrganisees)
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($optionsParEtape);

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>