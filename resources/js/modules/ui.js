// Module UI - Gestion des interactions utilisateur

export function initClickableRows() {
    // Rendre les lignes du tableau cliquables
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(row => {
        row.addEventListener("click", function() {
            window.location.href = this.dataset.href;
        });
    });
}

export function initHeartBeatAnimation() {
    const heartIcon = document.querySelector('.heartBeat-active');
    if (heartIcon) {
        // Ajouter les styles pour l'animation
        heartIcon.style.fontSize = '15rem';
        heartIcon.style.color = '#dc3545';
        heartIcon.style.animation = 'heartBeat 1.5s ease-in-out infinite';
        
        // Créer les keyframes si elles n'existent pas
        if (!document.querySelector('#heartBeatStyles')) {
            const style = document.createElement('style');
            style.id = 'heartBeatStyles';
            style.textContent = `
                @keyframes heartBeat {
                    0% { transform: scale(1); }
                    14% { transform: scale(1.3); }
                    28% { transform: scale(1); }
                    42% { transform: scale(1.3); }
                    70% { transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
        }
    }
}

export function initUI() {
    initClickableRows();
    initHeartBeatAnimation();
}
