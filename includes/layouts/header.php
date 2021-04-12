<?php
require_once(__DIR__ . '/../config.php')
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo $site_root ?>/">
        <img src="<?php echo $site_root ?>/static/img/logos/logo.svg" alt="Logo" height="45px"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo $site_root ?>/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $site_root ?>/wishlist.php">My Wishlist</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              myUsername
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?php echo $site_root ?>/my-account.php">Account Settings</a></li>
              <li><a class="dropdown-item" href="<?php echo $site_root ?>/admin">Admin Panel</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="<?php echo $site_root ?>/logout.php">Logout</a></li>
            </ul>
          </li>
          <li><a class="btn btn-outline-primary rounded-pill me-2" href="<?php echo $site_root ?>/login.php">Login</a>
          </li>
          <li><a class="btn btn-primary rounded-pill" href="<?php echo $site_root ?>/register.php">Register</a></li>
        </ul>

      </div>
    </div>
  </nav>