document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!username || !email || !password) {
            alert('Todos los campos son obligatorios');
            return;
        }
        if (!emailRegex.test(email)) {
            alert('Por favor, ingresa un correo válido');
            return;
        }
        if (password.length < 8) {
            alert('La contraseña debe tener al menos 8 caracteres');
            return;
        }

        const formData = new FormData(this);

        fetch('../action/authAction.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Usuario registrado correctamente');
                window.location.href = '../index.php?view=login';
            } else {
                // Manejo de errores específicos como en Kotlin
                const errorMsg = data.message || 
                    (data.error === 'VALIDATION_ERROR' ? 'Datos de registro inválidos' : 
                    'Error en el registro, por favor intenta nuevamente');
                alert(errorMsg);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = error.message || 
                (error.error === 'VALIDATION_ERROR' ? 'Datos de registro inválidos' : 
                'Error en el servidor, inténtalo más tarde');
            alert(errorMsg);
        });
    });
});


document.getElementById('loginLink').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = '../index.php?view=login';
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.querySelector(`#${fieldId} + .show-password`);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
    icon.innerHTML = type === 'password' ? '&#128065;' : '&#128064;';
}