<?php
header('Accept: application/json');
header('Content-type: application/json');

function connectionToDataBase(){
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "easywodlog";

	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($conn->connect_error){
		return null;
	}
	else{
		return $conn;
	}
}

function registerUser($fullName, $userName, $email, $password, $age){
	$conn = connectionToDataBase();

	if ($conn != null){
		$sql = "INSERT INTO Users (fullName,userName,age,email,password, weight, height) VALUES ('$fullName','$userName','$age','$email','$password','0.0','0.0')";

		if(mysqli_query($conn, $sql)){
			$conn -> close();
			return array("status" => "USER REGISTERED");
		}
		else{
			$conn -> close();
			return array("status" => "NO REGISTERED");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function validateUser($userName){
    $conn = connectionToDataBase();

    if ($conn != null)
    {
	   	$sql = "SELECT * FROM Users WHERE username = '$userName'";
		$result = $conn->query($sql);
			
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc()) 
	    	{
				$conn->close();
				return array("status" => "COMPLETE", "fName" => $row['fName'], "lName" => $row['lName'], "password" => $row['password']);
			}
		}
		else
		{
			$conn->close();
			return array("status" => "ERROR");
		}
    }else{
      	$conn->close();
       	return array("status" => "ERROR");
    }
}

function loadProfile($userName){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "SELECT fullName, email, age, weight, height FROM Users WHERE username = '$userName'";
		
		$result = $conn->query($sql);
		
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$response = array("status" => "SUCCESS", 'fullName' => $row['fullName'], 'email' => $row['email'], 'age' => $row['age'], 'weight' => $row['weight'], 'height' => $row['height']);
			$conn -> close();
			return($response);
		} else{
			$conn -> close();
			return array("status" => "NO SUCCESS");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function updateProfile($userName, $weight, $height){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "UPDATE Users SET weight = '$weight', height = '$height' WHERE userName = '$userName'";

		if($conn->query($sql)){
			$conn -> close();
			return array("status" => "USER UPDATED");
		}else{
			$conn -> close();
			return array("status" => "NO SUCCESS");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function addNewWOD($date, $wod, $type){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "INSERT INTO Wods (day, wod, type) VALUES ('$date','$wod','$type')";
		if(mysqli_query($conn, $sql)){
			$sql = "CREATE TABLE `$date`
						(
							  userName varchar(30) NOT NULL,
							  result varchar(10) NOT NULL,
							  PRIMARY KEY(userName)
							)";
			mysqli_query($conn, $sql);
			$conn -> close();
			return array("status" => "NEW WOD ADDED");
		}
		else{
			$conn -> close();
			return array("status" => "WOD NOT ADDED");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function takeName($userName){
	$conn = connectionToDataBase();
	if($conn != null){
		$sql = "SELECT * FROM Users WHERE userName = '$userName'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$name = $row['fullName'];
			return $name;
		}else{
			$conn -> close();
			return array("status" => "NO SUCCESS");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function logResult($name, $date, $res){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "INSERT INTO `$date` (userName, result) VALUES ('$name', '$res')";
		if(mysqli_query($conn, $sql)){
			$conn -> close();
			return array("status" => "RESULT LOGGED SUCCESSFULLY");
		}else{
			$sql = "UPDATE `$date` SET result = '$res' WHERE userName = '$name'";
			if(mysqli_query($conn, $sql)){
				$conn -> close();
				return array("status" => "RESULT LOGGED SUCCESSFULLY");
			}else{
			$conn -> close();
			return array("status" => "RESULT NOT LOGGED");
			}
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function showResults($date){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "SELECT * FROM wods where day='$date'";
		$style = $conn->query($sql);
		if($style->num_rows > 0){
			$row = $style->fetch_assoc();
			$type = $row['type'];
		}
		if($type == "T")
			$sql2 = "SELECT * FROM `$date` ORDER BY result ASC";
		else
			$sql2 = "SELECT * FROM `$date` ORDER BY result DESC";

		$result = $conn->query($sql2);

		if($result->num_rows > 0){
			$responses = array();
			while($row = $result->fetch_assoc()){
				array_push($responses, array('status' => 'SUCCESS', 'username' => $row['userName'], 'result' => $row['result']));
			}
			$conn -> close();
			return($responses);
		}else{
			$conn -> close();
			return array("status" => "NO LOGS");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function showWOD(){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "SELECT * FROM wods WHERE ID = (SELECT MAX(ID) FROM wods)";
		$result = $conn->query($sql);

		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$response = array("status" => "SUCCESS", 'date' => $row['day'], 'wod' => nl2br($row['wod']), 'type' => $row['type']);
			$conn -> close();
			return($response);
		} else{
			$conn -> close();
			return array("status" => "NO SUCCESS");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}

function searchWOD($date){
	$conn = connectionToDataBase();

	if($conn != null){
		$sql = "SELECT * FROM wods WHERE day = '$date'";
		$result = $conn->query($sql);

		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$response = array("status" => "SUCCESS", 'date' => $row['day'], 'wod' => nl2br($row['wod']));
			$conn -> close();
			return($response);
		} else{
			$conn -> close();
			return array("status" => "NO SUCCESS");
		}
	}else{
		$conn -> close();
		return array("status" => "CONNECTION WITH DB WENT WRONG");
	}
}