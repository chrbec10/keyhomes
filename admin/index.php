<?php
$title = "Admin"; //The Page Title
$secure = true;
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database
require_once('includes/admin-header.php'); //Add admin formatting
?>

<h2>Admin Home - Recent Listings</h2>
<br>

<?php

//Select properties, joining on the agent information by ID
$sql = "SELECT * FROM property JOIN agent ON agent.agent_ID = property.agent_ID ORDER BY property_ID DESC";

//If our query is successful
if($stmt = $pdo->query($sql)){
    if($stmt->rowCount() > 0){

        //Generate table header
        //TODO View/Edit/Delete cell + New button
        echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Address</th>";
                    echo "<th>Sale Type</th>";
                    echo "<th>Price</th>";
                    echo "<th>Agent</th>";
                    echo "<th>Actions</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            //Display up to the 10 most recent entries in simple form
            $count = 0;
            while (($row = $stmt->fetch())&& $count < 20){
                echo "<tr>";
                    //ID number
                    echo "<td>".$row['property_ID']."</td>";
                    //Address + link to listing
                    echo "<td><a href='../listing.php?id=".$row['property_ID']."'>".$row['streetNum'].' '.$row['street'].', '.$row['city'].' '.$row['postcode']."</a></td>";
                    //Sale type
                    echo "<td>".$row['saleType']."</td>";
                    //Price
                    echo "<td>".'$'.$row['price']."</td>";
                    //Agent
                    echo "<td><a href='edit-agent.php?id=".$row['agent_ID']."'>".$row['fname'].' '.$row['lname']."</a></td>";
                    //Actions
                    echo '<td><span class="me-2"><a href="edit-listing.php?id=' . $row['property_ID'] . '"><i class="fa fa-pencil-alt"></i></a></span>
                    <span class="me-2"><a href="delete-listing.php?id=' . $row['property_ID'] . '"><i class="fa fa-trash-alt"></i></a></span>';
                echo "</tr>";
                $count = $count + 1;
            }
            echo "</tbody>";
        echo "</table>";

        //Clear variables
        unset($stmt);
    } else{
        echo "<div class='alert alert-danger'><em>No entries found.</em></div>";
    }
} else{
    echo "Oops! Something seems to have gone wrong. Please try again later.";
}
unset($pdo);
?>

<?php 
require_once('includes/admin-footer.php'); //Close out admin formatting
require_once('../includes/layouts/footer.php'); //Gets the footer 
?>