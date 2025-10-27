// Gestion du menu déroulant dans la sidebar
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les éléments avec sous-menu
    const menuItems = document.querySelectorAll('.nav-item.has-submenu');
    
    menuItems.forEach(function(item) {
        const link = item.querySelector('.nav-link');
        const submenu = item.querySelector('.submenu');
        
        if (link && submenu) {
            // Toggle submenu au clic
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Fermer les autres sous-menus
                menuItems.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.classList.remove('open');
                    }
                });
                
                // Toggle le sous-menu actuel
                item.classList.toggle('open');
            });
        }
    });
    
    // Ouvrir automatiquement le sous-menu si une page enfant est active
    menuItems.forEach(function(item) {
        const activeLink = item.querySelector('.submenu .nav-link.active');
        if (activeLink) {
            item.classList.add('open');
        }
    });
});
