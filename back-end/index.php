<?php

  // Allows access to the API through CORS
  // Could be tightened up for certain IP
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header("Access-Control-Request-Headers: Apikey");
  header("Access-Control-Allow-Headers: Apikey");
  header('Content-Type: application/json; charset=UTF-8');

  include("DBConn.php");

  // To be turned off for production
  ini_set('display_errors', 'On');
  error_reporting(E_ALL);

  // Start db connection object (includes functions for CRUD)
  $dbConn = new DBConn();

  $dbConn->checkAPIkey();
  $dbConn->makeQuery();
