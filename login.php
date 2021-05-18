<?php
session_start();

//If the user is logged in, redirect them to the home page
if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin']) {
  header('location: index.php');
  exit;
}

$verification_err = false;
$username = '';
$password = '';

//If this page was posted to, check the credentials
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  require_once('./includes/db.php'); //Connect to the database

  //Get the Username and Password for use in this script
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  //If there's no username provided, set error
  if (empty($username)) {
    $username_err = "The Username field cannot be empty.";
  }

  //If there's no password provided, set error
  if (empty($password)) {
    $password_err = 'The Password field cannot be empty';
  }

  //If there's no errors...
  if (empty($username_err) && empty($password_err)) {

    $sql = "SELECT user_ID, username, password, isAdmin FROM users WHERE username = :username";

    if ($stmt = $pdo->prepare($sql)) {

      $stmt->bindParam(":username", $username);

      if ($stmt->execute()) {
        if ($stmt->rowCount() != 1) {
          $verification_err = true;
        } else {
          $user = $stmt->fetch(PDO::FETCH_ASSOC);

          if (password_verify($password, $user['password'])) {

            $_SESSION["loggedin"] = true;
            $_SESSION['id'] = $user['user_ID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['isAdmin'] = $user['isAdmin'];

            if (!empty($_POST['redirectToListing'])) {
              header("location: listing.php?id={$_POST['redirectToListing']}");
            } else {
              header('location: index.php');
            }
          } else {
            $verification_err = true;
          }
        }
      }
    }
  }
}

$title = "Login"; //The Page Title
require_once('./includes/layouts/header.php');
?>
<div class="container content-top-padding">

  <div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5 ">
      <h1>Login</h1>

      <!-- If there was an error, show it -->
      <?php if ($verification_err) : ?>
      <div class="alert alert-danger mb-1" role="alert">
        Username or Password was incorrect
      </div>
      <?php endif; ?>
      <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
        <div class="form-group mb-1">
          <label>Username</label>
          <input type="text" name="username" title="username"
            class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
            value="<?php echo $username; ?>" autofocus>
          <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group mb-3">
          <label>Password</label>
          <input type="password" name="password" title="password"
            class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
            value="<?php echo $password; ?>">
          <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>

        <input type="hidden" name="redirectToListing"
          value="<?php echo (!empty($_GET['redirectToListing'])) ? $_GET['redirectToListing'] : '' ?>">

        <input type="submit" class="btn btn-primary w-100 mb-1" value="Submit">

        <p>Need an account? <a href="register.php">Register here</a>.</p>
      </form>
    </div>
  </div>
</div>


<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>