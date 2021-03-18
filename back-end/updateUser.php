<?php

function updateUser() {

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

	parse_str(file_get_contents("php://input"), $post_vars);
  $id = $post_vars['id']; $firstName = $post_vars['firstName']; $surname = $post_vars['surname'];
	$dob = $post_vars['dob'];	$email = $post_vars['email']; $phone = $post_vars['phone'];

	$query = "UPDATE users SET first_name='$firstName', surname='$surname', dob='$dob', email='$email', phone='$phone' WHERE id='$id'";
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
	$output['data'] = "Updated user $id.";

	mysqli_close($conn);

	echo json_encode($output);

}

?>
