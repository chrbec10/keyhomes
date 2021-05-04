<?php

$title = "Success"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
?>

<div class="content-top-padding pb-4 bg-light">
    <div class="container mt-4">
        <div class='alert alert-success'>
            <p>Operation completed successfully.</p>
            <p><a href="index.php" class="btn btn-primary">Return Home</a></p>
        </div>
    </div>
</div>

<?php require_once('../includes/layouts/footer.php'); //Gets the footer ?>