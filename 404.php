<?php
//Inform the browser that this is a 404 page
header("HTTP/1.0 404 Not Found");

$title = "Page not Found"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<div class="content-top-padding">
  <div class="container-fluid bg-light text-center p-5">
    <h1 class="my-5">404: Page Not Found</h1>
  </div>
</div>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>