<?php
class DBConn {

  // Access for localhost (change to your settings if testing)
  private $cd_host = "127.0.0.1";
  private $cd_user = "root"; // user name
  private $cd_password = ""; // password
  private $cd_dbname = "users"; // database name

  // Access for online DB
  // private $cd_host = "db5002055394.hosting-data.io";
  // private $cd_user = "dbu1229560"; // user name
  // private $cd_password = "2001!UserAPI"; // password
  // private $cd_dbname = "dbs1672596"; // database name

  private $executionStartTime;

  private $conn = null;

  function __construct()
  {
    $this->executionStartTime = microtime(true);

    try{
      $conn = new PDO("mysql:host=$this->cd_host;dbname=$this->cd_dbname;charset=utf8;collation=utf8_unicode_ci", "$this->cd_user", "$this->cd_password");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      $this->outputError("300", "Failure", "Database unavailable.", $e->getMessage());
    }

    $this->conn = $conn;

  }

  // Get a user from DB
  function checkAPIkey($APIkey)
  {
    $stmt = $this->conn->prepare("SELECT * FROM api_keys WHERE api_key=?");
    $stmt->execute([$APIkey]);
    $data = $stmt->fetch();

    if (!$data) {

      $this->outputError("400", "error", "Auth failed.", "Authorisation failed. Please check your API key.");

    }

    return true;

  }

  function getUsers()
  {
    $query = "SELECT * FROM users";
    $data = $this->conn->query($query)->fetchAll();

    if (!$data) {
      $this->outputError("400", "error", "Query failed", "Could not return users.");
    }

    $this->outputSuccess("200", "ok", "success", $data);

  }

  // Get a user from DB
  function getUser($id)
  {
    // Passes query to DB after being processed to prevent SQL injection
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {
      $this->outputError("400", "Error", "Query failed", "Get user failed: No user with id $id.");
    }

    $this->outputSuccess("200", "ok", "success", $data);

  }

  // Insert user to DB
  function insertUser()
  {
    // Try and catch any incorrect array references
    try {

      parse_str(file_get_contents("php://input"),$put_vars);
      if(!array_key_exists('firstName', $put_vars) || !array_key_exists('surname', $put_vars) || !array_key_exists('surname', $put_vars) || !array_key_exists('surname', $put_vars) || !array_key_exists('surname', $put_vars)) {
        throw new \Exception("Required input not given.");
      }

      $firstName = $this->validateName($put_vars['firstName']); $surname = $this->validateName($put_vars['surname']);
      $dob = $this->isDateMySqlFormat($put_vars['dob']); $email = $this->validateEmail($put_vars['email']);
      $phone = $this->validatePhone($put_vars['phone']);

    } catch (Exception $e) {
      $this->outputError("400", "error", "Insert failed", $e->getMessage());
    }

    // Passes query to DB after being processed to prevent SQL injection
    $stmt = $this->conn->prepare("INSERT INTO users (first_name, surname, dob, email, phone ) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$firstName, $surname, $dob, $email, $phone]);

    if (!$result) {
      $this->outputError("400", "error", "Insert failed", "Add user failed.");
    }

    $this->outputSuccess("201", "ok", "success", "Inserted user.");

  }

  function updateUser()
  {
    try {

      if(!array_key_exists('firstName', $_POST) || !array_key_exists('surname', $_POST) || !array_key_exists('surname', $_POST) || !array_key_exists('surname', $_POST) || !array_key_exists('surname', $_POST)){
        throw new \Exception("Required input not given.");
      }

      $id = $_POST['id']; $firstName = $this->validateName($_POST['firstName']);
      $surname = $this->validateName($_POST['surname']); $dob = $this->isDateMySqlFormat($_POST['dob']);
      $email = $this->validateEmail($_POST['email']); $phone = $this->validatePhone($_POST['phone']);

    } catch (\Exception $e) {

      $this->outputError("400", "error", "Update failed", $e->getMessage());

    }

    // First checks if user exists before update
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {

      $this->outputError("400", "error", "input failed", "Update user failed: no user with id $id.");

    }

    // Passes query to DB after being processed to prevent SQL injection
    $stmt = $this->conn->prepare("UPDATE users SET first_name=?, surname=?, dob=?, email=?, phone=? WHERE id=?");
    $result = $stmt->execute([$firstName, $surname, $dob, $email, $phone,$id]);

    $this->outputSuccess("201", "ok", "success", "Updated user $id.");

  }

  function deleteUser($id)
  {
    // First checks if user exists before deletion
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {

      $this->outputError("400", "error", "input failed", "Delete user failed: no user with id $id.");

    }

    // Passes query to DB after being processed to prevent SQL injection
    $query = "DELETE FROM users WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $result = $stmt->execute([$id]);

    $this->outputSuccess("204", "ok", "success", "User $id deleted.");

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

      $this->outputError("400", "error", "input failed", $e->getMessage());

    }

  }

  function validateName($name) {

    // Gets rid of whitspaces
    $name = trim($name);

    // Checks if name is empty
    if(empty($name)) {
      $this->outputError("400", "error", "input failed", "Please add a name.");
    }

    if(preg_match("/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/", $name)) {

      return $name;

    } else {

      $this->outputError("400", "error", "input failed", "Name not valid format.");

    }

  }

  // Function for validating email
  function validateEmail($email)
  {
    // Ensures email is of correct format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

      return $email;

    } else {

      $this->outputError("400", "error", "input failed", "Email not valid format.");

    }
  }

  // Regex for validating phone numbers
  function validatePhone($phone)
  {
    // Regex allows a range of potential phone formats e.g. +44 7827 667 047, 07827-667-047
    if(preg_match("/^[0-9\-\(\)\/\+\s]*$/", $phone)) {

      // Doesn't actually change $phone so is unnecessary (only return required)
      return $phone;

    } else {

      $this->outputError("400", "error", "Query failed", "Phone number not valid format.");

    }
  }

  // Generates an API key (not used in practice - could generate then return new user API keys)
  function generateAPIkey()
  {
    // Generate random string for APIkey (20 letters long)
    $length = 20;
    $random_string = '';
    for($i = 0; $i < $length; $i++) {
        $number = random_int(0, 36);
        $character = base_convert($number, 10, 36);
        $random_string .= $character;
    }

    $stmt = $this->conn->prepare("INSERT INTO api_keys ( api_key ) VALUES (?)");
    $result = $stmt->execute([$random_string]);

  }

  // Returns error codes, closes dbConn and exits script for URL error
  function outputURLError()
  {

    $this->outputError("400", "error", "Query failed", "URL string has incorrect format.");

  }

  // Returns error codes, closes dbConn and exits script for URL error
  // With more refactoring all errors would be output using this code (giving much shorter and adaptable code)
  // Could also replace the successful output with similar function
  function outputError($code, $name, $desc, $data)
  {
    $output['status']['code'] = $code;
    $output['status']['name'] = $name;
    $output['status']['description'] = $desc;
    $output['data'] = $data;

    echo json_encode($output);
    $this->conn = null;
    exit;

  }

  function outputSuccess($code, $name, $desc, $data)
  {
    $output['status']['code'] = $code;
    $output['status']['name'] = $name;
    $output['status']['description'] = $desc;
    $output['status']['returnedIn'] = (microtime(true) - $this->executionStartTime) / 1000 . " ms";
    $output['data'] = $data;

    echo json_encode($output);
    $this->conn = null;
    exit;

  }

}
