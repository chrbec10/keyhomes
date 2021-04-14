<?php

$title = "Listings in Hamilton"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<div class="content-top-padding pb-4 bg-light">
  <div class="container mt-4">
    <h1>Results for Homes in Hamilton</h1>
    <div class="row">
      <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
        <div class="card card-body">

          <form>
            <label for="city" class="form-label mb-1">City:</label>
            <select class="form-select mb-2" id="city">
              <option selected>All of NZ</option>
              <option value="Hamilton">Hamilton</option>
              <option value="Auckland">Auckland</option>
              <option value="Wellington">Wellington</option>
            </select>

            <label for="suburb" class="form-label mb-1">Suburb:</label>
            <select class="form-select mb-2" id="suburb">
              <option selected>Any Suburb</option>
              <option value="Fairview Downs">Fairview Downs</option>
              <option value="Huntington">Huntington</option>
              <option value="Hillcrest">Hillcrest</option>
            </select>

            <div class="mb-1">Price Range:</div>
            <div class="row mb-1">
              <div class="col-md-6">
                <input type="text" class="form-control" id="minPrice" placeholder="$ Min">
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" id="maxPrice" placeholder="$ Max">
              </div>
            </div>
            <input type="range" class="form-range" id="minMaxRange">
            <button class="btn btn-primary mt-2 rounded-pill w-100">Filter</button>
          </form>

        </div>
      </div>
      <div class="col-md-8 col-lg-9">
        <div class="card card-body py-4">
          <div class="container-fluid">

            <?php $iterations = 4;
            for ($i = 0; $i < $iterations; $i++) : ?>
            <div class="row mb-3">
              <div class="col-md-3 bg-secondary rounded"></div>
              <div class="col-md-9">
                <div class="row">
                  <div class="col h5">Property Title</div>
                  <div class="col-auto"><a href="#" data-bs-toggle="tooltip" data-bs-placement="left"
                      title="Add to Wishlist">+Wishlist</a></div>
                </div>
                <p>(Excerpt on the Propert)... Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit enim et
                  tempora vel, ratione quo rerum totam obcaecati fuga facere quaerat accusantium repudiandae quae
                  sapiente
                  nihil! Facilis possimus asperiores eos.</p>
                <p>3 Bdrm 1 Bthrm</p>
              </div>
            </div>
            <?php
              if ($i < $iterations - 1) {
                echo '<hr>';
              }
              ?>
            <?php endfor; ?>
          </div>

          <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <a class="page-link" tabindex="-1">Previous</a>
              </li>
              <li class="page-item"><a class="page-link" href="listings.php?page=1">1</a></li>
              <li class="page-item"><a class="page-link" href="listings.php?page=2">2</a></li>
              <li class="page-item"><a class="page-link" href="listings.php?page=3">3</a></li>
              <li class="page-item">
                <a class="page-link" href="listings.php?page=2">Next</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>