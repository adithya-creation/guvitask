$(document).ready(function(){
    var sessionID = localStorage.getItem('sessionID');
    if(!sessionID){
        window.location.href = 'login.html';
    }

    // Fetch user profile data
    $.ajax({
        url: 'php/profile.php',
        method: 'GET',
        data: {sessionID: sessionID},
        success: function(response){
            try {
                var result = JSON.parse(response);
                if(result.status === 'success'){
                    $('#age').val(result.profile.age);
                    $('#dob').val(result.profile.dob);
                    $('#contact').val(result.profile.contact);
                } else {
                    alert(result.message);
                    if(result.message === 'Session expired'){
                        window.location.href = 'login.html';
                    }
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                alert('An error occurred while fetching profile data');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('An error occurred while fetching profile data');
        }
    });

    $('#profileForm').submit(function(e){
        e.preventDefault();

        var age = $('#age').val();
        var dob = $('#dob').val();
        var contact = $('#contact').val();

        $.ajax({
            url: 'php/profile.php',
            method: 'POST',
            data: {
                sessionID: sessionID,
                age: age,
                dob: dob,
                contact: contact
            },
            success: function(response){
                try {
                    var result = JSON.parse(response);
                    alert(result.message);
                    if(result.status === 'error' && result.message === 'Session expired'){
                        window.location.href = 'login.html';
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    alert('An error occurred while updating profile');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('An error occurred while updating profile');
            }
        });
    });
});