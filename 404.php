<?php
//Inform the browser that this is a 404 page
header("HTTP/1.0 404 Not Found");

$title = "Page not Found"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<h1>That Was an Error! 404</h1>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>