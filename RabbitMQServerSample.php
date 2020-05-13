<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

//for login case
//REFER TO FUNCTION REGISTER BELOW AND ADD SAME CONCEPT TO LOGIN FUNCTION.. DO NOT FORGET .. THIS WILL PUSH LOGIN DETAILS TO MYSQL.
//
function login($user,$pass){
	//TODO validate user credentials

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "Database1@", "login");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt insert query execution
$sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
if(mysqli_query($link, $sql)){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);

	return true;
}

// for register case:
//
function register($user,$pass,$email){
        //TODO validate user credentials

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "Database1@", "register");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt insert query execution
$sql = "INSERT INTO users (username, password, email) VALUES ('$user', '$pass', '$email')";
if(mysqli_query($link, $sql)){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);

	return true;
}
//change for fetching
function fetch($doctor,$office,$insurance,$specialty){
        //TODO validate user credentials

/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "Database1@", "doctor");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt insert query execution
//changed
$query="SELECT doctor, office, insurance, specialty FROM `doctor` WHERE doctor='$doctor' AND office='$office' AND insurance='$insurance' AND specialty='$specialty'";
//$query="SELECT * FROM doctor (doctor, office, insurance, specialty) VALUES ('$doctor','$office','$insurance','$specialty')";

//$query="SELECT * FROM doctor (doctor, office, insurance, specialty) VALUES ('$doctor','$office','$insurance','$specialty')";
if($a = mysqli_query($link, $query)){
	echo "Returned rows are: ". mysqli_num_rows($a);
	var_dump($a);
	return $a;
//return  mysqli_query($link, $query);
//	echo mysqli_free_result($a);
//	var_dump($a);
} else{
    echo "ERROR: Could not able to execute $query. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);

        return true;
//changed
}


function get_news($source){
	require("config.inc");
$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://newsapi.org/v2/everything?q=$source&apiKey=$api_key",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	//CURLOPT_POSTFIELDS => "apiKey=$api_key&newsSource=$source",
	CURLOPT_HTTPHEADER => array(
		"content-type: application/x-www-form-urlencoded",
		//"x-rapidapi-host: $rapid_api_host",
		//"x-rapidapi-key: $rapid_api_key"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
return json_encode($response);	
}

function request_processor($req){
	echo "Received Request".PHP_EOL;
	echo "<pre>" . var_dump($req) . "</pre>";
	if(!isset($req['type'])){
		return "Error: unsupported message type";
	}
	//Handle message type
	$type = $req['type'];
	switch($type){
		case "login":
			return login($req['username'], $req['password']);
		case "register":
			return register($req['username'], $req['password'], $req['email']);
		case "posting":
			return fetch($req['doctor'], $req['office'], $req['insurance'], $req['specialty']);
		case "validate_session":
			return validate($req['session_id']);
		case "get_news":
			return get_news($req['query']);
		case "echo":
			return array("return_code"=>'0', "message"=>"Echo: " .$req["message"]);
	}
	return array("return_code" => '0',
		"message" => "Server received request and processed it");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "sampleServer");

echo "Rabbit MQ Server Start" . PHP_EOL;
$server->process_requests('request_processor');
echo "Rabbit MQ Server Stop" . PHP_EOL;
exit();
?>
