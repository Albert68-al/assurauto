// Fonction pour toggle le sous-menu
function toggleSubmenu(element) {
    console.log('=== toggleSubmenu appelé ===');
    console.log('Element cliqué:', element);
    
    // Trouver le parent .has-submenu
    const parent = element.closest('.has-submenu');
    console.log('Parent trouvé:', parent);
    
    if (parent) {
        const submenu = parent.querySelector('.submenu');
        console.log('Submenu trouvé:', submenu);
        
        // Fermer tous les autres sous-menus
        document.querySelectorAll('.has-submenu').forEach(function(item) {
            if (item !== parent) {
                item.classList.remove('open');
                console.log('Fermé un autre menu');
            }
        });
        
        // Toggle le sous-menu actuel
        const wasOpen = parent.classList.contains('open');
        parent.classList.toggle('open');
        const isNowOpen = parent.classList.contains('open');
        
        console.log('Était ouvert:', wasOpen);
        console.log('Est maintenant ouvert:', isNowOpen);
        console.log('Classes du parent:', parent.className);
        
        if (submenu) {
            console.log('Style du submenu:', window.getComputedStyle(submenu).maxHeight);
            console.log('Opacity du submenu:', window.getComputedStyle(submenu).opacity);
        }
    } else {
        console.error('Parent .has-submenu non trouvé!');
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('App.js chargé - Initialisation du menu');
    
    // Ouvrir automatiquement le sous-menu si on est sur une page enfant
    const activeSubmenuLink = document.querySelector('.submenu .submenu-link.active');
    if (activeSubmenuLink) {
        const parent = activeSubmenuLink.closest('.has-submenu');
        if (parent) {
            parent.classList.add('open');
            console.log('Menu ouvert automatiquement car page active');
        }
    }
});
