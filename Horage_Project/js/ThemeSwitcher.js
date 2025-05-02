document.addEventListener('DOMContentLoaded', function() {
    // Supprimer les anciens boutons
    document.querySelectorAll('.theme-btn, .theme-container').forEach(el => el.remove());

    // Créer le conteneur minimaliste
    const btnContainer = document.createElement('div');
    btnContainer.className = 'theme-container';
    btnContainer.style.position = 'fixed';
    btnContainer.style.bottom = '15px';
    btnContainer.style.right = '15px';
    btnContainer.style.zIndex = '1000';
    btnContainer.style.display = 'flex';
    btnContainer.style.gap = '8px';
    document.body.appendChild(btnContainer);

    // Configuration des thèmes
    const themes = [
        { id: 'light', icon: '🌞', css: 'CSS/light.css' },
        { id: 'accessibility', icon: '👁️', css: 'CSS/accessibility.css' },
        { id: 'dark', icon: '🌙', css: 'CSS/root.css' }
    ];

    // Fonction pour gérer les cookies
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
    }

    // Création des boutons
    themes.forEach(theme => {
        const btn = document.createElement('button');
        btn.className = 'theme-btn';
        btn.innerHTML = theme.icon;
        btn.style.cssText = `
            font-size: 20px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
            transition: transform 0.2s;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.7;
        `;
        
        btn.addEventListener('click', () => {
            setTheme(theme.id);
        });
        
        btnContainer.appendChild(btn);
    });

    // Déterminer le thème initial
    function getInitialTheme() {
        // 1. Vérifier d'abord le cookie
        const cookieTheme = getCookie('user_theme');
        if (cookieTheme) return cookieTheme;
        
        // 2. Sinon vérifier le localStorage
        const localStorageTheme = localStorage.getItem('theme_v3');
        if (localStorageTheme) return localStorageTheme;
        
        // 3. Sinon utiliser le thème par défaut
        return 'dark';
    }

    // Appliquer le thème
    const initialTheme = getInitialTheme();
    setTheme(initialTheme);
    

    function setTheme(themeId) {
        const theme = themes.find(t => t.id === themeId) || themes[0];
        
        // Chargement CSS
        let themeLink = document.getElementById('theme-style');
        if (!themeLink) {
            themeLink = document.createElement('link');
            themeLink.id = 'theme-style';
            themeLink.rel = 'stylesheet';
            document.head.appendChild(themeLink);
        }
        themeLink.href = `${theme.css}?v=${new Date().getTime()}`;
        
        // Mise à jour des boutons
        document.querySelectorAll('.theme-btn').forEach((btn, index) => {
            const isActive = themes[index].id === themeId;
            btn.style.transform = isActive ? 'scale(1.3)' : 'scale(1)';
            btn.style.opacity = isActive ? '1' : '0.7';
        });
        
        // Sauvegarde dans localStorage et cookie
        localStorage.setItem('theme_v3', themeId);
        setCookie('user_theme', themeId, 365); // Cookie valable 1 an
        
        // Si l'utilisateur est connecté, vous pourriez aussi sauvegarder côté serveur
        // via une requête AJAX ici si nécessaire
    }
});