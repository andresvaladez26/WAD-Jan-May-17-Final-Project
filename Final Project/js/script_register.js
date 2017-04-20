$(document).ready(function(){
	$("#register").on("click", function(){

        var $name       = $("#fullName");
        var $user       = $("#userName");
		var $email 		= $("#email");
		var $password 	= $("#password");
		var $confirm 	= $("#confirmation");
        var $age        = $("#age");
		
		if ($name.val() == "" || $password.val() == "" || $email.val() == "" || $confirm.val() == "" || $age.val() == "" || $user.val() == ""){
			alert("There is missing information");
		}else if ($password.val() != $confirm.val())
            alert("Passwords do not match");	
		else{
			var jsonToSend ={
            "action": "REGISTER",
            "fullName": $("#fullName").val(),
            "userName": $("#userName").val(),
            "email" : $("#email").val(),
            "password" : $("#password").val(),
            "age": $("#age").val()
        	};
        	console.log(jsonToSend);
        	$.ajax({
            	url: "data/applicationLayer.php",
            	type: "POST",
            	data: jsonToSend,
            	dataType: "json",
            	success : function(jsonResponse){
                    alert(jsonResponse.status);
                	window.location.assign('./index.html');
            	},
            	error: function(errorMessage){
                	alert(errorMessage.responseText);
            	} 
        	});
		}
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