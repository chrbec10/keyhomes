<?php

$title = "Delete Property"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database



if(isset($_POST['id']) && !empty($_POST['id'])){
    //Prepare an SQL statement
    $sql = "DELETE FROM property WHERE property_ID = :id";

    if ($stmt = $pdo->prepare($sql)){
        //Bind variables
        $stmt->bindParam(":id", $param_id);
        $param_id = trim($_POST['id']);

        //Attempt to execute the prepared statement
        if($stmt->execute()){
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong.";
        }
    }
    //close statement
    unset($stmt);

    //close connection
    unset($pdo);
} else {
    //check for ID
    if(empty(trim($_GET['id']))){
        header("location: ../404.php");
        exit();
    }
}
?>
<div class="content-top-padding pb-4 bg-light">
    <div class="container mt-4">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="alert alert-danger">
                <input type="hidden" name="id" id="id" value="<?php echo trim($_GET["id"]); ?>"/>
                <p>Are you sure you want to delete this record?</p>
                <p>This action cannot be undone</p>
                <p><input type="submit" value="Delete" class="btn btn-danger"><a href="index.php" class="btn btn-secondary ml-2">Cancel</a></p>
            </div>
        </form>
    </div>
</div>


<?php require_once('../includes/layouts/footer.php'); //Gets the footer ?>