document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = {
        username: document.querySelector('input[name="username"]'),
        mail1: document.querySelector('input[name="mail1"]'),
        mail2: document.querySelector('input[name="mail2"]'),
        password1: document.querySelector('input[name="password1"]'),
        password2: document.querySelector('input[name="password2"]'),
        nom: document.querySelector('input[name="nom"]'),
        prenom: document.querySelector('input[name="prenom"]'),
        birthdate: document.querySelector('input[name="birthdate"]')
    };
    
    function showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error';
        errorDiv.textContent = message;
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
    
    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => {
            if (!error.classList.contains('php-error')) {
                error.remove();
            }
        });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function validateDate(date) {
        const today = new Date();
        const birthDate = new Date(date);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        return age >= 18;
    }
    
    Object.keys(inputs).forEach(key => {
        inputs[key].addEventListener('input', function() {
            clearErrors();
            
            if (key === 'mail1' && !validateEmail(this.value)) {
                showError(this, "Email invalide");
            }
            
            if (key === 'mail2' && this.value !== inputs.mail1.value) {
                showError(this, "Les emails ne correspondent pas");
            }
            
            if (key === 'password1' && this.value.length < 8) {
                showError(this, "Le mot de passe doit faire au moins 8 caractères");
            }
            
            if (key === 'password2' && this.value !== inputs.password1.value) {
                showError(this, "Les mots de passe ne correspondent pas");
            }
            
            if (key === 'birthdate' && !validateDate(this.value)) {
                showError(this, "Vous devez avoir au moins 18 ans");
            }
        });
    });
    
    form.addEventListener('submit', function(e) {
        clearErrors();
        let isValid = true;
        
        Object.keys(inputs).forEach(key => {
            if (!inputs[key].value.trim()) {
                showError(inputs[key], "Ce champ est obligatoire");
                isValid = false;
            }
        });
        
        if (!validateEmail(inputs.mail1.value)) {
            showError(inputs.mail1, "Email invalide");
            isValid = false;
        }
        
        if (inputs.mail1.value !== inputs.mail2.value) {
            showError(inputs.mail2, "Les emails ne correspondent pas");
            isValid = false;
        }
        
        if (inputs.password1.value.length < 8) {
            showError(inputs.password1, "Le mot de passe doit faire au moins 8 caractères");
            isValid = false;
        }
        
        if (inputs.password1.value !== inputs.password2.value) {
            showError(inputs.password2, "Les mots de passe ne correspondent pas");
            isValid = false;
        }
        
        if (!validateDate(inputs.birthdate.value)) {
            showError(inputs.birthdate, "Vous devez avoir au moins 18 ans");
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
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
    
    [inputs.password1, inputs.password2].forEach(input => {
        const eyeButton = document.createElement('button');
        eyeButton.type = 'button';
        eyeButton.textContent = '👁️';
        eyeButton.className = 'eye-button';
        
        eyeButton.addEventListener('click', () => {
            togglePasswordVisibility(input, eyeButton);
        });
        
        input.parentNode.appendChild(eyeButton);
    });
    
    function setupCharacterCounter(input, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        input.insertAdjacentElement('afterend', counter);

        input.addEventListener('input', () => {
            const remaining = maxLength - input.value.length;
            counter.textContent = `${input.value.length}/${maxLength} caractères`;
            
            if (remaining < 0) {
                counter.style.color = 'red';
                input.value = input.value.substring(0, maxLength);
            } else if (remaining < 10) {
                counter.style.color = 'orange';
            } else {
                counter.style.color = '#666';
            }
        });
    }
    
    setupCharacterCounter(inputs.username, 20);
    setupCharacterCounter(inputs.password1, 30);
    setupCharacterCounter(inputs.password2, 30);
});