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

          <!-- Get the values to use in the filter options -->
          <?php
          $sql = "SELECT JSON_ARRAYAGG(DISTINCT city) AS cities FROM property";
          if ($result = $pdo->query($sql)) {
            $filters =  $result->fetch(PDO::FETCH_ASSOC);
          }

          $cities = json_decode($filters['cities']);

          ?>

          <form method="GET" action="listings.php">
            <label for="city" class="form-label mb-1">City:</label>
            <select class="form-select mb-2" name="city">
              <option <?php echo isset($_GET['city']) ? '' : 'selected' ?> value="">All of NZ</option>
              <?php foreach ($cities as $city) :
              ?>
              <option value="<?php echo $city ?>" <?php echo ($_GET['city'] == $city) ? 'selected' : '' ?>>
                <?php echo $city ?></option>
              <?php endforeach;
              ?>
            </select>

            <div class="mb-1">Price Range:</div>
            <div class="row mb-1">
              <div class="col">
                <select class="form-select" name="minPrice" id="minPrice">

                  <?php
                  $priceFilterValues = [
                    '', 100000, 150000, 200000, 250000, 300000, 350000, 400000, 450000, 500000, 550000, 600000, 650000, 700000, 750000, 800000, 850000, 900000, 950000, 1000000, 1100000, 1200000, 1300000, 1400000, 1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000, 2750000, 3000000, 3500000, 4000000, 5000000, 6000000, 7000000, 8000000, 9000000, 10000000
                  ];

                  foreach ($priceFilterValues as $option) :
                    $formatted = '$';
                    if ($option == '') {
                      $formatted = 'Any';
                    } else if ($option < 1000000) {
                      $formatted .= $option / 1000 . 'k';
                    } else {
                      $formatted .= $option / 1000000 . 'M';
                    }
                  ?>
                  <option value="<?php echo $option ?>" <?php echo ($option == $_GET['minPrice']) ? 'selected' : '' ?>>
                    <?php echo $formatted ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-auto px-0 pt-2">
                to
              </div>
              <div class="col">
                <select class="form-select" name="maxPrice" id="maxPrice">

                  <?php
                  foreach ($priceFilterValues as $option) :
                    $formatted = '$';
                    if ($option == '') {
                      $formatted = 'Any';
                    } else if ($option < 1000000) {
                      $formatted .= $option / 1000 . 'k';
                    } else {
                      $formatted .= $option / 1000000 . 'M';
                    }
                  ?>
                  <option value="<?php echo $option ?>" <?php echo ($option == $_GET['maxPrice']) ? 'selected' : '' ?>>
                    <?php echo $formatted ?></option>
                  <?php endforeach; ?>

                </select>
              </div>
            </div>

            <button class="btn btn-primary mt-2 rounded-pill w-100">Filter</button>
          </form>

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