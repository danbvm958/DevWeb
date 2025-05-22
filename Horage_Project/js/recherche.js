const PAGE_SIZE = 3;
let currentPage = 1;

function getAllCards() {
    return Array.from(document.querySelectorAll('.travel-card'));
}

function getFilteredCards() {
    return getAllCards().filter(card => card.dataset.filtered !== "false");
}

function updatePagination() {
    const container = document.getElementById('voyagesContainer');
    const cards = getFilteredCards();
    const pagination = document.getElementById('pagination-controls');
    const nPages = Math.ceil(cards.length / PAGE_SIZE) || 1;

    if(currentPage > nPages) currentPage = nPages;
    if(currentPage < 1) currentPage = 1;

    getAllCards().forEach(card => card.style.display = "none");
    cards.forEach((card, idx) => {
        card.style.display = (idx >= (currentPage-1)*PAGE_SIZE && idx < currentPage*PAGE_SIZE) ? "" : "none";
    });

    pagination.innerHTML = '';
    if(cards.length === 0) return;

    const prevBtn = document.createElement("a");
    prevBtn.textContent = "« Précédent";
    prevBtn.classList.add('pagination-btn');
    if(currentPage === 1){
        prevBtn.classList.add('disabled');
    } else {
        prevBtn.href = "#";
        prevBtn.onclick = (e) => { e.preventDefault(); currentPage--; updatePagination(); };
    }
    pagination.appendChild(prevBtn);

    const nMaxPagesShow = 3;
    let first = Math.max(1, currentPage - Math.floor(nMaxPagesShow/2));
    let last = Math.min(nPages, first + nMaxPagesShow - 1);
    first = Math.max(1, last - nMaxPagesShow + 1);

    for(let i=first; i<=last; ++i) {
        let b = document.createElement("a");
        b.textContent = i;
        b.classList.add('pagination-btn');
        if(i === currentPage) {
            b.classList.add('active');
        } else {
            b.href = "#";
            b.onclick = (e) => { e.preventDefault(); currentPage = i; updatePagination(); };
        }
        pagination.appendChild(b);
    }

    const nextBtn = document.createElement("a");
    nextBtn.textContent = "Suivant »";
    nextBtn.classList.add('pagination-btn');
    if(currentPage === nPages){
        nextBtn.classList.add('disabled');
    } else {
        nextBtn.href = "#";
        nextBtn.onclick = (e) => { e.preventDefault(); currentPage++; updatePagination(); };
    }
    pagination.appendChild(nextBtn);
}

function getValue(card, crit) {
    if (crit === 'prix') return Number(card.dataset.prix) || 0;
    if (crit === 'date') return new Date(card.dataset.date).getTime() || 0;
    if (crit === 'duree') return Number(card.dataset.duree) || 0;
    return 0;
}

function filterByPays() {
    const val = document.getElementById('filtrePays')?.value || "tous";
    getAllCards().forEach(card => {
        card.dataset.filtered = (val === "tous" || card.dataset.pays === val) ? "true" : "false";
    });
}

function trierVoyagesMulti() {
    const c1 = document.getElementById('tri1')?.value;
    const s1 = document.getElementById('sens1')?.value;
    const c2 = document.getElementById('tri2')?.value;
    const s2 = document.getElementById('sens2')?.value;
    const c3 = document.getElementById('tri3')?.value;
    const s3 = document.getElementById('sens3')?.value;

    const criteria = [];
    if (c1 && c1 !== "none") criteria.push([c1, s1]);
    if (c2 && c2 !== "none") criteria.push([c2, s2]);
    if (c3 && c3 !== "none") criteria.push([c3, s3]);

    let cards = getAllCards();
    cards.sort((a, b) => {
        for(let i=0; i<criteria.length; ++i) {
            const [crit, sens] = criteria[i];
            const diff = (getValue(a, crit) - getValue(b, crit)) * (sens==="asc"?1:-1);
            if(diff !== 0) return diff;
        }
        return Number(a.dataset.index) - Number(b.dataset.index);
    });
    const container = document.getElementById('voyagesContainer');
    cards.forEach(card => container.appendChild(card));
}

['filtrePays','tri1','sens1','tri2','sens2','tri3','sens3'].forEach(id=> {
    const el = document.getElementById(id);
    if(el){
        el.addEventListener('change', function(){
            filterByPays();
            trierVoyagesMulti();
            currentPage = 1;
            updatePagination();
        });
    }
});

window.addEventListener('DOMContentLoaded', function() {
    filterByPays();
    trierVoyagesMulti();
    updatePagination();
});