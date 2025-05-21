<?php
function calculerPrixTotalAvecOptions($pdo, $voyage_id, $nb_adultes, $nb_enfants, $options_choisies) {
    // 1. Prix de base
    $stmt = $pdo->prepare("SELECT PrixBase FROM voyages WHERE IdVoyage = ?");
    $stmt->execute([$voyage_id]);
    $voyage = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voyage) throw new Exception("Voyage introuvable");

    $nombre_personnes = $nb_adultes + $nb_enfants;
    $prix_base = floatval($voyage['PrixBase']);
    $prix_total = 0;

    // 2. Réductions
    $stmt = $pdo->prepare("SELECT * FROM reduction WHERE IdVoyage = ?");
    $stmt->execute([$voyage_id]);
    $reductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Réduction groupe
    foreach ($reductions as $reduction) {
        if ($reduction['TypeReduction'] === 'groupe' && $nombre_personnes >= $reduction['ConditionReduction']) {
            $prix_base = floatval($reduction['PrixReduit']);
            break;
        }
    }

    // Prix adultes
    $prix_total += $nb_adultes * $prix_base;

    // Réduction enfants
    $enfant_tarif_reduit = false;
    foreach ($reductions as $reduction) {
        if ($reduction['TypeReduction'] === 'enfant' && $nb_enfants >= $reduction['ConditionReduction']) {
            $prix_total += $nb_enfants * (floatval($prix_base) - floatval($reduction['PrixReduit']));
            $enfant_tarif_reduit = true;
            break;
        }
    }

    // Si pas de réduction enfants
    if (!$enfant_tarif_reduit && $nb_enfants > 0) {
        $prix_total += $nb_enfants * $prix_base;
    }

    // 3. Options disponibles
    $stmt = $pdo->prepare("
        SELECT oe.IdOption, oe.IdEtape, oe.NomOption, 
               co.IdChoix AS IdChoixOption, co.Nom AS NomChoix, co.Prix
        FROM options_etape oe
        JOIN choix_options co ON oe.IdOption = co.IdOption
        WHERE oe.IdEtape IN (
            SELECT IdEtape FROM etapes WHERE IdVoyage = ?
        )
    ");
    $stmt->execute([$voyage_id]);
    $options_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Regrouper les options
    $options_par_etape = [];
    foreach ($options_disponibles as $opt) {
        $id_etape = $opt['IdEtape'];
        $id_option = $opt['IdOption'];

        if (!isset($options_par_etape[$id_etape])) {
            $options_par_etape[$id_etape] = [];
        }

        if (!isset($options_par_etape[$id_etape][$id_option])) {
            $options_par_etape[$id_etape][$id_option] = [
                'nom' => $opt['NomOption'],
                'choix' => []
            ];
        }

        $options_par_etape[$id_etape][$id_option]['choix'][$opt['IdChoixOption']] = [
            'nom' => $opt['NomChoix'],
            'prix' => floatval($opt['Prix'])
        ];
    }

    // 4. Calculer le prix des options choisies
    $etapes_avec_options = [];

    foreach ($options_choisies as $id_etape => $options) {
        if (!isset($options_par_etape[$id_etape])) continue;

        foreach ($options as $id_option => $choix_data) {
            if (!isset($options_par_etape[$id_etape][$id_option])) continue;

            $option = $options_par_etape[$id_etape][$id_option];

            $choix_array = is_array($choix_data) ? $choix_data : [$choix_data];

            foreach ($choix_array as $choix_str) {
                list($id_choix, $prix) = explode('|', $choix_str);
                $id_choix = intval($id_choix);
                $prix = floatval($prix);

                if (isset($option['choix'][$id_choix])) {
                    $etapes_avec_options[$id_etape][] = [
                        'id_etape' => $id_etape,
                        'id_option' => $id_option,
                        'id_choix' => $id_choix,
                        'nom' => $option['nom'],
                        'choix' => $option['choix'][$id_choix]['nom'],
                        'prix' => $prix
                    ];
                    $prix_total += $prix * $nombre_personnes;
                }
            }
        }
    }

    return [
        'prix_total' => $prix_total,
        'etapes_avec_options' => $etapes_avec_options,
        'prix_base' => $voyage['PrixBase'],
        'nombre_personnes' => $nombre_personnes
    ];
}
?>
