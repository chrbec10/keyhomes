<?php

$title = "Agents"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database
?>
<div class="content-top-padding pb-4 bg-light">
    <div class="container-fluid mt-4">
        <ul>
            <li><a href="new-listing.php">New Listing</a></li>
            <li><a href="new-agent.php">New Agent</a></li>
        </ul>
        <?php

        //Select all agents
        $sql = "SELECT * FROM agent ORDER BY agent_ID DESC";

        //If our query is successful
        if($stmt = $pdo->query($sql)){
            if($stmt->rowCount() > 0){

                //Generate table header
                //TODO View/Edit/Delete cell + New button
                echo "<table class='table table-bordered table-striped'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Name</th>";
                            echo "<th>Actions</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    //Display up to the 10 most recent entries in simple form
                    $count = 0;
                    while (($row = $stmt->fetch())&& $count < 10){
                        echo "<tr>";
                            //ID number
                            echo "<td>".$row['agent_ID']."</td>";
                            //Agent
                            echo "<td>" . $row['fname'] . ' ' . $row['lname'] . "</a></td>";
                            //Actions
                            echo '<td><span class="me-2"><a href="edit-agent.php?id=' . $row['agent_ID'] . '"><i class="fa fa-pencil-alt"></i></a></span>
                            <span class="me-2"><a href="delete-agent.php?id=' . $row['agent_ID'] . '"><i class="fa fa-trash-alt"></i></a></span>';
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
    </div>
</div>
<?php require_once('../includes/layouts/footer.php'); //Gets the footer ?>