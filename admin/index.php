<?php

$title = "Admin"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database
?>
<div style="padding-top:50px">;
    <?php
    $sql = "SELECT * FROM property JOIN agent ON agent.agent_ID = property.agent_ID";
    if($result = $pdo->query($sql)){
        if($result->rowCount() > 0){
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
                while ($row = $result->fetch()){
                    echo "<tr>";
                        echo "<td>".$row['property_ID']."</td>";
                        echo "<td>".$row['streetNum'].' '.$row['street'].', '.$row['city'].' '.$row['postcode']."</td>";
                        echo "<td>".$row['saleType']."</td>";
                        echo "<td>".'$'.$row['price']."</td>";
                        echo "<td>".$row['fname'].' '.$row['lname']."</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
            echo "</table>";
            unset($result);
        } else{
            echo "<div class='alert alert-danger'><em>No entires found.</em></div>";
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