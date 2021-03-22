<?php

function generateAPIkey() {

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);



	$executionStartTime = microtime(true);

	include("config.php");

	header('Content-Type: application/json; charset=UTF-8');

	$conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname);

	if (mysqli_connect_errno()) {

		$output['status']['code'] = "300";
		$output['status']['name'] = "failure";
		$output['status']['description'] = "database unavailable";
		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
		$output['data'] = [];

		mysqli_close($conn);

		echo json_encode($output);

		exit;

	}

	parse_str(file_get_contents("php://input"),$put_vars);
  $firstName = $put_vars['firstName']; $surname = $put_vars['surname']; $dob = $put_vars['dob'];
	$email = $put_vars['email']; $phone = $put_vars['phone'];

	$query = "INSERT INTO users (first_name, surname, dob, email, phone ) VALUES ('$firstName', '$surname', '$dob', '$email', '$phone')";
	$result = $conn->query($query);

	if (!$result) {

		$output['status']['code'] = "400";
		$output['status']['name'] = "executed";
		$output['status']['description'] = "query failed";
		$output['data'] = [];

		mysqli_close($conn);

		echo json_encode($output);

		exit;

	}

  //  	$data = [];
	//
	// while ($row = mysqli_fetch_assoc($result)) {
	//
	// 	array_push($data, $row);
	//
	// }

	$output['status']['code'] = "201";
	$output['status']['name'] = "ok";
	$output['status']['description'] = "success";
	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
	$output['data'] = "Inserted user.";

	mysqli_close($conn);

	echo json_encode($output);

}

function secure_random_string($length) {
    $random_string = '';
    for($i = 0; $i < $length; $i++) {
        $number = random_int(0, 36);
        $character = base_convert($number, 10, 36);
        $random_string .= $character;
    }

    return $random_string;
}
