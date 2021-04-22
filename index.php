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
  <div class="overlay-container d-flex flex-column align-items-center justify-content-center">
    <div class="d-block">
      <h1 class="text-white display-3 fw-normal" style="text-shadow: 2px 2px 3px #000000a8;">Find Your Perfect Home</h1>
      <div class="card bg-dark p-3">
        <div class="row">
          <div class="col">
            <input type="text" class="form-control rounded-pill d-inline" placeholder="Search">
          </div>
          <div class="col-auto">
            <button class="btn btn-primary rounded-pill d-inline">Search</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Latest Listings -->
<div class=" container-fluid bg-light py-5">
  <div class="container">
    <div class="text-center">
      <h2>Latest Listings</h2>
      <p>Search over 200 of the Top Properties in NZ</p>
    </div>
    <div class="row justify-content-center">

      <?php
      //Get the Latest Listings
      $sql = "
      SELECT property.property_ID, bedrooms, bathrooms, garage, image FROM property JOIN (
        SELECT * FROM gallery WHERE gallery.image_ID IN (
          SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID
        )
      ) 
      AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID ORDER BY property.property_ID DESC LIMIT 3";

      if ($result = $pdo->query($sql)) :
        // $results = $stmt->fetch(PDO::FETCH_ASSOC);
        while ($row = $result->fetch()) :
      ?>

      <div class="col-sm-6 col-md-3 mb-4">
        <div class="card">
          <img src="<?php echo $row['image'] ?>" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Home Away From Home</h5>
            <p class="card-text text-muted">Bdrm <?php echo $row['bedrooms'] ?> Bthrm <?php echo $row['bathrooms'] ?>
            </p>
            <div class="d-grid">
              <a href="listing.php?id=<?php echo $row['property_ID'] ?>" class="btn btn-secondary rounded-pill">View</a>
            </div>
          </div>
        </div>
      </div>


      <?php
        endwhile;
      endif; ?>

    </div>
    <div class="text-center">
      <a href="listings.php" class="btn btn-primary rounded-pill">View All Listings</a>
    </div>
  </div>
</div>

<!-- Why choose us -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4 col-lg-6 d-none d-md-block" style="background-image: url('static/img/comfy-interior.jpg'); background-position: center 37%;
    background-size: cover;"></div>
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

<!-- Our Agents -->
<div class="container-fluid bg-light py-5">
  <div class="container text-center">
    <h2>Our Agents</h2>
    <p>Meet our Team of Professional Agents</p>
    <div class="row justify-content-center">
      <?php for ($i = 0; $i < 3; $i++) : ?>
      <div class="col-auto mb-4">
        <div class="rounded-circle bg-secondary mb-2" style="width: 250px; height: 250px"></div>
        <span class="fs-5"><b>John Doe</b></span>
        <br>
        <span class="text-muted">Knows how to sell houses?</span>
      </div>
      <?php endfor; ?>
    </div>
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