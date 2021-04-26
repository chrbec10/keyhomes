<?php

session_start();

if ($_SESSION['loggedin']) {

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once('../includes/db.php');

    $sql = "
    IF EXISTS(SELECT * FROM wishlist WHERE user_ID = 1 AND property_ID = 2)
    THEN
    DELETE FROM wishlist WHERE user_ID = 1 AND property_ID = 2
    ELSE
    INSERT INTO wishlist(user_ID, property_ID) VALUES 1, 2
    
    ";

    // $response = ['user' => $_SESSION['id'], 'wishlisted' => false];

    // header('Content-Type: application/json');
    // echo json_encode($response);
  }
} else {
  http_response_code(403);
}