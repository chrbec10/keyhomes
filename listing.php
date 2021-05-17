<?php


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

$title = "{$listing['streetNum']} {$listing['street']}, {$listing['city']}"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header

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

<!-- or the reference on CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/@splidejs/splide@2.4.21/dist/css/splide.min.css">
<link rel="stylesheet" href="/static/css/splide-keyhomes-theme.css">

<div class="content-top-padding bg-dark">
  <div class="container pt-3 pb-2">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-7">

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
        <div class="splide" id="main-slider">
          <div class="splide__track">
            <ul class="splide__list">
              <?php
              if (count($gallery) > 0) :
                foreach ($gallery as $key => $image) :
              ?>
              <li class="splide__slide"><img src="<?php echo "uploads/properties/med_" . $image['image'] ?>" class="w-100 rounded" alt=""></li>
              <?php
                endforeach;
              endif;
              ?>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="py-3" style="background-color: #2c2b36;">
    <div class="container">

      <!-- Gallery Previews -->
      <div class="splide" id="preview-slider">
        <div class="splide__track">
          <ul class="splide__list">
            <?php
            if (count($gallery) > 0) :
              foreach ($gallery as $key => $image) :
            ?>
            <li class="splide__slide rounded"><img src="<?php echo "uploads/properties/med_" . $image['image'] ?>" alt=""></li>
            <?php
              endforeach;
            endif;
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container py-3 mb-4">
  <div class="row">
    <div class="col-lg-8 col-xl-9 mb-4 mb-lg-0">
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
          <a href="login.php?redirectToListing=<?php echo $listing['property_ID'] ?>">
            <button class="btn btn-outline-secondary rounded-pill">
              Sign in to Wishlist
            </button>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <h2 class="h4 mb-2">
        <?php echo "{$listing['city']} {$listing['postcode']}" ?></h2>
      <p class="fs-3 fw-bold mb-2">
        <?php
        require('./includes/functions/format_price_text.php');
        echo format_price_text($listing['saleType'], $listing['price']);
        ?>
      </p>
      <div class="mb-2 mb-md-4">
        <div class="badge feature rounded-pill me-1 mb-1"><i
            class="fas fa-bed me-1"></i><?php echo $listing['bedrooms'] ?>
          Bedrooms</div>
        <div class="badge feature rounded-pill me-1 mb-1"><i
            class="fas fa-bath me-1"></i><?php echo $listing['bathrooms'] ?>
          Bathrooms</div>
        <div class="badge feature rounded-pill me-1 mb-1"><i
            class="fas fa-warehouse me-1"></i><?php echo $listing['garage'] ?>
          Garages</div>
      </div>

      <p class="mb-4"><?php echo $listing['description'] ?></p>

      <!-- Map might go here -->
      <iframe class="rounded bg-light" width="100%" height="400" style="border:0" loading="lazy" allowfullscreen
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFjY1014SSYdpCvVi18RBxu1apMGuTSEk&q=<?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']}" ?>">
      </iframe>
    </div>
    <div class="col-lg-4 col-xl-3">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-12">
          <div class="card shadow-sm">
            <div class="card-header text-center fs-3" style="line-height: 1.1;">
              <span class=" fs-6 text-muted">Listing Agent</span>
              <br>
              <?php echo $listing['fname'] . ' ' . $listing['lname'] ?>
            </div>

            <div class="bg-secondary py-3 text-center">
              <img src="<?php echo "uploads/agents/" . $listing['icon'] ?>" alt="Agent's image" class="rounded-circle my-1 shadow"
                style="max-width: 75%; height: auto;">
            </div>

            <hr class="mt-0">
            <div class="card-body text-center pt-0">
              <p class="mb-1">Email: <a
                  href="mailto:<?php echo $listing['email'] ?>"><?php echo $listing['email'] ?></a>
              </p>
              <p class="mb-1">Phone: <a href="tel:<?php echo $listing['phone'] ?>"><?php echo $listing['phone'] ?></a>
              </p>
              <p class="mb-4">Mobile: <a
                  href="tel:<?php echo $listing['mobile'] ?>"><?php echo $listing['mobile'] ?></a>
              </p>
              <a class="btn btn-primary w-100"
                href="mailto:<?php echo $listing['email'] ?>?subject=Inquiry: <?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']} {$listing['postcode']}" ?>">Make
                an Inquiry</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>

<script>
var previewSlider = new Splide('#preview-slider', {
  rewind: true,
  fixedWidth: 150,
  fixedHeight: 100,
  isNavigation: true,
  gap: 10,
  focus: 'center',
  pagination: false,
  cover: true,
  breakpoints: {
    '600': {
      fixedWidth: 125,
      fixedHeight: 75,
    }
  }
}).mount();

var primarySlider = new Splide('#main-slider', {
  type: 'fade',
  pagination: false,
  arrows: false,
}).mount()

primarySlider.sync(previewSlider).mount()
</script>

<script>
var wishlistButtons = document.getElementsByClassName('wishlistButton');

for (button of wishlistButtons) {
  button.addEventListener('click', (e) => {

    xhr = new XMLHttpRequest();
    xhr.open("POST", 'services/wishlist-service.php');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.addEventListener('readystatechange', () => {

      if (xhr.readyState == 4 && xhr.status == 200) {
        var res = JSON.parse(xhr.responseText);

        if (res.wishlisted == "true") {
          e.target.classList.add('btn-warning');
          e.target.classList.remove('btn-outline-secondary');
          e.target.innerHTML = 'Wishlisted';
        } else {
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