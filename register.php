<?php

$title = 'Register'; //Page title
require_once('./includes/db.php'); //Connect to the database

//Initializing variables with empty values
$username = $password = $pass_confirm = '';
$username_err = $password_err = $pass_confirm_err = '';


//Process registration data when form is submitted
//Fetches 'username', 'password', and 'pass_confirm'
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }
    else {
        //Prepare a SELECT statement to check for existing users
        $sql = "SELECT user_ID FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)){ 
            //Bind variables to the statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            //Fill parameters
            $param_username = trim($_POST["username"]);

            //Attempt our prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "A user with this username already exists.";
                }
                else {
                    $username = trim($_POST["username"]);
                }
            }
            else {
                echo "Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }

    //Validating password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    }
    elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Your password must be more than 6 characters long.";
    }
    else {
        $password = trim($_POST["password"]);
    }

    //Validating password confirmation
    if(empty(trim($_POST["pass_confirm"]))){
        $pass_confirm_err = "Please confirm your password.";
    }
    else {
        $pass_confirm = trim($_POST["pass_confirm"]);
        if(empty($password_err) && ($password != $pass_confirm)){
            $pass_confirm_err = "Password and confirmation do not match.";
        }
    }

    //Check for input errors before adding to database
    if(empty($username_err) && empty($password_err) && empty($pass_confirm_err)){

        //Prepare to insert username into database
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

        if($stmt = $pdo->prepare($sql)){
            //Bind variables to the statment as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            //Fill parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); //Hashes the password

            //Try our prepared statement
            if($stmt->execute()){
                //Redirect to the homepage
                header("location: index.php");
            }
            else{
                echo "Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}
?>