<?php

$title = "Register"; //The Page Title
require_once('./includes/layouts/header.php');
require_once('register.php'); //Connect to the database
?>

<h1>this is the admin page</h1>
<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="pass_confirm" class="form-control <?php echo (!empty($pass_confirm_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pass_confirm; ?>">
                <span class="invalid-feedback"><?php echo $pass_confirm_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>