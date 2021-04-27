<?php
session_start();

//Redirect if the user is not logged in
if (!$_SESSION['loggedin']) {
  header('location: login.php');
  exit();
}

$title = "{$_SESSION['username']}'s Wishlist"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database
?>

<div class="content-top-padding pb-4 bg-light">
  <div class="container mt-4">
    <h1>My Wishlist</h1>
    <div class="row">
      <div class="col-md-8 col-lg-9">
        <div class="card card-body py-4">
          <div class="container-fluid">

            <?php
            $sql = "
            SELECT property.property_ID, streetNum, street, city, postcode, saleType, price, description, bedrooms, bathrooms, garage, image FROM wishlist
              JOIN property ON property.property_ID = wishlist.property_ID 
                LEFT JOIN (
                  SELECT * FROM gallery WHERE gallery.image_ID IN (
                  SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID 
                )
              )
            AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID
            WHERE wishlist.user_ID = 5
            ";

            if ($stmt = $pdo->prepare($sql)) {

              if ($stmt->execute()) :
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for ($i = 0; $i < count($results); $i++) :
                  $listing = $results[$i];
            ?>

            <div class="row mb-3">
              <div class="col-md-4">
                <div class="ratio-4-3">
                  <a href="/listing.php?id=<?php echo $listing['property_ID'] ?>">
                    <div class="ratio-content rounded"
                      style="background-image: url('<?php echo $listing['image'] ?? '/static/img/no-image.png' ?>'); background-size: cover; background-position: center;">
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col h5"><a
                      href="/listing.php?id=<?php echo $listing['property_ID'] ?>"><?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']} {$listing['postcode']}" ?></a>
                  </div>
                  <div class="col-auto">
                    <i class="fs-5 wishlistButton wishlisted fas fa-star" data-bs-toggle="tooltip"
                      data-bs-placement="left" data-kh-listing-id="<?php echo $listing['property_ID'] ?>"
                      title="Add to Wishlist"></i>
                  </div>
                </div>
                <p><?php echo $listing['description'] ?></p>
                <p>
                  <span class="me-2">
                    <i class="fas fa-bed text-secondary"></i> <?php echo $listing['bedrooms'] ?>
                  </span>
                  <span class="me-2">
                    <i class="fas fa-bath text-secondary"></i> <?php echo $listing['bathrooms'] ?>
                  </span>
                  <span>
                    <i class="fas fa-warehouse text-secondary"></i> <?php echo $listing['garage'] ?>
                  </span>
                </p>
              </div>
            </div>

            <?php
                  if ($i < count($results) - 1) {
                    echo '<hr>';
                  }
                endfor;
              endif;
            }
            ?>

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


          <form>
            <label for="city" class="form-label mb-1">City:</label>
            <select class="form-select mb-2" id="city">
              <option selected>All of NZ</option>
              <?php foreach ($cities as $city) :
              ?>
              <option value="<?php echo $city ?>"><?php echo $city ?></option>
              <?php endforeach;
              ?>
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
            <input type="range" class="form-range" id="minRange" min="0" step="10000" max="100000">
            <input type="range" class="form-range" id="maxRange" min="0" step="10000" max="100000">
            <button class="btn btn-primary mt-2 rounded-pill w-100">Filter</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

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