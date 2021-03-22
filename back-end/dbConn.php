<?php
class DBConn {

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

  // Get a user from DB
  function checkAPIkey($APIkey) {

  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

    $stmt = $this->conn->prepare("SELECT * FROM api_keys WHERE api_key=?");
    $stmt->execute([$APIkey]);
    $data = $stmt->fetch();

  	if (!$data) {

      $output['status']['code'] = "401";
    	$output['status']['name'] = "ok";
    	$output['status']['description'] = "Auth failed.";
    	$output['data'] = "Not authorised.";

      echo json_encode($output);

      exit;

  	}

  	return true;

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

    // Passes query to DB after being processed to prevent SQL injection
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
  function insertUser()
  {
  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

    // Try and catch any incorrect array references
    try {

      parse_str(file_get_contents("php://input"),$put_vars);
      $firstName = $this->validateName($put_vars['firstName']); $surname = $this->validateName($put_vars['surname']);
      $dob = $this->isDateMySqlFormat($put_vars['dob']); $email = $this->validateEmail($put_vars['email']);
      $phone = $this->validatePhone($put_vars['phone']);

    } catch (Exception $e) {

      $output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "Insert failed";
  		$output['data'] = "Add user failed";

      $this->conn = NULL;

  		echo json_encode($output);

  		exit;

    }

    // Passes query to DB after being processed to prevent SQL injection
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

    try {

      $id = $_POST['id']; $firstName = $this->validateName($_POST['firstName']);
      $surname = $this->validateName($_POST['surname']); $dob = $this->isDateMySqlFormat($_POST['dob']);
      $email = $this->validateEmail($_POST['email']); $phone = $this->validatePhone($_POST['phone']);

    } catch (\Exception $e) {

      $output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "Update failed";
  		$output['data'] = "Update user failed";

      $this->conn = NULL;

  		echo json_encode($output);

  		exit;

    }

    // Passes query to DB after being processed to prevent SQL injection
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

  function deleteUser($id)
  {
  	ini_set('display_errors', 'On');
  	error_reporting(E_ALL);

  	header('Content-Type: application/json; charset=UTF-8');

    // Passes query to DB after being processed to prevent SQL injection
  	$query = "DELETE FROM users WHERE id=?";
  	$stmt = $this->conn->prepare($query);
    $result = $stmt->execute([$id]);

  	if (!$result) {

  		$output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = "Delete user failed: no user with id $id.";

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

  // Processes date for storage as mySQL date
  function isDateMySqlFormat($date)
  {
    // Uses try catch statements to determine various conditions
    try {

      // Throw exception if nothing is passed (prevents same day date)
      if (!$date) {
        throw new \Exception('No date passed to API.');
      }

      // Create date
      $date=date_create($date);

      // Throw exception if $date isn't in a date format
      if (!$date) {
        throw new \Exception('Invalid date format. Use YYYY-MM-DD.');
      }

      // Error if DOB has year before
      $dateYear = date_format($date, 'Y');
      if ($dateYear < 1900) {
        throw new \Exception('Invalid date of birth. Birth year must be after 1900.');
      }

      // Ensure date is in mySQL format NOTE: may switch month and day
      $date_return = date_format($date, 'Y-m-d');

      // Gets date 16 years ago for valid DOB - 16 years or older
      $maxDOB = strtotime("-16 year", time());
      $maxDOB = date("Y-m-d", $maxDOB);

      if ($date_return >= $maxDOB) {
        throw new \Exception('Invalid date of birth. User must be at least 16 years old.');
      }

      // Returns formatted DOB if no errors thrown
      return $date_return;

    } catch (Exception $e) {

      $output['status']['code'] = "400";
      $output['status']['name'] = "executed";
      $output['status']['description'] = "query failed";
      $output['data'] = $e->getMessage();

      $this->conn = NULL;

      echo json_encode($output);

      exit;
    }

  }

  function validateName($name) {
    if(preg_match("/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/", $name)) {

      return $name;

    } else {

      $output['status']['code'] = "400";
      $output['status']['name'] = "executed";
      $output['status']['description'] = "query failed";
      $output['data'] = "Name not valid format.";

      $this->conn = NULL;

      echo json_encode($output);

      exit;

    }

  }

  function validateEmail($email)
  {
    // Regex for HTML 5 form validation (input type email)
    if(preg_match("/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $email)) {

      return $email;

    } else {

      $output['status']['code'] = "400";
      $output['status']['name'] = "executed";
      $output['status']['description'] = "query failed";
      $output['data'] = "Email not valid format.";

      $this->conn = NULL;

      echo json_encode($output);

      exit;

    }
  }

  function validatePhone($phone)
  {
    // Regex for HTML 5 form validation (input type email)
    if(preg_match("/^[0-9\-\(\)\/\+\s]*$/", $phone)) {

      return $phone;

    } else {

      $output['status']['code'] = "400";
      $output['status']['name'] = "executed";
      $output['status']['description'] = "query failed";
      $output['data'] = "Phone number not valid format.";

      $this->conn = NULL;

      echo json_encode($output);

      exit;

    }
  }

}
