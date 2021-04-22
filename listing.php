<?php

$title = "Listing Name"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header

//Checks whether an ID route query parameter has been provided
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {

  require_once('./includes/db.php'); //Connect to the database

  $property_id = trim($_GET['id']);

  $sql = "SELECT * FROM property JOIN agent ON agent.agent_ID = property.agent_ID WHERE property_ID = :property_id";

  if ($stmt = $pdo->prepare($sql)) {

    $stmt->bindParam(":property_id", $property_id);

    if ($stmt->execute()) {

      //Check to make sure exactly one row was returned
      if ($stmt->rowCount() == 1) {

        //Get the row as an associative Array
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);
      } else {

        //ID param in URL is invalid
        header('location: 404.php');
        exit();
      }
    }
  }

  //Close the statement
  unset($stmt);
} else {

  //URL doesn't contain a id param
  header('location: 404.php');
  exit();
}
?>

<div class="content-top-padding bg-light">
  <div class="container py-3">
    <div class="row">
      <div class="col-md-8 col-lg-6 offset-md-2 offset-lg-3">

        <?php //Get the Gallery from the Database
        $sql = "SELECT * FROM gallery WHERE property_ID = :property_id";

        $gallery = [];

        if ($stmt = $pdo->prepare($sql)) {
          $stmt->bindParam(":property_id", $property_id);


          if ($stmt->execute()) {
            while ($image = $stmt->fetch()) {
              array_push($gallery, $image);
            }
          }
        } ?>

        <!-- Gallery -->
        <div id="gallery" class="carousel slide carousel-fade mb-2" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php
            if (count($gallery) > 0) :
              foreach ($gallery as $key => $image) :
            ?>
            <div class="carousel-item <?php if ($key == 0) echo 'active'; ?>">
              <img src="<?php echo $image['image'] ?>" class="d-block w-100 rounded" alt="...">
            </div>
            <?php
              endforeach;
            endif;
            ?>
          </div>
        </div>

        <!-- Gallery Previews -->
        <div class="row justify-content-center">
          <?php
          if (count($gallery) > 0) :
            foreach ($gallery as $key => $image) :
          ?>
          <div class="col-auto">
            <img src="<?php echo $image['image'] ?>" class="carousel-thumbnail rounded"
              data-slide-to="<?php echo $key ?>" width="100" alt="...">
          </div>
          <?php
            endforeach;
          endif;
          ?>

        </div>

      </div>
    </div>
  </div>
</div>
<div class="container py-3">
  <div class="row">
    <div class="col-md-8">
      <h1 class="fw-bold mb-1">Home Away from home!</h1>
      <!-- <h2 class="h4 mb-2">145 Ohaupo Road, Glenview, Hamilton</h2> -->
      <h2 class="h4 mb-2">
        <?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']} {$listing['postcode']}" ?></h2>
      <p class="fs-3 fw-bold mb-2">Offers Over $<?php echo $listing['price'] ?></p>
      <div class="mb-4">
        <div class="badge feature rounded-pill me-1"><?php echo $listing['bedrooms'] ?> Bedrooms</div>
        <div class="badge feature rounded-pill me-1"><?php echo $listing['bathrooms'] ?> Bathrooms</div>
        <div class="badge feature rounded-pill me-1"><?php echo $listing['garage'] ?> Garages</div>
      </div>

      <p><?php echo $listing['description'] ?></p>

      <!-- Map might go here -->
      <div class="bg-info rounded" style="height: 400px">

      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header text-center fs-3">
          <?php echo $listing['fname'] . ' ' . $listing['lname'] ?>
        </div>
        <div class="card-body">
          <p class="mb-1">Email: <a href="mailto:<?php echo $listing['email'] ?>"><?php echo $listing['email'] ?></a>
          </p>
          <p class="mb-1">Phone: <a href="tel:<?php echo $listing['phone'] ?>"><?php echo $listing['phone'] ?></a></p>
          <p class="mb-4">Mobile: <a href="tel:<?php echo $listing['mobile'] ?>"><?php echo $listing['mobile'] ?></a>
          </p>
          <button class="btn btn-primary w-100">Contact</button>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
//Get a reference to the gallery and initialise its settings
var gallery = document.getElementById('gallery');
var bsGallery = new bootstrap.Carousel(gallery, {});

//Get a reference to the thumbnails
var galleryThumbnails = document.getElementsByClassName('carousel-thumbnail');

//Give each thumbnail a click listener to change the positon of the carousel
for (let i = 0; i < galleryThumbnails.length; i++) {
  galleryThumbnails[i].addEventListener('click', () => {
    bsGallery.to(galleryThumbnails[i].dataset.slideTo);
  })
}
</script>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>