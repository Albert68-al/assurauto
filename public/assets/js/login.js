// assets/js/login.js
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('loginPassword');
    const loginButton = document.querySelector('.login-button');
    const errorMessage = document.getElementById('loginError');

    // Toggle password visibility
    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Form submission
    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('loginEmail').value;
        const password = passwordInput.value;

        // Show loading state
        loginButton.classList.add('loading');
        errorMessage.style.display = 'none';

        try {
            const response = await fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                // Store token and user data
                localStorage.setItem('assur_token', data.token);
                localStorage.setItem('assur_user', JSON.stringify(data.user));

                // Redirect to dashboard
                window.location.href = 'index.html';
            } else {
                throw new Error(data.error || 'Erreur de connexion');
            }
        } catch (error) {
            // Show error message
            errorMessage.textContent = error.message;
            errorMessage.style.display = 'block';

            // Add shake animation to form
            loginForm.style.animation = 'shake 0.5s ease';
            setTimeout(() => {
                loginForm.style.animation = '';
            }, 500);
        } finally {
            // Hide loading state
            loginButton.classList.remove('loading');
        }
    });

    // Auto-fill demo accounts for testing
    const urlParams = new URLSearchParams(window.location.search);
    const demoAccount = urlParams.get('demo');

    if (demoAccount) {
        const accounts = {
            admin: { email: 'admin@example.com', password: 'admin123' },
            agent: { email: 'agent@example.com', password: 'agent123' },
            viewer: { email: 'viewer@example.com', password: 'viewer123' }
        };

        const account = accounts[demoAccount];
        if (account) {
            document.getElementById('loginEmail').value = account.email;
            passwordInput.value = account.password;
        }
    }
    // Fonction pour basculer la visibilité du mot de passe
function togglePassword(inputId, button) {
  const input = document.getElementById(inputId);
  const icon = button.querySelector('i');
  
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

// Gestionnaire pour le lien "Déjà inscrit ?"
document.getElementById('switchToLogin')?.addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('loginTab').click();
});
});