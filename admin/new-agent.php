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

if ($_SERVER["REQUEST_METHOD"] == "POST"){

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


    //check for input errors before trying to insert into database
    if (empty($fname_err) && empty($lname_err) && empty($icon_err) && empty($email_err) && empty($phone_err) && empty($mobile_err)){
        $sql = "INSERT INTO agent (fname, lname, email, phone, mobile)
        VALUES (:fname, :lname, :email, :phone, :mobile)";

        if ($stmt = $pdo->prepare($sql)){

            //Bind variables to the prepared statement as parameters
            $stmt->bindParam(":fname", $param_fname);
            $stmt->bindParam(":lname", $param_lname);
            $stmt->bindParam(":email", $param_email);
            $stmt->bindParam(":phone", $param_phone);
            $stmt->bindParam(":mobile", $param_mobile);

            //Set parameters
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_phone = $phone;
            $param_mobile = $mobile;

            //Try and execute the statement
            if ($stmt->execute()){
                header("location: index.php");
                exit();

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        //Close the connection
        unset($pdo);
    }
}

?>
<div style="padding-top:70px; padding-bottom:20px;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
require_once('../includes/layouts/footer.php'); //Gets the footer
?>