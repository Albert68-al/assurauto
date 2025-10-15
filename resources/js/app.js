import './bootstrap';
import ApexCharts from 'apexcharts';
// Gestion du chargement
document.addEventListener('DOMContentLoaded', function() {
    // Masquer l'écran de chargement
    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
        loadingScreen.style.display = 'none';
    }

    // Initialisation des tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Gestion des notifications
    window.showNotification = function(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationMessage = notification.querySelector('.notification-message');
        const notificationIcon = notification.querySelector('.notification-icon');
        
        notification.className = `notification ${type}`;
        notificationMessage.textContent = message;
        notification.classList.remove('hidden');
        
        // Ajouter l'icône appropriée
        notificationIcon.className = 'notification-icon';
        if (type === 'success') {
            notificationIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
        } else if (type === 'error') {
            notificationIcon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
        } else if (type === 'warning') {
            notificationIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        } else {
            notificationIcon.innerHTML = '<i class="fas fa-info-circle"></i>';
        }
        
        // Masquer la notification après 5 secondes
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 5000);
    };

    // Gestion des erreurs de formulaire
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
            }
        });
    });
});

// Fonction pour confirmer la suppression
function confirmDelete(event) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
        event.preventDefault();
        return false;
    }
    return true;
}