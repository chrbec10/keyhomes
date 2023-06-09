<?php
require_once(__DIR__ . '/../config.php');

//Set Response Headers
header('Content-Type: text/html; charset=UTF-8');

if (session_id() == "") {
  session_start();
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$is_admin = false;

//If the user is not logged in, set logged in to false
if (!isset($_SESSION['loggedin'])) {
  $_SESSION['loggedin'] = false;
}

//If the user is marked as admin, set admin tag to expose admin panel link
if (isset($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == true)) {
  $is_admin = true;
}

//If the page as marked as secure, pretend it doesn't exist
if (isset($secure) && $secure == true && $is_admin != true) {
  header("location: ../404.php", 404);
}

$home_nav = $home_nav ?? false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
  //If a page desctiption has been declared, use it, otherwise use the default description
  $meta_description = $meta_description ?? "Massive catalogue of affordable housing. Find your Perfect Home at Key Homes" ?>
  <meta name="description" content="<?php echo $meta_description ?>">

  <!-- Only add title if variable is not empty when this file is parsed -->
  <title>Key Homes<?php echo (!empty($title)) ? " | {$title}" : '' ?></title>

  <!-- Icons -->
  <link rel="shortcut icon" href="<?php echo $site_root ?>/static/img/logos/icon32.png" type="image/x-icon"
    sizes="32x32">
  <link rel="shortcut icon" href="<?php echo $site_root ?>/static/img/logos/icon128.png" type="image/x-icon"
    sizes="128x128">
  <link rel="shortcut icon" href="<?php echo $site_root ?>/static/img/logos/icon192.png" type="image/x-icon"
    sizes="192x192">
  <link rel="shortcut icon" href="<?php echo $site_root ?>/static/img/logos/icon512.png" type="image/x-icon"
    sizes="512x512">

  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo $site_root ?>/static/css/theme.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!--Fonts-->

  <!-- Early loaded Scripts -->
  <script src="<?php echo $site_root ?>/static/js/bootstrap.bundle.js" defer="true"></script>
</head>

<body class="bg-dark">
  <!-- Styles the Navbar to be transparent if $home_nav is true -->
  <nav
    class="navbar navbar-expand-lg fixed-top main-nav <?php echo ($home_nav) ? 'home-nav transparent' : 'navbar-light bg-white' ?>"
    id="mainNav">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo $site_root ?>/">
        <img src="<?php echo $site_root ?>/static/img/logos/logo.svg" class="logo-dark" alt="KeyHomes Logo"
          height="45px" width="124px">
        <img src="<?php echo $site_root ?>/static/img/logos/logo-inverted.svg" class="logo-light" alt="KeyHomes Logo"
          height="45px" width="124px">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavContent"
        aria-label="Toggle Navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavContent">

        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center text-lg-right">
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo $site_root ?>/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active  mb-2 mb-lg-0" href="<?php echo $site_root ?>/listings.php">Listings</a>
          </li>

          <!-- User IS logged in -->
          <?php if ($_SESSION['loggedin']) : ?>

          <li class="nav-item">
            <a class="nav-link active" href="<?php echo $site_root ?>/wishlist.php">My Wishlist</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link active dropdown-toggle text-capitalize" id="navbarDropdown" role="button"
              data-bs-toggle="dropdown">
              <?php echo $_SESSION['username'] ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-center">
              <!-- <li><a class="dropdown-item" href="<?php echo $site_root ?>/my-account.php">Account Settings</a></li> -->

              <!-- Only show the ACP link if the user is an admin -->
              <?php if ($is_admin) : ?>
              <li><a class="dropdown-item" href="<?php echo $site_root ?>/admin">Admin Panel</a></li>
              <?php endif; ?>

              <!-- <li>
                <hr class="dropdown-divider">
              </li> -->
              <li><a class="dropdown-item text-danger" href="<?php echo $site_root ?>/logout.php">Logout</a></li>
            </ul>
          </li>

          <!-- User is NOT logged in -->
          <?php else : ?>

          <li class="w-100 me-2 mb-2 mb-lg-0 mb-lg-0"><a class="btn btn-outline-primary rounded-pill w-100"
              href="<?php echo $site_root ?>/login.php" title="Login" id="loginButton">Login</a>
          </li>
          <li class="w-100"><a class="btn btn-primary rounded-pill w-100" href="<?php echo $site_root ?>/register.php"
              title="Register">Register</a></li>

          <?php endif; ?>
          <!-- End of $logged_in -->

        </ul>

      </div>
    </div>
  </nav>
  <div id="content" class="bg-white">