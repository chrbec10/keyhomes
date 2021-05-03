<?php

$title = "Admin"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database
?>
<div style="padding-top:50px">;

    <?php

    //Select properties, joining on the agent information by ID
    $sql = "SELECT * FROM property JOIN agent ON agent.agent_ID = property.agent_ID";

    //If our query is successful
    if($result = $pdo->query($sql)){
        if($result->rowCount() > 0){

            //Generate table header
            //TODO View/Edit/Delete cell + New button
            echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th>#</th>";
                        echo "<th>Address</th>";
                        echo "<th>Sale Type</th>";
                        echo "<th>Price</th>";
                        echo "<th>Agent</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                //Display up to the 10 most recent entries in simple form
                $count = 0;
                while (($row = $result->fetch())&& $count < 10){
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
                        echo "<td>".$row['fname'].' '.$row['lname']."</td>";
                    echo "</tr>";
                    $count = $count + 1;
                }
                echo "</tbody>";
            echo "</table>";

            //Clear variables
            unset($result);
        } else{
            echo "<div class='alert alert-danger'><em>No entries found.</em></div>";
        }
    } else{
        echo "Oops! Something seems to have gone wrong. Please try again later.";
    }
    unset($pdo);
    ?>
</div>
<?php
require_once('../includes/layouts/footer.php'); //Gets the footer
?>