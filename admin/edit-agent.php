<?php

$title = "New Agent"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to the database

//Defining variables
$fname = $lname = $icon = $email = $phone = $mobile = '';
$fname_err = $lname_err = $icon_err = $email_err = $phone_err = $mobile_err = '';

//validate inputs aren't empty
function validateInput($input = '', &$err = '', &$output = '', $errMsg = '') {
    if (empty($input)){
        $err = $errMsg;

    } else {
        $output = $input;
    }
}

//Process form data on submit
if (isset($_POST['id']) && !empty(trim($_POST['id']))){

    //Grab ID of agent to be edited
    $agent_ID = trim($_POST["id"]);

    //validate first name
    $input_fname = trim($_POST["fname"]);
    validateInput($input_fname, $fname_err, $fname, "Please enter a first name");

    //validate last name
    $input_lname = trim($_POST["lname"]);
    validateInput($input_lname, $lname_err, $lname, "Please enter a last name");

    //validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email address";
        
    } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a valid email";

    } else {
        $email = $input_email;
    }

    //validate phone number
    $input_phone = trim($_POST["phone"]);
    validateInput($input_phone, $phone_err, $phone, "Please enter a phone number");

    //validate mobile number
    $input_mobile = trim($_POST["mobile"]);
    validateInput($input_mobile, $mobile_err, $mobile, "Please enter a mobile number");
    if(empty($input_mobile)){
        $mobile_err = "Please enter a mobile number";

    } elseif(!filter_var($input_mobile, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9\+\s]*$/")))){
        $mobile_err = "Please enter a valid mobile number";

    } else {
        $mobile = $input_mobile;
    }


    //check for input errors before trying to update database
    if (empty($fname_err) && empty($lname_err) && empty($icon_err) && empty($email_err) && empty($phone_err) && empty($mobile_err)){
        $sql = "UPDATE agent SET fname = :fname, lname = :lname, email = :email, phone = :phone, mobile = :mobile
        WHERE agent_ID = :agent_ID";

        if ($stmt = $pdo->prepare($sql)){

            //Bind variables to the prepared statement as parameters
            $stmt->bindParam(":fname", $param_fname);
            $stmt->bindParam(":lname", $param_lname);
            $stmt->bindParam(":email", $param_email);
            $stmt->bindParam(":phone", $param_phone);
            $stmt->bindParam(":mobile", $param_mobile);
            $stmt->bindParam(":agent_ID", $param_agent_ID);

            //Set parameters
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_phone = $phone;
            $param_mobile = $mobile;
            $param_agent_ID = $agent_ID;

            //Try and execute the statement
            if ($stmt->execute()){
                header("location: success.php");
                exit();
            } else{
                echo "Oops! Something went wrong.";
            }
        }
        //Close the connection
        unset($pdo);
    }
} else {
    //Check whether we were given an ID before continuing
    if (isset($_GET['id']) && !empty(trim($_GET['id']))){
        //Get our ID parameter
        $agent_ID = trim($_GET['id']);

        //Prepare a select statement
        $sql = "SELECT * FROM agent WHERE agent_ID = :agent_ID";
        if ($stmt = $pdo->prepare($sql)){

            //Bind variables to the select statement
            $stmt->bindParam(":agent_ID", $param_agent_ID);

            //Set parameter
            $param_agent_ID = $agent_ID;
            
            //Attempt the select statement
            if($stmt->execute()){
                //Check that we get exactly 1 row back
                if ($stmt->rowCount() == 1){
                    //Fetch as an associative array since we're getting only one row back
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    //Pull values from row
                    $fname = $row['fname'];
                    $lname = $row['lname'];
                    $icon = $row['icon'];
                    $email = $row['email'];
                    $phone = $row['phone'];
                    $mobile = $row['mobile'];
                    $agentName = strtolower($row['fname'][0] . $row['lname']);

                } else {
                    //URL doesn't contain a valid ID
                    header("location: ../404.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong.";
            }
        }
        //Close statement
        unset($stmt);

    } else {
        //We weren't given an ID
        header("location: ../404.php");
        exit();
    }
    
}

?>
<div class="content-top-padding pb-4 bg-light">
    <div class="container mt-4">
    <div class="alert alert-success">Good!</div>
    <div class="alert alert-danger">Bad...</div>
        <div class="container">
            <h2 class="text-center">Editing details for agent <?php echo $fname . ' ' . $lname ?></h2>
            <br>
            <img src= <?php echo '"../uploads/agents/' . $icon . '?=' . filemtime('../uploads/agents/' . $icon) . '"' ?> style="max-width:256px" class="rounded-circle mx-auto d-block">
        </div>
        <br>
        <form action="agent-icon.php" method="post" enctype="multipart/form-data">
        <h4 class="text-center">Upload new icon</h4>
            <div class="row">
                <div class="form-group col-md">
                    <input type="file" name="agentIcon" id="agentIcon" class="form-control">
                    <input type="hidden" id="id" name="id" value="<?php echo $agent_ID; ?>"/>
                    <input type="hidden" id="agentName" name="agentName" value="<?php echo $agentName; ?>"/>
                    <button tpye="submit" class="btn btn-primary">Submit</button>
                    <p><strong>Note:</strong> Maximum size of 512px x 512px and 2MB. Allowed formats: .jpg, .jpeg, .gif, or .png.</strong></p>
                </div>
            </div>
        </form>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h4 class="text-center">Agent details</h4>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" name="fname" id="fname" value="<?php echo $fname; ?>">
                    <span class="invalid-feedback"><?php echo $fname_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" name="lname" id="lname" value="<?php echo $lname; ?>">
                    <span class="invalid-feedback"><?php echo $lname_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="email">Email Address</label>
                    <input type="text" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" name="email" id="email" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="phone">Office Phone Number</label>
                    <input type="text" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" name="phone" id="phone" value="<?php echo $phone; ?>">
                    <span class="invalid-feedback"><?php echo $phone_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="mobile">Work Cellphone Number</label>
                    <input type="text" class="form-control <?php echo (!empty($mobile_err)) ? 'is-invalid' : ''; ?>" name="mobile" id="mobile" value="<?php echo $mobile; ?>">
                    <span class="invalid-feedback"><?php echo $mobile_err;?></span>
                </div>
            </div>
            <br>
            <input type="hidden" id="id" name="id" value="<?php echo $agent_ID; ?>"/>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="./" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>


<script>
function toggleDisplay(element){
    var toggle = document.getElementById(element);
    if (toggle.style.display === "none"){
        toggle.style.display = "block";
    }
    else {
        toggle.style.display = "none";
    }
};
</script>
<?php
require_once('../includes/layouts/footer.php'); //Gets the footer
?>