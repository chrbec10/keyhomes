<?php

$title = "Home"; //The Page Title
$home_nav = true; //Makes the nav transparent by default

require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<!-- Call to Action -->
<div class="container-fluid bg-dark vh-75 has-overlay" style="background-image: url('./uploads/properties/test/3_0.jpg'); background-position: center;
    background-size: cover; background-attachment: fixed;">
  <div class="dark-overlay"></div>

</div>

<!-- Latest Listings -->
<div class=" container-fluid bg-light py-3">
  <div class="container text-center">
    <h2>Latest Listings</h2>
    <p>Search over 200 of the Top Properties in NZ</p>
    <button class="btn btn-primary rounded-pill">View All Listings</button>
  </div>
</div>

<!-- Why choose us -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4 col-lg-6 d-none d-md-block bg-warning">Some image</div>
    <div class="col-md-8 col-lg-6 px-4 py-3">
      <h2>Why Choose Us?</h2>
    </div>
  </div>
</div>

<!-- Latest Listings -->
<div class="container-fluid bg-light py-3">
  <div class="container text-center">
    <h2>Our Agents</h2>
    <p>Meet our Team of Professional Agents</p>
  </div>
</div>

<!-- Change Navbar BG on scroll -->
<script>
var navbar = document.getElementById('mainNav');

function updateNavBG() {
  let yPos = window.scrollY;

  if (yPos < 5) {
    navbar.classList.add('transparent');
    return;
  }
  navbar.classList.remove('transparent');
}

document.addEventListener("scroll", () => {
  updateNavBG();
})

updateNavBG();
</script>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>