<?php

  include("generateAPIkey.php");
  include("dbConn.php");

  ini_set('display_errors', 'On');
  error_reporting(E_ALL);

  header('Content-Type: application/json; charset=UTF-8');

  // Start db connection object (includes functions for CRUD)
  $dbConn = new DBConn();

  // Tries to get APIkey
  try {
    // Throws error if header not available
    $headers = apache_request_headers();
    if(!array_key_exists('APIKEY', $headers)){
      throw new \Exception("No API key in header.");
    }
    $apiKey = $headers['APIKEY'];
    $dbConn->checkAPIkey($apiKey);
  } catch (Exception $e) {
    $dbConn->outputError("401", "error", "Auth failed.", $e->getMessage());
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
      $dbConn->outputURLError();
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
      $dbConn->outputURLError();
    }

  }

  // HTTP verb for deleting users
  if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
      $url = parse_url($_SERVER['REQUEST_URI']);
      if(!array_key_exists('query', $url)){
        throw new \Exception("No query string for id.");
      }
      parse_str($url['query'], $DELETE);
      $id = $DELETE['id'];
      $dbConn->deleteUser($id);
    } catch (Exception $e) {
      $dbConn->outputURLError();
    }

  }
