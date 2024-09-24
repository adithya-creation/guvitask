$(document).ready(function(){
    $('#registerForm').submit(function(e){
        e.preventDefault();

        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();

        // Email validation
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email');
            return;
        }

        // Password validation
        if (password.length < 8) {
            alert('Password must be at least 8 characters');
            return;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        $.ajax({
            url: 'php/register.php',
            method: 'POST',
            data: {
                username: username,
                email: email,
                password: password
            },
            success: function(response){
                var result = JSON.parse(response); // Ensure response is parsed correctly
                if (result.status === 'success') {
                    alert(result.message);
                    window.location.href = 'login.html';
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + error); // Error handling
                alert('Something went wrong! Please try again.');
            }
        });
    });
});
