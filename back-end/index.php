<?php

  include("getUser.php");
  include("insertUser.php");
  include("updateUser.php");
  include("deleteUser.php");
  include("getUsers.php");
  include("generateAPIkey.php");
  include("dbConn.php");

  $headers = apache_request_headers();

  if ($headers['APIKEY'] !== 'wpf0okfhmjoyb3v0gw16') {
    $output['status']['code'] = "401";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "Auth failed.";
  	$output['data'] = "Not authorised.";

    echo json_encode($output);

    exit;

  } else {

    $dbConn = new dbConn();

    $data = $_SERVER['REQUEST_URI'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

      // Can use str_contains in PHP 8
      if (strpos($data, '?') !== false && strpos($data, '=') !== false) {
        $id = explode('?', $_SERVER['REQUEST_URI']);
        $id = explode('=', $id[1]);
        $id = $id[1];

        $dbConn->getUser($id);

      } else {

        $dbConn->getUsers();

      }

    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

      $dbConn->insertUser();

    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

      $id = explode('?', $_SERVER['REQUEST_URI']);
      $id = explode('=', $id[1]);
      $id = $id[1];

      $dbConn->deleteUser($id);

    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // Can use str_contains in PHP 8
      if (strpos($data, '?') !== false) {
        $id = explode('?', $_SERVER['REQUEST_URI']);
        $id = explode('=', $id[1]);
        $id = $id[1];

        $dbConn->updateUser($id);

      } else {

        $output['status']['code'] = "400";
    		$output['status']['name'] = "executed";
    		$output['status']['description'] = "query failed";
    		$output['data'] = "URL string has incorrect format.";

      }

    }


    // // Testing code
    // $headers = apache_request_headers();
    // // $headerStringValue = json_encode($headerStringValue)
    // $APIkey = secure_random_string(20);
    //

    // $output['status']['code'] = "200";
  	// $output['status']['name'] = "ok";
  	// $output['status']['description'] = "success";
  	// $output['data'] = $APIkey;
    //
    // echo json_encode($output);

  }

?>
