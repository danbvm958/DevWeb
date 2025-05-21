// localStorageCleanup.js
// On supprime le thème enregistré en local lors du développement
// pour éviter les conflits avec les anciennes versions
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    localStorage.removeItem('theme');
    localStorage.removeItem('theme_v1'); // On supprime aussi les anciennes versions du thème
}