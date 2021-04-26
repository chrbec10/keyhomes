<?php
session_start();

//Redirect if the user is not logged in
if (!$_SESSION['loggedin']) {
  header('location: login.php');
  exit();
}

$title = "{$_SESSION['username']}'s Wishlist"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<div class="content-top-padding pb-3">
  <div class="container">
    <h1>My Wishlist</h1>
    <button class="btn btn-primary">Primary Button</button>
  </div>
</div>


<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>