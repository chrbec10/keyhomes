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

<div class="content-top-padding pb-4 bg-light">
  <div class="container mt-4">
    <h1>My Wishlist</h1>

    <?php

    $conditions[] = 'user_ID = ?';
    $parameters[] = $_SESSION['id'];

    require('./includes/search/search-block.php');

    ?>

  </div>
</div>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>