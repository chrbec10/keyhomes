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
        $email_err = "Please enter an email address"
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

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

        if $stmt = ($pdo->prepare($sql)){

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

<?php
require_once('../includes/layouts/footer.php'); //Gets the footer
?>