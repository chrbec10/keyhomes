<?php

$title = "Home"; //The Page Title

require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<h1>This is the home page!</h1>
<button class="btn btn-primary">Primary Button</button>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>