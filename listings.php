<?php

$title = "Listings in Hamilton"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database

?>

<div class="content-top-padding pb-4 bg-light">
  <div class="container mt-4">
    <h1>
      <?php echo !empty($_GET['city']) ? "" : 'All ' ?>Listings<?php echo !empty($_GET['city']) ? " in {$_GET['city']}" : '' ?>
    </h1>
    <div class="row">
      <div class="col-md-8 col-lg-9">
        <div class="card card-body py-4">
          <div class="container-fluid">

            <?php
            function change_url_parameter($url, $parameter, $parameterValue)
            {
              $url = parse_url($url);
              parse_str($url["query"], $parameters);
              unset($parameters[$parameter]);
              $parameters[$parameter] = $parameterValue;
              return  $url["path"] . "?" . http_build_query($parameters);
            }

            require('./includes/search/results.php');

            ?>

          </div>

        </div>


      </div>
      <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
        <div class="card card-body">

          <?php require('./includes/search/filter.php') ?>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
var minPrice = document.getElementById('minPrice');
var maxPrice = document.getElementById('maxPrice');

maxPrice.addEventListener('input', () => {
  if (maxPrice.value < minPrice.value && maxPrice.value != '') {
    minPrice.value = maxPrice.value;
  }
})
minPrice.addEventListener('input', () => {
  if (minPrice.value > maxPrice.value && maxPrice.value != '') {
    maxPrice.value = minPrice.value;
  }
})
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
        console.log(xhr.responseText);
        var res = JSON.parse(xhr.responseText);
        console.log(res);

        console.log(e.target.classList);

        if (res.wishlisted == "true") {
          console.log('adding');
          e.target.classList.add('wishlisted', 'fas');
          e.target.classList.remove('far');
        } else {
          console.log('removing');
          e.target.classList.remove('wishlisted', 'fas')
          e.target.classList.add('far');
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