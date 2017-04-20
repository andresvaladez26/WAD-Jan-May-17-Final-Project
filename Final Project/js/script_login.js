$(document).ready(function(){
	$("#login").on("click", function(){

		var jsonToSend = {
			"action": "LOGIN",
			"username" : $("#username").val(),
			"userPassword" : $("#password").val()
		};

		$.ajax({
			url : "data/applicationLayer.php",
			type : "POST",
			data : jsonToSend,
			dataType : "json",
			contentType : "application/x-www-form-urlencoded",
			success : function(jsonResponse){
				alert(jsonResponse.status);
				window.location.assign("./index.html");
			},
			error: function (errorMessage){
				alert(errorMessage.responseText);
			} 
		});
	});
});

  $(document).ready(function(){
    $("#logout").on("click", function(){
            var jsonToSend = {
                "action": "LOGOUT"
            };

            $.ajax({
                url: "data/applicationLayer.php",
                type: "POST",
                data : jsonToSend,
                dataType : "json",
                contentType:"application/x-www-form-urlencoded",
                success: function(jsonResponse){
                        alert(jsonResponse.status);
                        window.location.assign("./login.html")
                    }
            });
    });
});