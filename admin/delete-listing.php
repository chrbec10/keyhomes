<?php

$title = "Delete Property"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database

$address = '';


if(isset($_GET['id']) && !empty($_GET['id'])){
    //Prepare an SQL statment
    $sql = "SELECT * FROM property WHERE property_ID = :id";

    if ($stmt = $pdo->prepare($sql)){
        //Bind variables
        $stmt->bindParam(":id", $param_ID);
        $param_ID = trim($_GET['id']);

        //Attempt query
        if ($stmt->execute()){
            if ($stmt->rowCount() == 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $address = $row['streetNum'] . ' ' . $row['street'] . ', ' . $row['city'] . ' ' . $row['postcode'];

            } else {
                header("location: ../404.php");
            }
        }  
    }
}

if(isset($_POST['id']) && !empty($_POST['id'])){
    //Gather gallery info for deleting gallery images
    $sql = "SELECT image FROM gallery WHERE property_ID = :id";

    if ($stmt = $pdo->prepare($sql)){
        //Bind variables
        $stmt->bindParam(":id", $param_ID);
        $param_ID = trim($_POST['id']);

        //Attempt to execute the prepared statement
        if($stmt->execute()){

            //Erase all gallery images attached to the listing from the server
            if($stmt->rowCount() > 0){

                while($row = $stmt->fetch()){
                    foreach(glob('../uploads/properties/*' . $row['image']) as $image){
                        unlink($image);
                    }
                }
            }
            //Prepare an SQL statement
            $sql = "DELETE FROM property WHERE property_ID = :id";

            if ($stmt = $pdo->prepare($sql)){
                //Bind variables
                $stmt->bindParam(":id", $param_ID);
                $param_ID = trim($_POST['id']);

                //Attempt to execute the prepared statement
                if($stmt->execute()){
                    header("location: success.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong.";
                }
            }
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
                <p>Are you sure you want to delete the record for <?php echo $address; ?>?</p>
                <p>This action cannot be undone</p>
                <p><input type="submit" value="Delete" class="btn btn-danger">
                <a href="index.php" class="btn btn-secondary ml-2">Cancel</a></p>
            </div>
        </form>
    </div>
</div>


<?php require_once('../includes/layouts/footer.php'); //Gets the footer ?>