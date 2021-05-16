<?php

$title = "Delete Agent"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database
require_once('includes/admin-header.php'); //Add admin formatting

$agent = '';
$currentProps = '';

if(isset($_GET['id']) && !empty($_GET['id'])){
    //Prepare an SQL statment
    $sql = "SELECT * FROM agent WHERE agent_ID = :id";

    if ($stmt = $pdo->prepare($sql)){
        //Bind variables
        $stmt->bindParam(":id", $param_ID);
        $param_ID = trim($_GET['id']);

        //Attempt query
        if ($stmt->execute()){
            if ($stmt->rowCount() == 1){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $agent = $row['fname'] . ' ' . $row['lname'];

                $sql = "SELECT COUNT(agent_ID) FROM property WHERE agent_ID = :id";
                if ($stmt = $pdo->prepare($sql)){
                    //Bind variables
                    $stmt->bindParam(":id", $param_ID);
                    $param_ID = trim($_GET['id']);
                    
                    //Attempt query
                    if ($stmt->execute()){
                        $currentProps = $stmt->fetch();

                    } else {
                        echo "Oops! Something went wrong";
                    }
                }

            } else {
                header("location: ../404.php");
            }
            //clear variables
            unset($stmt);
            unset($row);
        } else {
        echo "Oops! Something went wrong"; 
        }
    }
}

if(isset($_POST['id']) && !empty($_POST['id'])){

    $sql = "SELECT gallery.image FROM gallery INNER JOIN property ON property.property_ID = gallery.property_ID WHERE property.agent_ID = :id";
    if ($stmt = $pdo->prepare($sql)){

        $stmt->bindParam(":id", $param_ID);
        $param_ID = trim($_POST['id']);

        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                while($row = $stmt->fetch()){
                    foreach(glob('../uploads/properties/*' . $row['image']) as $image){
                        unlink($image);
                    }
                }
            }
        }
    }
    //Prepare an SQL statement
    $sql = "DELETE FROM agent WHERE agent_ID = :id";

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

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="alert alert-danger">
        <input type="hidden" name="id" id="id" value="<?php echo trim($_GET["id"]); ?>"/>
        <p>Are you sure you want to remove agent <?php echo $agent; ?>?</p>
        <p>This action cannot be undone</p>
        <?php if($currentProps[0] > 0){echo "<p><b>WARNING: This agent currently has " . $currentProps[0] . " properties assigned to them. These will be deleted if you delete the agent assigned to them.</b></p>";}?>
        <p><input type="submit" value="Delete" class="btn btn-danger">
        <a href="edit-agent.php?id=<?php echo trim($_GET["id"]); ?>" class="btn btn-secondary ml-2">Cancel</a></p>
    </div>
</form>

<?php 
require_once('includes/admin-footer.php'); //Close out admin formatting
require_once('../includes/layouts/footer.php'); //Gets the footer 
?>