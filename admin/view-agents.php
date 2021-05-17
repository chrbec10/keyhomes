<?php
$title = "Agents"; //The Page Title
$secure = true;
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database
require_once('includes/admin-header.php'); //Add admin formatting
?>

<h2>View Agents</h2>
<br>

<?php

//Select properties, joining on the agent information by ID
$sql = "SELECT * FROM agent";

//If our query is successful
if($stmt = $pdo->query($sql)){
    if($stmt->rowCount() > 0){

        //Generate table header
        //TODO View/Edit/Delete cell + New button
        echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>Icon</th>";
                    echo "<th>ID</th>";
                    echo "<th>First Name</th>";
                    echo "<th>Last Name</th>";
                    echo "<th>Email</th>";
                    echo "<th>Work Phone</th>";
                    echo "<th>Mobile</th>";
                    echo "<th>Actions</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            //Display agents in table
            while ($row = $stmt->fetch()){
                echo "<tr>";
                    //Icon
                    echo "<td><img class='admin-icon' src='../uploads/agents/" . $row['icon'] . "'></td>";
                    //ID Number
                    echo "<td>" . $row['agent_ID'] . "</td>";
                    //First Name
                    echo "<td>" . $row['fname'] . "</td>";
                    //Last Name
                    echo "<td>" . $row['lname'] . "</td>";
                    //Email
                    echo "<td><a href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a></td>";
                    //Work Phone
                    echo "<td><a href='tel:" . $row['phone'] . "'>" . $row['phone'] . "</a></td>";
                    //Mobile
                    echo "<td><a href='mailto:" . $row['mobile'] . "'>" . $row['mobile'] . "</a></td>";
                    //Actions
                    echo '<td><span class="me-2"><a href="edit-agent.php?id=' . $row['agent_ID'] . '"><i class="fa fa-pencil-alt"></i></a></span>
                    <span class="me-2"><a href="delete-agent.php?id=' . $row['agent_ID'] . '"><i class="fa fa-trash-alt"></i></a></span>';
                echo "</tr>";
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