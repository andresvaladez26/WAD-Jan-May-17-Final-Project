 $(document).ready(function(){
    $("#search").on("click", function(){
            var jsonToSend = {
                "action": "SEARCH",
                "date": $("#date").val(),
            };

            $.ajax({
                url: "data/applicationLayer.php",
                type: "POST",
                data : jsonToSend,
                dataType : "json",
                contentType:"application/x-www-form-urlencoded",
                success: function(jsonResponse){
                        var newHtml = "";
                        newHtml = jsonResponse.wod;
                        $("#resultWOD").html(newHtml);
                        $("#log").show();
                        var jsonObject = {
                        "action" : "RESULTS",
                        "date" : $("#date").val()
                        }

                    $.ajax({
                        url: "data/applicationLayer.php",
                        type: "POST",
                        data : jsonObject,
                        dataType : "json",
                        contentType:"application/x-www-form-urlencoded",
                        success: function(jsonResponse){
                                var newHtml = "";
                                newHtml += "<tbody> <tr> <th>Rank</th> <th>Name</th> <th>Result</th>";
                                for(var i=0; i<jsonResponse.length;i++){
                                    var rank = i+1;
                                    newHtml += "<tr> <td>" + rank + "</td><td>" + jsonResponse[i].username + "</td><td>" + jsonResponse[i].result + " </td></tr>";
                                }
                                newHtml += "</tr> </tbody>";
                                $("#results").html(newHtml);
                                $("#results").show();
                            }
                        });
                    }
            });
    });
});

 $(document).ready(function(){
    $("#submit").on("click", function(){
                        var jsonObject = {
                        "action": "LOG",
                        "date": $("#date").val(),
                        "result": $("#result").val()
                    };

                    $.ajax({
                        url: "data/applicationLayer.php",
                        type: "POST",
                        data : jsonObject,
                        dataType : "json",
                        contentType:"application/x-www-form-urlencoded",
                        success: function(jsonResponse){
                                alert(jsonResponse.status);
                                window.location.reload();
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