<?php
$title = "Search"; //The Page Title
$secure = true;
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database

//If we get POSTed an agent ID
if (isset($_POST['id']) && !empty(trim($_POST['id']))){
    $agent_ID = trim($_POST['id'])

    $sql = "SELECT * FROM property WHERE agent_ID = :id";

    if ($stmt = $pdo->prepare($sql)){

        $stmt->bindParam(":id", $param_agent_ID);

        $param_agent_ID = $agent_ID;

        if($stmt->execute()){
            if($stmt->rowCount() > 0);
                $properties = $stmt->fetchAll();
                var_dump($properties);
        }
    }
}



require_once('includes/admin-header.php'); //Add admin formatting
?>

<h2>Search listings by Agent</h2>
<br>



<?php 
require_once('includes/admin-footer.php'); //Close out admin formatting
require_once('../includes/layouts/footer.php'); //Gets the footer 
?>