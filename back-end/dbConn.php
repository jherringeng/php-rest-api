<?php
class dbConn {

  private $cd_host = "127.0.0.1";
	private $cd_user = "root"; // user name
	private $cd_password = ""; // password
	private $cd_dbname = "users"; // database name

  private $conn;

  function __construct() {
    $this->conn = new mysqli($this->cd_host, $this->cd_user, $this->cd_password, $this->cd_dbname);
  }

  function getUsers() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

  	if (mysqli_connect_errno()) {

  		$output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

  	$query = "SELECT * FROM users";
  	$result = $this->conn->query($query);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

     	$data = [];

  	while ($row = mysqli_fetch_assoc($result)) {

  		array_push($data, $row);

  	}

  	$output['status']['code'] = "200";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  	$output['data'] = $data;

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function getUser($id) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

  	if (mysqli_connect_errno()) {

  		$output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

  	$query = "SELECT * FROM users WHERE id='$id'";
  	$result = $this->conn->query($query);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

     	$data = [];

  	while ($row = mysqli_fetch_assoc($result)) {

  		array_push($data, $row);

  	}

  	$output['status']['code'] = "200";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  	$output['data'] = $data;

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function insertUser() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

  	if (mysqli_connect_errno()) {

  		$output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

  	parse_str(file_get_contents("php://input"),$put_vars);
    $firstName = $put_vars['firstName']; $surname = $put_vars['surname']; $dob = $put_vars['dob'];
  	$email = $put_vars['email']; $phone = $put_vars['phone'];

  	$query = "INSERT INTO users (first_name, surname, dob, email, phone ) VALUES ('$firstName', '$surname', '$dob', '$email', '$phone')";
  	$result = $this->conn->query($query);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		mysqli_close($this->conn);

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

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function updateUser() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

  	if (mysqli_connect_errno()) {

  		$output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

  	parse_str(file_get_contents("php://input"), $post_vars);
    $id = $post_vars['id']; $firstName = $post_vars['firstName']; $surname = $post_vars['surname'];
  	$dob = $post_vars['dob'];	$email = $post_vars['email']; $phone = $post_vars['phone'];

  	$query = "UPDATE users SET first_name='$firstName', surname='$surname', dob='$dob', email='$email', phone='$phone' WHERE id='$id'";
  	$result = $this->conn->query($query);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

    $output['status']['code'] = "201";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  	$output['data'] = "Updated user $id.";

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function deleteUser($id) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

  	if (mysqli_connect_errno()) {

  		$output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

  	$query = "DELETE FROM users WHERE id='$id'";
  	$result = $this->conn->query($query);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		mysqli_close($this->conn);

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

  	$output['status']['code'] = "200";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
  	$output['data'] = "User $id deleted.";

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

}
