document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    var firstName = document.getElementById('firstName').value;
    var lastName = document.getElementById('lastName').value;
    var phoneNumber = document.getElementById('phoneNumber').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    // Regular expressions for validation
    var nameRegex = /^[a-zA-Z]+$/;
    var phoneRegex = /^(97|98)\d{8}$/;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // Check if passwords match
    if (password !== confirmPassword) {
        displayError("Passwords do not match.", "confirmPassword");
        return;
    } else {
        clearError("confirmPassword");
    }

    // Validate first name
    if (!nameRegex.test(firstName)) {
        displayError("First name should contain only letters.", "firstName");
        return;
    } else {
        clearError("firstName");
    }

    // Validate last name
    if (!nameRegex.test(lastName)) {
        displayError("Last name should contain only letters.", "lastName");
        return;
    } else {
        clearError("lastName");
    }

    // Validate phone number
    if (!phoneRegex.test(phoneNumber)) {
        displayError("Phone number should be 10 digits starting with 97 or 98.", "phoneNumber");
        return;
    } else {
        clearError("phoneNumber");
    }

    // Validate email
    if (!emailRegex.test(email)) {
        displayError("Invalid email format. Email should end with @gmail.com or similar.", "email");
        return;
    } else {
        clearError("email");
    }

    // Validate password
    if (!passwordRegex.test(password)) {
        displayError("Password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.", "password");
        return;
    } else {
        clearError("password");
    }

    // AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'signup.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            if(response.trim() === "success") {
                console.log("Signup successful!");
                alert("Signup successful!");
                window.location.href = "login.html"; // Redirect to login page
            } else if (response.trim() === "failure_username_exists") {
                alert("Username already exists. Please choose a different username.");
            } else {
                console.log("Signup failed. Please try again later.");
                alert("Signup failed. Please try again later.");
            }
        }
    };
    xhr.send('firstName=' + firstName + '&lastName=' + lastName + '&phoneNumber=' + phoneNumber + '&email=' + email + '&password=' + password);
});

function displayError(message, fieldId) {
    var errorSpan = document.getElementById(fieldId + "Error");
    errorSpan.textContent = message;
    errorSpan.style.display = "block";
}

function clearError(fieldId) {
    var errorSpan = document.getElementById(fieldId + "Error");
    errorSpan.textContent = "";
    errorSpan.style.display = "none";
}document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    // AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'login.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            if (response.trim() === "success") {
                console.log("Login successful!");
                alert("Login successful!");
                window.location.href = "dashboard.html";
            } else {
                console.log("Login failed. Please check your username and password.");
                displayErrorMessage("Login failed. Please check your username and password.");
            }
        }
    };
    xhr.send('username=' + username + '&password=' + password);
});
function displayErrorMessage(message) {
    var errorMessageElement = document.getElementById('errorMessage');
    errorMessageElement.textContent = message;
    errorMessageElement.style.display = 'block';
}

