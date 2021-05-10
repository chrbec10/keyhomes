<?php
header("refresh:5;url= ./");
$title = "Success"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
?>

<div class="content-top-padding pb-4 bg-light">
    <div class="container mt-4">
        <div class='alert alert-success'>
            <p class='text-center m-auto'>Operation completed successfully.</p>
            <p class='text-center m-auto'>Redirecting you to the Admin homepage in 5 seconds.</p>
        </div>
        <p class='text-center'><a href="./" class="btn btn-primary">Return to Admin Home</a></p>
    </div>
</div>

<?php require_once('../includes/layouts/footer.php'); //Gets the footer ?>