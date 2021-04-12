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