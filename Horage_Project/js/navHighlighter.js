// VERSION ULTRA-DEBUG
document.addEventListener('DOMContentLoaded', () => {
    console.log('=== DÉBUT DU DEBUG ===');
    console.log('URL complète:', window.location.href);
    
    const currentPage = window.location.pathname.split('/').pop();
    console.log('Page actuelle:', currentPage);

    const navLinks = document.querySelectorAll('.nav a');
    console.log(`${navLinks.length} liens trouvés dans la nav`);

    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        console.log('Test lien:', linkHref);
        
        if (currentPage === linkHref) {
            console.log('MATCH TROUVÉ:', linkHref);
            link.parentElement.classList.add('active');
            link.style.border = '2px dashed lime !important'; // Marquage visuel
        }
    });
    
    console.log('=== FIN DU DEBUG ===');
});