<?php
class dbConn {

  private $cd_host = "127.0.0.1";
	private $cd_user = "root"; // user name
	private $cd_password = ""; // password
	private $cd_dbname = "users"; // database name
  private $executionStartTime;

  private $conn = NULL;

  function __construct() {
    // $this->conn = new mysqli($this->cd_host, $this->cd_user, $this->cd_password, $this->cd_dbname);

    $this->executionStartTime= microtime(true);

    try{
      $conn = new PDO("mysql:host=$this->cd_host;dbname=$this->cd_dbname;charset=utf8;collation=utf8_unicode_ci", "$this->cd_user", "$this->cd_password");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      $output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  		$output['data'] = $e->getMessage();

      echo json_encode($output);

  		exit;
    }
    $this->conn = $conn;

    // Exits and returns error
    // if (mysqli_connect_errno()) {
    //
  	// 	$output['status']['code'] = "300";
  	// 	$output['status']['name'] = "failure";
  	// 	$output['status']['description'] = "database unavailable";
  	// 	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	// 	$output['data'] = [];
    //
  	// 	mysqli_close($this->conn);
    //
  	// 	echo json_encode($output);
    //
  	// 	exit;
    //
  	// }

  }

  function getUsers() {

    // To be removed for production
  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$this->executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

  	$query = "SELECT * FROM users";
  	$data = $this->conn->query($query)->fetchAll();

  	if (!$data) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		mysqli_close($this->conn);

  		echo json_encode($output);

  		exit;

  	}

     	// $data = [];


  	// while ($row = mysqli_fetch_assoc($result)) {
    //
  	// 	array_push($data, $row);
    //
  	// }

  	$output['status']['code'] = "200";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = $data;

  	$this->conn = NULL;

  	echo json_encode($output);

  }

  function getUser($id) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$this->executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

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
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = $data;

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function insertUser() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$this->executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');



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
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = "Inserted user.";

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function updateUser() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$this->executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

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
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = "Updated user $id.";

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

  function deleteUser($id) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	$this->executionStartTime = microtime(true);

  	header('Content-Type: application/json; charset=UTF-8');

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
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = "User $id deleted.";

  	mysqli_close($this->conn);

  	echo json_encode($output);

  }

}
