$(document).ready(function(){
    $('#logoutButton').click(function(){
        var sessionID = localStorage.getItem('sessionID');
        
        $.ajax({
            url: 'php/logout.php',
            method: 'POST',
            data: {sessionID: sessionID},
            success: function(response){
                var result = JSON.parse(response);
                if(result.status === 'success'){
                    localStorage.removeItem('sessionID');
                    window.location.href = 'login.html';
                } else {
                    alert(result.message);
                }
            }
        });
    });
});