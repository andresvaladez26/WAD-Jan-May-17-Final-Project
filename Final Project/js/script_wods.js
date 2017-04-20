$(document).ready(function(){
        var jsonToSend = {
            "action": "ADDWOD"
        };

        $.ajax({
            url: "data/applicationLayer.php",
            type: "POST",
            data : jsonToSend,
            dataType : "json",
            contentType:"application/x-www-form-urlencoded",
            success: function(jsonResponse){
                var location = '="addwod.html"'
                var newHtml = "";
                newHtml += "<button class='submit' onclick='location.href" + location + "' type='button'> Add New WOD </button>";
                $("#admin").append(newHtml);
            }
        });
});

$(document).ready(function(){
        var jsonToSend = {
            "action": "LASTWOD"
        };

        $.ajax({
            url: "data/applicationLayer.php",
            type: "POST",
            data : jsonToSend,
            dataType : "json",
            contentType:"application/x-www-form-urlencoded",
            success: function(jsonResponse){
                    var newHtml = "";
                    newHtml = jsonResponse.date;
                    $("#last").append(newHtml);
                    newHtml = jsonResponse.wod;
                    $("#lastwod").append(newHtml);
                    $("#log").show();

                    var jsonObject = {
                        "action" : "RESULTS",
                        "date" : jsonResponse.date
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

                    $("#submit").on("click", function(){
                        var jsonObject = {
                        "action": "LOG",
                        "date": jsonResponse.date,
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
                                window.location.assign("wods.html");
                            }
                    });
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