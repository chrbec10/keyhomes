<?php
/* This service allows the user to add or remove items from their wishlist without refreshing the page */

session_start();

//Check whether the user is logged in
if ($_SESSION['loggedin']) {

  //Check whether the request was made as a POST request
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once('../includes/db.php');

    header('Content-Type: application/json'); //Set the response type to Json

    $user_id = $_SESSION['id'];
    $property_id = trim($_POST['propertyid']);


    $sql = 'SELECT * FROM wishlist WHERE user_ID = :user_id AND property_ID = :property_id';

    if ($stmt = $pdo->prepare($sql)) {

      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':property_id', $property_id);

      if ($stmt->execute()) {


        //Setup a response to use in both cases
        $response = ['user' => $_SESSION['id'], 'property_id' => $property_id];

        //If there was a result, delete it
        if ($stmt->rowCount() == 1) {

          $sql = 'DELETE FROM wishlist WHERE user_ID = :user_id AND property_ID = :property_id';

          if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':property_id', $property_id);

            if ($stmt->execute()) {
              $response['wishlisted'] =  'false';
              echo json_encode($response);
            }
          }
        } else {
          $sql = 'INSERT INTO wishlist (user_ID, property_ID) VALUES (:user_id, :property_id)';

          if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':property_id', $property_id);

            if ($stmt->execute()) {
              $response['wishlisted'] =  'true';
              echo json_encode($response);
            }
          }
        }
      }
    }
  }
} else {
  http_response_code(403);
}