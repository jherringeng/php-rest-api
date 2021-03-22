<?php

  include("getUser.php");
  include("insertUser.php");
  include("updateUser.php");
  include("deleteUser.php");
  include("getUsers.php");
  include("generateAPIkey.php");
  include("dbConn.php");

  // Start db connection object (includes functions for CRUD)
  $dbConn = new DBConn();

  // Tries to get APIkey
  try {

    $headers = apache_request_headers();
    $dbConn->checkAPIkey($headers['APIKEY']);

  } catch (Exception $e) {

    $output['status']['code'] = "401";
    $output['status']['name'] = "ok";
    $output['status']['description'] = "Auth failed.";
    $output['data'] = "No API key in header.";

    $this->conn = NULL;

    echo json_encode($output);

    exit;
  }

  // Server uses HTTP verbs to determine actions (GET, POST, PUT, DELETE)
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $url = parse_url($_SERVER['REQUEST_URI']);
    if(array_key_exists('id', $_GET)) {

      $id = $_GET['id'];
      $dbConn->getUser($id);

    } else if (!array_key_exists('query', $url)) {

      $dbConn->getUsers();

    }

    else {

      outputURLError();

    }

  }

  // HTTP verb for inserting users
  if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $dbConn->insertUser();

  }

  // HTTP verb for updating users
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(array_key_exists('id', $_POST)) {

      $id = $_POST['id'];
      $dbConn->updateUser($id);

    } else {

      outputURLError();

    }

  }

  // HTTP verb for deleting users
  if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    try {

      $url = parse_url($_SERVER['REQUEST_URI']);
      parse_str($url['query'], $DELETE);
      $id = $DELETE['id'];
      $dbConn->deleteUser($id);

    } catch (Exception $e) {

      outputURLError();

    }

  }

  // Returns error codes, closes dbConn and exits script for URL error
  function outputURLError() {

    $output['status']['code'] = "400";
    $output['status']['name'] = "executed";
    $output['status']['description'] = "query failed";
    $output['data'] = "URL string has incorrect format.";

    echo json_encode($output);

    $this->conn = NULL;

    exit;

  }
