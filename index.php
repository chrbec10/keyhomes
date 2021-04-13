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
<div class=" container-fluid bg-light py-5">
  <div class="container">
    <div class="text-center">
      <h2>Latest Listings</h2>
      <p>Search over 200 of the Top Properties in NZ</p>
    </div>
    <div class="row mb-4">

      <?php for ($i = 0; $i < 4; $i++) : ?>

      <div class="col-sm-6 col-md-3">
        <div class="card">
          <img src="uploads/properties/test/0_2.jpg" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Home Away From Home</h5>
            <p class="card-text text-muted">Bdrm 1 Bthrm 1</p>
            <div class="d-grid">
              <a href="#" class="btn btn-secondary rounded-pill">View</a>
            </div>
          </div>
        </div>
      </div>


      <?php endfor; ?>

    </div>
    <div class="text-center">
      <button class="btn btn-primary rounded-pill">View All Listings</button>
    </div>
  </div>
</div>

<!-- Why choose us -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4 col-lg-6 d-none d-md-block bg-warning">Some image</div>
    <div class="col-md-8 col-lg-6 p-5">
      <div class="container p-5">
        <h2>Why Choose Us?</h2>
        <p>We make it our mission to bring all the best properties in New Zealand to your fingertips.</p>
        <h3>Wide Range of Properties</h3>
        <p>From Private sellers and Professional sellers alike.</p>
        <h3>Best Pricing Garunteed</h3>
        <p>Our Agents go to great extremes to negotiate the best pricing so that you can get the perfect home for the
          perfect price.</p>
        <h3>Trusted By Thousands</h3>
        <p>Our Customers just love us. Like Really! We work our absolute hardest to ensure that each and every customer
          gets what they're looking for.</p>
      </div>
    </div>
  </div>
</div>

<!-- Latest Listings -->
<div class="container-fluid bg-light py-5">
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