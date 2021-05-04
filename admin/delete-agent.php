<?php

$title = "Delete Agent"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database

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
        } 
        echo "Oops! Something went wrong"; 
    }
}

if(isset($_POST['id']) && !empty($_POST['id'])){
    //Prepare an SQL statement
    $sql = "DELETE FROM agent WHERE agent_ID = :id";

    if ($stmt = $pdo->prepare($sql)){
        //Bind variables
        $stmt->bindParam(":id", $param_ID);
        $param_ID = trim($_POST['id']);

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
                <p>Are you sure you want to remove agent <?php echo $agent; ?>?</p>
                <p>This action cannot be undone</p>
                <?php if($currentProps[0] > 0){echo "<p><b>WARNING: This agent currently has " . $currentProps[0] . " properties assigned to them. These will be deleted if you delete the agent assigned to them.</b></p>";}?>
                <p><input type="submit" value="Delete" class="btn btn-danger">
                <a href="index.php" class="btn btn-secondary ml-2">Cancel</a></p>
            </div>
        </form>
    </div>
</div>


<?php require_once('../includes/layouts/footer.php'); //Gets the footer ?>