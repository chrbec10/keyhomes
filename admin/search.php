<?php
$title = "Search"; //The Page Title
$secure = true;
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database

$agent_ID = '';
$agent_ID_err = '';
$showTable = false;

//If we get POSTed an agent ID
if (isset($_POST['agent']) && !empty(trim($_POST['agent']))){

    $input_agent_ID = trim($_POST["agent"]);
    
    if (isset($input_agent_ID)){
        $agent_ID = $input_agent_ID;
    }


    $sql = "SELECT * FROM property JOIN agent ON agent.agent_ID = property.agent_ID WHERE agent.agent_ID = :id ORDER BY property.property_ID DESC";

    if ($stmt = $pdo->prepare($sql)){

        $stmt->bindParam(":id", $param_agent_ID);

        $param_agent_ID = $agent_ID;

        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $showTable = true;
                $properties = $stmt->fetchAll();
            }
        }
    }
}

require_once('includes/admin-header.php'); //Add admin formatting
?>

<h2>Search listings by Agent</h2>
<br>
<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
    <div class="input-group">
        <select name='agent' id='agent' class='form-control <?php echo (!empty($agent_ID_err)) ? 'is-invalid' : ''; ?>'>
            <option value='' <?php if (!isset($agent_ID)){ echo "selected"; } ?>>Select an Agent</option>
            <?php
                $sql = "SELECT fname, lname, agent_ID FROM agent";
                if ($result = $pdo->query($sql)){
                    if ($result->rowCount() > 0){
                        while ($row = $result->fetch()){
                            if ($row['agent_ID'] == $agent_ID){
                                echo '<option selected value="' . $row['agent_ID'] . '">' . $row['agent_ID'] . ' | ' . $row['fname'] . ' ' . $row['lname'] . '</option>';
                            } else {
                            echo '<option value="' . $row['agent_ID'] . '">' . $row['agent_ID'] . ' | ' . $row['fname'] . ' ' . $row['lname'] . '</option>';
                            }
                        }
                        echo "</select>";
                        echo "<button type='submit' class='btn btn-primary input-group-append'>Search</button>";
                        echo "</div>";
                        echo "</form>";
                    } else {
                        echo "</select>";
                        echo "</div>";
                        echo "</form>";
                        echo "<div class='alert alert-warning'>No agents to retrieve. Try creating one first.</div>";
                    }
                } else {
                    echo "</select>";
                    echo "</div>";
                    echo "</form>";
                    echo "<div class='alert alert-danger'>Unable to retrieve agents. Something went wrong. Please try again later.</div>";
                }
            ?>
</form>
<br>
<?php
if($showTable){
    echo "<div class='table-responsive'>";
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
        foreach($properties as $listing){
            echo "<tr>";
                //ID number
                echo "<td>".$listing['property_ID']."</td>";
                //Address + link to listing
                echo "<td><a href='../listing.php?id=".$listing['property_ID']."'>".$listing['streetNum'].' '.$listing['street'].', '.$listing['city'].' '.$listing['postcode']."</a></td>";
                //Sale type
                echo "<td>".$listing['saleType']."</td>";
                //Price
                echo "<td>".'$'.$listing['price']."</td>";
                //Agent
                echo "<td><a href='edit-agent.php?id=".$listing['agent_ID']."'>".$listing['fname'].' '.$listing['lname']."</a></td>";
                //Actions
                echo '<td><span class="me-2"><a href="edit-listing.php?id=' . $listing['property_ID'] . '"><i class="fa fa-pencil-alt"></i></a></span>
                <span class="me-2"><a href="delete-listing.php?id=' . $listing['property_ID'] . '"><i class="fa fa-trash-alt"></i></a></span>';
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    echo "</div>";
}
?>

<?php 
unset($pdo);
require_once('includes/admin-footer.php'); //Close out admin formatting
require_once('../includes/layouts/footer.php'); //Gets the footer 
?>