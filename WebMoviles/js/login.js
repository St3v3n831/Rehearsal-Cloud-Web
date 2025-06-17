document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('../action/authAction.php', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Login exitoso!');
            // Redirigir o mostrar datos
            window.location.href = 'dashboard.php'; // ejemplo
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error(err));
});


// Función para mostrar alertas
function showAlert(type, message) {
    const alertBox = document.createElement('div');
    alertBox.className = `alert ${type}`;
    alertBox.textContent = message;
    
    // Insertar antes del formulario
    const form = document.getElementById('loginForm');
    form.parentNode.insertBefore(alertBox, form);
    
    // Eliminar después de 5 segundos
    setTimeout(() => alertBox.remove(), 5000);
}


document.getElementById('signupLink').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = '../index.php?view=signup';
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.querySelector(`#${fieldId} + .show-password`);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
    icon.innerHTML = type === 'password' ? '&#128065;' : '&#128064;';
}