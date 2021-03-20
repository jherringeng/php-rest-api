<?php

  include("getUser.php");
  include("insertUser.php");
  include("updateUser.php");
  include("deleteUser.php");
  include("getUsers.php");
  include("generateAPIkey.php");
  include("dbConn.php");

  $headers = apache_request_headers();

  $dbConn = new dbConn();

  if($dbConn->checkAPIkey($headers['APIKEY'])) {

    $output['status']['code'] = "401";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "Auth failed.";
  	$output['data'] = "Authorised.";

    echo json_encode($output);

    exit;

  } 

  if ($headers['APIKEY'] !== 'wpf0okfhmjoyb3v0gw16') {
    $output['status']['code'] = "401";
  	$output['status']['name'] = "ok";
  	$output['status']['description'] = "Auth failed.";
  	$output['data'] = "Not authorised.";

    echo json_encode($output);

    exit;

  } else {

    $data = $_SERVER['REQUEST_URI'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

      // Can use str_contains in PHP 8 (currently using PHP 7)
      if (strpos($data, '?') == false && strpos($data, '=') == false) {

        $dbConn->getUsers();

      } else {

        if(array_key_exists('id', $_GET)) {

          $id = $_GET['id'];
          $dbConn->getUser($id);

        } else {

          outputURLError();

        }

      }

    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

      $dbConn->insertUser();

    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

      $url = parse_url($_SERVER['REQUEST_URI']);
      parse_str($url['query'], $DELETE);

      // $output['status']['code'] = "200";
    	// $output['status']['name'] = "ok";
    	// $output['status']['description'] = "success";
    	// $output['data'] = $DELETE;
      //
      // echo json_encode($output);
      //
      // exit;

      if(array_key_exists('id', $DELETE)) {

        $id = $DELETE['id'];
        $dbConn->deleteUser($id);

      } else {

        outputURLError();

      }

      // $id = explode('?', $_SERVER['REQUEST_URI']);
      // $id = explode('=', $id[1]);
      // $id = $id[1];
      //
      // $dbConn->deleteUser($id);

    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      if(array_key_exists('id', $_POST)) {

        $id = $_POST['id'];
        $dbConn->updateUser($id);

      } else {

        outputURLError();

      }

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


  function outputURLError() {

    $output['status']['code'] = "400";
    $output['status']['name'] = "executed";
    $output['status']['description'] = "query failed";
    $output['data'] = "URL string has incorrect format.";

    echo json_encode($output);

    exit;

  }
