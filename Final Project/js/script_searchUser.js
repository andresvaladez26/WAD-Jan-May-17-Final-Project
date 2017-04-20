 $(document).ready(function(){
    $("#search").on("click", function(){
            var jsonToSend = {
                "action": "SEARCHU",
                "username": $("#username").val()
            };

            $.ajax({
                url: "data/applicationLayer.php",
                type: "POST",
                data : jsonToSend,
                dataType : "json",
                contentType:"application/x-www-form-urlencoded",
                success: function(jsonResponse){
                        console.log(jsonResponse);
                        var fullName = jsonResponse.fullName;
                        var email = jsonResponse.email;
                        var age = jsonResponse.age;
                        var weight = jsonResponse.weight;
                        var height = jsonResponse.height;
                        var newHtml = "";
                        newHtml += "<p><b> Name:</b> " + fullName + " </p> <p> <b> Email:</b> " + email + "</p> <p> <b> Age:</b> " + age + "</p> <p> <b> Weight:</b> " + weight + " kg</p> <p> <b> Height:</b> " + height + " meters</p>";
                        $("#results").append(newHtml);
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