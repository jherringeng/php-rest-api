<?php

  include("getUser.php");
  include("insertUser.php");
  include("updateUser.php");
  include("deleteUser.php");
  include("getUsers.php");


  $data = $_SERVER['REQUEST_URI'];

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Can use str_contains in PHP 8
    if (strpos($data, '?') !== false && strpos($data, '=') !== false) {
      $id = explode('?', $_SERVER['REQUEST_URI']);
      $id = explode('=', $id[1]);
      $id = $id[1];

      getUser($id);

    } else {
      getUsers();
    }

  }

  if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    insertUser();

  }

  if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $id = explode('?', $_SERVER['REQUEST_URI']);
    $id = explode('=', $id[1]);
    $id = $id[1];

    deleteUser($id);

  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Can use str_contains in PHP 8
    if (strpos($data, '?') !== false) {
      $id = explode('?', $_SERVER['REQUEST_URI']);
      $id = explode('=', $id[1]);
      $id = $id[1];

      updateUser($id);

    } else {

      $output['status']['code'] = "400";
  		$output['status']['name'] = "executed";
  		$output['status']['description'] = "query failed";
  		$output['data'] = "URL string has incorrect format.";

    }

  }

?>
