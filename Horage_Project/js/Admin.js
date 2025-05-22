document.querySelectorAll('.select-type').forEach(function(select) {
    select.addEventListener('change', function() {
        const email = this.dataset.email;
        const newType = this.value;
        const selectRef = this;

        const loader = document.getElementById('global-loader');
        if (loader) loader.style.display = 'flex';

        selectRef.disabled = true;
        selectRef.classList.add('en-attente');

        const startTime = Date.now(); // pour mesurer la durée réelle

        fetch('update_type.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'email=' + encodeURIComponent(email) + '&newType=' + encodeURIComponent(newType)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.msg || "Erreur lors de la mise à jour.");
                selectRef.value = data.currentType;
            }
        })
        .catch(() => {
            alert("Erreur de connexion au serveur.");
        })
        .finally(() => {
            const elapsed = Date.now() - startTime;
            const delay = Math.max(2000 - elapsed, 0); // temps restant jusqu'à 2s

            setTimeout(() => {
                selectRef.disabled = false;
                selectRef.classList.remove('en-attente');
                if (loader) loader.style.display = 'none';
            }, delay);
        });
    });
});
