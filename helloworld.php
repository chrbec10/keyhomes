<?php

$title = "Hello World"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<h1>Hello World</h1>
<button class="btn btn-primary">Primary Button</button>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>