<?php
header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

$action = $_POST["action"];

switch($action){
	case "LOGIN" 	: loginFunction();
					  break;
	case "REGISTER" : registerFunction();
					  break;
	case "PROFILE"	: profileFunction();
					  break;
	case "UPDAT"	: updateFunction();
					  break;
	case "ADDWOD"	: addWODFunction();
					  break;
	case "NEWWOD"	: newWODFunction();
					  break;
	case "LASTWOD"	: showWODFunction();
					  break;
	case "SEARCH"	: searchFunction();
					  break;
	case "LOG"		: logResultFunction();
					  break;
	case "RESULTS"	: showResultsFunction();
					  break;
	case "LOGOUT"	: logoutFunction();
					  break;
	case "SEARCHU"	: searchUserFunction();
					  break;
}

function decryptPassword($password)
{
	$key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	
    $ciphertext_dec = base64_decode($password);
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    $password = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
   	
   	
   	$count = 0;
   	$length = strlen($password);

    for ($i = $length - 1; $i >= 0; $i --)
    {
    	if (ord($password{$i}) === 0)
    	{
    		$count ++;
    	}
    }

    $password = substr($password, 0,  $length - $count); 

    return $password;
}

function encryptPassword()
{
	$userPassword = $_POST['password'];

    $key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    $key_size =  strlen($key);
    
    $plaintext = $userPassword;

    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
    $ciphertext = $iv . $ciphertext;
    
    $userPassword = base64_encode($ciphertext);

    return $userPassword;
}

function registerFunction(){
	$fullName = $_POST['fullName'];
	$userName = $_POST['userName'];
	$email = $_POST['email'];
	$password = encryptPassword();
	$age = $_POST['age'];

	$result = registerUser($fullName, $userName, $email, $password, $age);

	if($result["status"] == "USER REGISTERED"){
		session_start();
		$_SESSION['user'] = $userName;
		$_SESSION['loginTime'] = time();
		echo json_encode($result);
	}
	else{
		header('HTTP/1.1 500'.$result["status"]);
		die($result["status"]);
	}
}

function loginFunction(){
	$userName = $_POST['username'];

	$result = validateUser($userName);

	if ($result['status'] == 'COMPLETE')
		{
			$decryptedPassword = decryptPassword($result['password']);

			$password = $_POST['userPassword'];
			
			if ($decryptedPassword === $password)
		   	{	
		    	$response = array("status" => "WELCOME");
			    session_start();
				$_SESSION['user'] = $userName;
				$_SESSION['loginTime'] = time();
				echo json_encode($response);	
			}
			else
			{
				header('HTTP/1.1 306 Wrong credentials');
				die("Wrong credentials");
			}
		}

}

function searchUserFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$userName = $_POST['username'];
 		
 		$result = loadProfile($userName);
 		
 		if ($result["status"] == "SUCCESS"){
 			unset($result["status"]);
 			echo json_encode($result);
 		}	
 		else{
 			header('HTTP/1.1 500' . $result["status"]);
 			die($result["status"]);
 		}		
 	}else{
 		header('HTTP/1.1 410 No Session');
		die('No Session');
 	}
}

function profileFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$userName = $_SESSION['user'];
 		
 		$result = loadProfile($userName);
 		
 		if ($result["status"] == "SUCCESS"){
 			unset($result["status"]);
 			echo json_encode($result);
 		}	
 		else{
 			header('HTTP/1.1 500' . $result["status"]);
 			die($result["status"]);
 		}		
 	}else{
 		header('HTTP/1.1 410 No Session');
		die('No Session');
 	}
}

function updateFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$weight = $_POST['weight'];
		$height = $_POST['height'];
		$userName = $_SESSION['user'];

		$result = updateProfile($userName, $weight, $height);

		if($result['status'] == "USER UPDATED"){
			echo json_encode($result);
		}else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}else{
		header('HTTP/1.1 410 No Session');
		die('No Session');
	}
}

function addWODFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$userName = $_SESSION['user'];

		if($userName == "admin"){
			$result = array("status" => "SUCCESS");
			echo json_encode($result);
		}else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}else{
		header('HTTP/1.1 410 No Session');
		die('No Session');
	}
}

function logResultFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$userName = $_SESSION['user'];
		$name = takeName($userName);
		$date = $_POST['date'];
		$res = $_POST['result']; 
		$result = logResult($name, $date, $res);

		if($result['status'] == "RESULT LOGGED SUCCESSFULLY"){
			echo json_encode($result);
		}else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}else{
		header('HTTP/1.1 410 No Session');
		die('No Session');
	}
}

function newWODFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$date = $_POST['date'];
		$wod = $_POST['wod'];
		$type = $_POST['type'];

		$result = addNewWOD($date, $wod, $type);

		if($result['status'] = "NEW WOD ADDED"){
			echo json_encode($result);
		}else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}else{
		header('HTTP/1.1 410 No Session');
		die('No Session');
	}
}

function showWODFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
 		$result = showWOD();
 		
 		if ($result["status"] == "SUCCESS"){
 			unset($result["status"]);
 			echo json_encode($result);
 		}	
 		else{
 			header('HTTP/1.1 500' . $result["status"]);
 			die($result["status"]);
 		}		
 	}else{
 		header('HTTP/1.1 410 No Session');
		die('No Session');
 	}
}

function showResultsFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
 		$date = $_POST['date'];
 		$result = showResults($date);

 		if ($result[0]["status"] == "SUCCESS"){
 			echo json_encode($result);
 		}	
 		else{
 			header('HTTP/1.1 500' . $result["status"]);
 			die($result["status"]);
 		}		
 	}else{
 		header('HTTP/1.1 410 No Session');
		die('No Session');
 	}
}

function searchFunction(){
	session_start();

	if(isset($_SESSION['user']) && time() - $_SESSION['loginTime'] < 1800){
		$date = $_POST['date'];
 		$result = searchWOD($date);
 		
 		if ($result["status"] == "SUCCESS"){
 			unset($result["status"]);
 			echo json_encode($result);
 		}	
 		else{
 			header('HTTP/1.1 500' . $result["status"]);
 			die($result["status"]);
 		}		
 	}else{
 		header('HTTP/1.1 410 No Session');
		die('No Session');
 	}
}

function logoutFunction(){
	session_start();
	unset($_SESSION['user']);
	unset($_SESSION['loginTime']);
	session_destroy();
	$response = array("status" => "LOGGED OUT");
	echo json_encode($response);
}