document.addEventListener('DOMContentLoaded', function() {
    const inputAdultes = document.getElementById('nb_adultes');
    const inputEnfants = document.getElementById('nb_enfants');
    const affichagePrix = document.getElementById('total-price');
    const etapesContainer = document.getElementById('etapes-container');
    
    // Charger les options au démarrage
    chargerOptions();

    function chargerOptions() {
        fetch(`get_options.php?id=${voyageData.id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(optionsParEtape => {
                afficherOptions(optionsParEtape);
                attacherEcouteurs();
                calculerPrixTotal();
            })
            .catch(error => {
                etapesContainer.innerHTML = `<div class="error">Erreur lors du chargement des options: ${error.message}</div>`;
                console.error('Erreur:', error);
            });
    }

    function afficherOptions(optionsParEtape) {
    if (optionsParEtape.length === 0) {
        etapesContainer.innerHTML = '<div class="no-options">Aucune option disponible pour ce voyage</div>';
        return;
    }

    let html = '<section class="etapes"><h2>Personnalisation des options</h2>';

    optionsParEtape.forEach(etapeData => {
        const etape = etapeData.etape;

        html += `
            <div class="etape">
                <h3>${etape.titre} (${etape.dates})</h3>
                <div class="options">
        `;

        etapeData.options.forEach(option => {
            html += `<div class="option-group"><h4>${option.nom}</h4><ul>`;

            if (option.choix.length > 0) {
                option.choix.forEach(choix => {
                    const inputName = `options[${etape.id}][${option.id}]`;
                    const inputType = (option.nom.toLowerCase() === 'activité') ? 'checkbox' : 'radio';
                    const inputId = `option-${etape.id}-${option.id}-${choix.id}`;
                    const inputValue = `${choix.id}|${choix.prix}`;

                    html += `
                        <li>
                            <label>
                                <input type="${inputType}" 
                                       id="${inputId}"
                                       name="${inputName}${inputType === 'checkbox' ? '[]' : ''}"
                                       value="${inputValue}"
                                       data-prix="${choix.prix}"
                                       data-etape="${etape.id}"
                                       data-option="${option.id}">
                                ${choix.nom} (+${formatPrix(choix.prix)})
                            </label>
                        </li>
                    `;
                });
            } else {
                html += `<li><em>Aucun choix disponible</em></li>`;
            }

            html += `</ul></div>`; 
        });

        html += `</div></div>`; 
    });

    html += `
        </section>`;

    etapesContainer.innerHTML = html;
}


    function attacherEcouteurs() {
        inputAdultes.addEventListener('input', calculerPrixTotal);
        inputEnfants.addEventListener('input', calculerPrixTotal);
        
        document.querySelectorAll('input[type="radio"],input[type="checkbox"]').forEach(radio => {
            radio.addEventListener('change', calculerPrixTotal);
        });
    }

    function calculerPrixTotal() {
        const nbAdultes = parseInt(inputAdultes.value) || 0;
        const nbEnfants = parseInt(inputEnfants.value) || 0;
        const totalPersonnes = nbAdultes + nbEnfants;
        
        if (nbAdultes < 1 || nbEnfants < 0 || totalPersonnes > voyageData.placesDisponibles) {
            affichagePrix.textContent = "0 €";
            return;
        }
        
        let prixBase = voyageData.prixBase;
        let prixTotal = 0;
        
        // Appliquer les réductions de groupe
        voyageData.reductions.forEach(reduction => {
            if (reduction.TypeReduction === 'groupe' && 
                totalPersonnes >= reduction.ConditionReduction) {
                prixBase = reduction.PrixReduit;
            }
        });
        
        prixTotal += nbAdultes * prixBase;
        
        // Appliquer les réductions pour enfants
        let reductionEnfantAppliquee = false;
        voyageData.reductions.forEach(reduction => {
            if (!reductionEnfantAppliquee && 
                reduction.TypeReduction === 'enfant' && 
                nbEnfants >= reduction.ConditionReduction) {
                prixTotal += nbEnfants * (prixBase - reduction.PrixReduit);
                reductionEnfantAppliquee = true;
            }
        });
        
        if (!reductionEnfantAppliquee) {
            prixTotal += nbEnfants * prixBase;
        }
        
        // Ajouter le prix des options sélectionnées
        document.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked').forEach(option => {
            const prixOption = parseFloat(option.dataset.prix);
            prixTotal += prixOption * totalPersonnes;
        });
        
        affichagePrix.textContent = formatPrix(Math.round(prixTotal));
    }
});