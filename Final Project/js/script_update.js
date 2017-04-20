 $(document).ready(function(){
    $("#update").on("click", function(){
            var jsonToSend = {
                "action": "UPDAT",
                "weight": $("#weight").val(),
                "height": $("#height").val()
            };

            $.ajax({
                url: "data/applicationLayer.php",
                type: "POST",
                data : jsonToSend,
                dataType : "json",
                contentType:"application/x-www-form-urlencoded",
                success: function(jsonResponse){
                        alert(jsonResponse.status);
                        window.location.assign("profile.html");
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