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

    $this->executionStartTime = microtime(true);

    try{
      $conn = new PDO("mysql:host=$this->cd_host;dbname=$this->cd_dbname;charset=utf8;collation=utf8_unicode_ci", "$this->cd_user", "$this->cd_password");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      $output['status']['code'] = "300";
  		$output['status']['name'] = "failure";
  		$output['status']['description'] = "database unavailable";
  		$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  		$output['data'] = $e->getMessage();

      echo json_encode($output);

  		exit;

    }

    $this->conn = $conn;

  }

  function getUsers() {

    // To be removed for production
  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

  	$query = "SELECT * FROM users";
  	$data = $this->conn->query($query)->fetchAll();

  	if (!$data) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		$this->conn = NULL;

  		echo json_encode($output);

  		exit;

  	}

  	$output['status']['code'] = "200";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = $data;

  	$this->conn = NULL;

  	echo json_encode($output);

  }

  // Get a user from DB
  function getUser($id) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

  	$query = "SELECT * FROM users WHERE id='$id'";
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

  	if (!$data) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = "Get user failed: No user with id $id";

  		$this->conn = NULL;

  		echo json_encode($output);

  		exit;

  	}

  	$output['status']['code'] = "200";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = $data;

  	$this->conn = NULL;

  	echo json_encode($output);

  }

  // Insert user to DB
  function insertUser() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

  	parse_str(file_get_contents("php://input"),$put_vars);
    $firstName = $put_vars['firstName']; $surname = $put_vars['surname']; $dob = $put_vars['dob'];
  	$email = $put_vars['email']; $phone = $put_vars['phone'];


    $stmt = $this->conn->prepare("INSERT INTO users (first_name, surname, dob, email, phone ) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$firstName, $surname, $dob, $email, $phone]);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "Insert failed";
  		$output['data'] = "Add user failed";

  		$this->conn = NULL;

  		echo json_encode($output);

  		exit;

  	}

  	$output['status']['code'] = "201";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = "Inserted user.";

  	$this->conn = NULL;

  	echo json_encode($output);

  }

  function updateUser() {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

  	parse_str(file_get_contents("php://input"), $post_vars);
    $id = $_POST['id']; $firstName = $_POST['firstName']; $surname = $_POST['surname'];
  	$dob = $_POST['dob'];	$email = $_POST['email']; $phone = $_POST['phone'];

    $stmt = $this->conn->prepare("UPDATE users SET first_name=?, surname=?, dob=?, email=?, phone=? WHERE id=?");
    $result = $stmt->execute([$firstName, $surname, $dob, $email, $phone,$id]);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = "Update user failed: no user with id $id";

  		$this->conn = NULL;

  		echo json_encode($output);

  		exit;

  	}

    $output['status']['code'] = "201";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = "Updated user $id.";

  	$this->conn = NULL;

  	echo json_encode($output);

  }

  function deleteUser($id) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

  	$query = "DELETE FROM users WHERE id=?";
  	$stmt = $this->conn->prepare($query);
    $result = $stmt->execute([$id]);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = [];

  		$this->conn = NULL;

  		echo json_encode($output);

  		exit;

  	}

  	$output['status']['code'] = "204";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "success";
  	$output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
  	$output['data'] = "User $id deleted.";

  	$this->conn = NULL;

  	echo json_encode($output);

  }

}
