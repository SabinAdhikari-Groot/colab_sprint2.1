document.getElementById('loginForm').onsubmit = function(event) {
    event.preventDefault();
    let form = this;
    let formData = new FormData(form);

    // Send POST request to PHP script
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            window.location.href = 'nhomepage.html';
        } else {
            // Display error message if login fails
            let errorMessage = document.getElementById('errorMessage');
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'Invalid username or password.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
};
