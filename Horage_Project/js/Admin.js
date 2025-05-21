document.querySelectorAll('.btn-toggle-vip').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var email = this.dataset.email;
        var btnRef = this;

        btnRef.disabled = true;
        btnRef.classList.add('en-attente');

        setTimeout(function() {
            fetch('toggle_vip.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btnRef.textContent = data.newType === "vip" ? "Oui" : "Non";
                    btnRef.dataset.type = data.newType;
                    if (data.newType === "vip") {
                        btnRef.classList.add('btn-vip');
                        btnRef.classList.remove('btn-blocked');
                    } else {
                        btnRef.classList.remove('btn-vip');
                        btnRef.classList.add('btn-blocked');
                    }
                } else {
                    alert("Impossible de modifier le statut VIP !");
                }
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            })
            .catch(error => {
                alert("Erreur lors de la requête.");
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            });
        }, 2000);
    });
});
// Même principe pour le bouton "Bloqué"
document.querySelectorAll('.btn-toggle-bloc').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var email = this.dataset.email;
        var btnRef = this;

        btnRef.disabled = true;
        btnRef.classList.add('en-attente');

        setTimeout(function() {
            fetch('toggle_bloc.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btnRef.textContent = data.newEtat === "oui" ? "Oui" : "Non";
                    btnRef.dataset.bloque = data.newEtat;
                    if (data.newEtat === "oui") {
                        btnRef.classList.add('btn-blocked');
                        btnRef.classList.remove('btn-vip');
                    } else {
                        btnRef.classList.remove('btn-blocked');
                        btnRef.classList.add('btn-vip');
                    }
                } else {
                    alert("Impossible de modifier le statut BLOQUÉ !");
                }
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            })
            .catch(error => {
                alert("Erreur lors de la requête.");
                btnRef.disabled = false;
                btnRef.classList.remove('en-attente');
            });
        }, 2000);
    });
});
