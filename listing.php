<?php

$title = "Listing Name"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<div class="content-top-padding bg-light">
  <div class="container py-3">
    <div class="row">
      <div class="col-md-8 col-lg-6 offset-md-2 offset-lg-3">

        <!-- Gallery -->
        <div id="gallery" class="carousel slide carousel-fade mb-2" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="/uploads/properties/test/1_0.jpg" class="d-block w-100 rounded" alt="...">
            </div>
            <div class="carousel-item">
              <img src="/uploads/properties/test/1_1.jpg" class="d-block w-100 rounded" alt="...">
            </div>
            <div class="carousel-item">
              <img src="/uploads/properties/test/1_2.jpg" class="d-block w-100 rounded" alt="...">
            </div>
          </div>
        </div>

        <!-- Gallery Previews -->
        <div class="row justify-content-center">
          <div class="col-auto">
            <img src="/uploads/properties/test/1_0.jpg" class="carousel-thumbnail rounded" data-slide-to="0" width="100"
              alt="...">
          </div>
          <div class="col-auto">
            <img src="/uploads/properties/test/1_1.jpg" class="carousel-thumbnail rounded" data-slide-to="1" width="100"
              alt="...">
          </div>
          <div class="col-auto">
            <img src="/uploads/properties/test/1_2.jpg" class="carousel-thumbnail rounded" data-slide-to="2" width="100"
              alt="...">
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<div class="container py-3">
  <div class="row">
    <div class="col-md-8">
      <h1 class="fw-bold mb-1">Home Away from home!</h1>
      <h2 class="h4 mb-2">145 Ohaupo Road, Glenview, Hamilton</h2>
      <p class="fs-3 fw-bold mb-2">Offers Over $377,000</p>
      <div class="mb-4">
        <div class="badge feature rounded-pill me-1">3 Bedrooms</div>
        <div class="badge feature rounded-pill me-1">1 Bathrooms</div>
        <div class="badge feature rounded-pill me-1">1 Garages</div>
      </div>

      <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos officia tenetur modi, doloremque exercitationem
        laudantium consequatur fuga sint maxime a perferendis facilis earum repellat incidunt eos accusantium
        voluptatem? Nisi, aliquam?</p>

      <!-- Map might go here -->
      <div class="bg-info rounded" style="height: 400px">

      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header text-center fs-3">
          Agent Name
        </div>
        <div class="card-body">
          <p>Agent Info</p>
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