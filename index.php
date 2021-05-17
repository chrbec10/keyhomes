<?php

$title = "Home"; //The Page Title
$home_nav = true; //Makes the nav transparent by default

require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database

//Get the Random Listing for the CTA
$sql = "
      SELECT property.property_ID, streetNum, street, city, image FROM property JOIN (
        SELECT * FROM gallery WHERE gallery.image_ID IN (
          SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID
        )
      ) 
      AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID ORDER BY RAND() LIMIT 1";

if ($result = $pdo->query($sql)) {
  $random_featured =  $result->fetch(PDO::FETCH_ASSOC);
}
?>

<!-- Call to Action -->
<div class="container-fluid bg-dark vh-75 has-overlay" style="background-image: url('<?php echo 'uploads/properties/' . $random_featured['image'] ?>'); background-position: center;
    background-size: cover;">
  <div class="dark-overlay"></div>
  <div class="overlay-container d-flex flex-column align-items-center justify-content-center">
    <div class="container d-block">
      <h1 class="text-white display-3 fw-normal text-center" style="text-shadow: 2px 2px 3px #000000a8;">
        Find Your Perfect Home</h1>
      <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9 col-xl-8 col-xxl-7">
          <div class="card bg-dark p-3 pt-2 text-center">



            <form action="listings.php" method="GET">

              <label for="city" class="form-label" style="color: #ffffffb8">Search for Properties in...</label>
              <div class="input-group rounded-pill">
                <select class="form-select" name="city" title="City" style="border-radius: 50rem 0 0 50rem">
                  <option selected value="">All of NZ</option>

                  <!-- Get the values to use in the filter options -->
                  <?php
                  $sql = "SELECT DISTINCT city FROM property";
                  if ($result = $pdo->query($sql)) {

                    while ($row = $result->fetch()) :

                  ?>
                  <option value="<?php echo $row['city'] ?>">
                    <?php echo $row['city'] ?></option>
                  <?php

                    endwhile;
                  }

                  ?>


                </select>
                <button class="btn btn-primary px-3 px-sm-4 px-md-5" type="submit"
                  style="border-radius: 0 50rem 50rem 0;">Search</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid position-absolute" style="bottom: 0;">
      <h1 class="text-uppercase fw-bold text-end">
        <a href="listing.php?id=<?php echo $random_featured['property_ID'] ?>" class="text-white text-decoration-none">
          <button class="btn btn-secondary btn-sm " style="margin-top: -5px;">View
            <?php echo "{$random_featured['streetNum']} {$random_featured['street']}, {$random_featured['city']}" ?>
          </button>
        </a>
      </h1>
    </div>
  </div>

</div>

<!-- Latest Listings -->
<div class="container-fluid bg-light py-5" id="latest-listings">
  <div class="container">
    <div class="text-center">
      <h2>Latest Listings</h2>

      <!-- Get the amount of listings in the database -->
      <?php
      $sql = "SELECT COUNT(*) FROM property";
      if ($result = $pdo->query($sql)) :
        $row =  $result->fetch();
      ?>
      <p>Search over <?php echo $row['0'] ?>+ of the Top Properties in NZ</p>
      <?php endif; ?>
    </div>
    <div class="row justify-content-center">

      <!-- Get the Latest Listings and their first added image. Then, loop through those listings -->
      <?php
      $sql = "
      SELECT property.property_ID, streetNum, street, city, bedrooms, bathrooms, garage, image FROM property JOIN (
        SELECT * FROM gallery WHERE gallery.image_ID IN (
          SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID
        )
      ) 
      AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID ORDER BY property.property_ID DESC LIMIT 3";

      if ($result = $pdo->query($sql)) :
        while ($row = $result->fetch()) :
      ?>
      <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
        <div class="card h-100">
          <div class="ratio-4-3 listing-thumbnail"
            style="background-image: url('<?php echo 'uploads/properties/med_' . $row['image'] ?>');">
          </div>
          <div class="card-body">
            <p class="card-title h5">
              <?php echo "{$row['streetNum']} {$row['street']}," ?>
              <br>
              <?php echo $row['city'] ?>
            </p>
            <p class="card-text text-muted">
              <span class="me-2">
                <i class="fas fa-bed text-secondary"></i> <?php echo $row['bedrooms'] ?>
              </span>
              <span class="me-2">
                <i class="fas fa-bath text-secondary"></i> <?php echo $row['bathrooms'] ?>
              </span>
              <span>
                <i class="fas fa-warehouse text-secondary"></i> <?php echo $row['garage'] ?>
              </span>
            </p>

            <a href="listing.php?id=<?php echo $row['property_ID'] ?>"
              class="btn btn-secondary rounded-pill w-100">View</a>

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
    <div class="col-md-4 col-lg-6 d-none d-md-block" style="background-image: url('static/img/family-moving.jpg'); background-position: center 55%;
    background-size: cover;"></div>
    <div class="col-md-8 col-lg-6 p-md-5">
      <div class="container py-5 p-sm-5">
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
<div class="container-fluid bg-light py-5" id="agents">
  <div class="container text-center">
    <h2>Our Agents</h2>
    <p>Meet our Team of Professional Agents</p>
    <div class="row justify-content-center">

      <?php
      $sql = "SELECT fname, lname, icon FROM agent";
      if ($results = $pdo->query($sql)) :
        while ($row = $results->fetch()) :
      ?>

      <div class="col-auto mb-4">
        <div class="rounded-circle bg-secondary mb-2 agent-icon"
          style="background-image: url('<?php echo 'uploads/agents/' . $row['icon'] ?>')">
        </div>
        <span class="fs-5"><b><?php echo $row['fname'] . " " . $row['lname'] ?></b></span>
        <br>
        <span class="text-muted">Dedicated Agent</span>
      </div>
      <?php
        endwhile;
      endif;
      ?>
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