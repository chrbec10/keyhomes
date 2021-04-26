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

//See if this Listing is on the user's wishlist
$sql = "SELECT property_ID FROM wishlist WHERE user_ID = :user_id AND property_ID = :property_id";
if ($stmt = $pdo->prepare($sql)) {
  $stmt->bindParam(':user_id', $_SESSION['id']);
  $stmt->bindParam('property_id', $property_id);

  if ($stmt->execute()) {
    if ($stmt->rowCount() == 1) {
      $wishlisted = true;
    } else {
      $wishlisted = false;
    }
  }
}
?>

<div class="content-top-padding bg-light">
  <div class="container py-3">
    <div class="row">
      <div class="col-md-8 col-lg-7 offset-md-2 offset-lg-3">

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
            else :
              ?>
            <div class="carousel-item active d-flex justify-content-center">
              <img src="/static/img/no-image.png" class="d-block w-50 rounded" alt="no image">
            </div>
            <?php
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
      <div class="row">
        <div class="col">
          <h1 class="fw-bold mb-1"><?php echo "{$listing['streetNum']} {$listing['street']}" ?></h1>
        </div>
        <div class="col-auto">
          <?php if ($_SESSION['loggedin']) : ?>
          <button
            class="btn rounded-pill wishlistButton <?php echo $wishlisted ? 'btn-warning'  : 'btn-outline-secondary' ?>"
            data-kh-listing-id="<?php echo $listing['property_ID'] ?>">
            <?php echo $wishlisted ? 'Wishlisted' : '+ Wishlist' ?>
          </button>
          <?php else : ?>
          <a href="/login.php">
            <button class="btn btn-outline-secondary rounded-pill">
              Sign in to Wishlist
            </button>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <h2 class="h4 mb-2">
        <?php echo "{$listing['city']} {$listing['postcode']}" ?></h2>
      <p class="fs-3 fw-bold mb-2">Offers Over $<?php echo $listing['price'] ?></p>
      <div class="mb-4">
        <div class="badge feature rounded-pill me-1"><?php echo $listing['bedrooms'] ?> Bedrooms</div>
        <div class="badge feature rounded-pill me-1"><?php echo $listing['bathrooms'] ?> Bathrooms</div>
        <div class="badge feature rounded-pill me-1"><?php echo $listing['garage'] ?> Garages</div>
      </div>

      <p><?php echo $listing['description'] ?></p>

      <!-- Map might go here -->
      <iframe class="rounded bg-light" width="100%" height="400" style="border:0" loading="lazy" allowfullscreen
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFjY1014SSYdpCvVi18RBxu1apMGuTSEk&q=<?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']}" ?>">
      </iframe>
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

<script>
var wishlistButtons = document.getElementsByClassName('wishlistButton');

for (button of wishlistButtons) {
  button.addEventListener('click', (e) => {
    console.log(e.target.dataset.khListingId);

    xhr = new XMLHttpRequest();
    xhr.open("POST", '/services/wishlist-service.php', false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.addEventListener('readystatechange', () => {
      console.log('hi');

      if (xhr.readyState == 4 && xhr.status == 200) {
        var res = JSON.parse(xhr.responseText);
        console.log(res);

        console.log(e.target.classList);

        if (res.wishlisted == "true") {
          console.log('adding');
          e.target.classList.add('btn-warning');
          e.target.classList.remove('btn-outline-secondary');
          e.target.innerHTML = 'Wishlisted';
        } else {
          console.log('removing');
          e.target.classList.add('btn-outline-secondary');
          e.target.classList.remove('btn-warning');
          e.target.innerHTML = '+ Wishlist';
        }
      }

    })

    xhr.send(`propertyid=${e.target.dataset.khListingId}`);
  })
}
</script>



<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>