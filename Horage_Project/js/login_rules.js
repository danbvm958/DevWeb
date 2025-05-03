document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.getElementById('password-input');
    const usernameCounter = document.getElementById('username-counter');
    const passwordCounter = document.getElementById('password-counter');
    
    // Fonction pour afficher les erreurs
    function showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error';
        errorDiv.textContent = message;
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
    
    // Fonction pour supprimer les erreurs existantes
    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => error.remove());
    }
    
    // Compteur de caractères
    function updateCharacterCounter(input, counter, maxLength) {
        const remaining = maxLength - input.value.length;
        counter.textContent = `${input.value.length}/${maxLength} caractères`;
        
        if (remaining < 0) {
            counter.style.color = 'red';
            input.value = input.value.substring(0, maxLength);
        } else if (remaining < 5) {
            counter.style.color = 'orange';
        } else {
            counter.style.color = '#666';
        }
    }
    
    // Initialisation des compteurs
    updateCharacterCounter(usernameInput, usernameCounter, 20);
    updateCharacterCounter(passwordInput, passwordCounter, 30);
    
    // Écouteurs d'événements pour les compteurs
    usernameInput.addEventListener('input', function() {
        clearErrors();
        updateCharacterCounter(this, usernameCounter, 20);
    });
    
    passwordInput.addEventListener('input', function() {
        clearErrors();
        updateCharacterCounter(this, passwordCounter, 30);
    });
    
    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        clearErrors();
        let isValid = true;
        
        if (usernameInput.value.length < 1) {
            showError(usernameInput, "Le nom d'utilisateur doit faire au moins 1 caractère");
            isValid = false;
        }
        
        if (passwordInput.value.length < 8) {
            showError(passwordInput, "Le mot de passe doit faire au moins 8 caractères");
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Fonctionnalité œil pour le mot de passe
    function togglePasswordVisibility(input, button) {
        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '👁️';
            button.classList.add('large');
        } else {
            input.type = 'password';
            button.textContent = '👁️';
            button.classList.remove('large');
        }
    }
    
    // Ajout du bouton œil pour le mot de passe
    const eyeButton = document.createElement('button');
    eyeButton.type = 'button';
    eyeButton.textContent = '👁️';
    eyeButton.className = 'eye-button';
    
    eyeButton.addEventListener('click', () => {
        togglePasswordVisibility(passwordInput, eyeButton);
    });
    
    passwordInput.parentNode.appendChild(eyeButton);
});
