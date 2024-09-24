$(document).ready(function(){
    $('#loginForm').submit(function(e){
        e.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            url: 'php/login.php',
            method: 'POST',
            data: {email: email, password: password},
            success: function(response){
                var result = JSON.parse(response);
                if(result.status === 'success'){
                    localStorage.setItem('sessionID', result.sessionID);
                    window.location.href = 'profile.html';
                } else {
                    alert(result.message);
                }
            }
        });
    });
});