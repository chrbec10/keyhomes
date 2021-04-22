<?php

$title = "Listings in Hamilton"; //The Page Title
require_once('./includes/layouts/header.php'); //Gets the header
require_once('./includes/db.php'); //Connect to the database

?>

<div class="content-top-padding pb-4 bg-light">
  <div class="container mt-4">
    <h1>All Listings</h1>
    <div class="row">
      <div class="col-md-8 col-lg-9">
        <div class="card card-body py-4">
          <div class="container-fluid">

            <?php
            $sql = 'SELECT * FROM property JOIN (
                      SELECT * FROM gallery WHERE gallery.image_ID IN (
                        SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID
                         )
                      ) 
                     AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID';

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
                      style="background-image: url('<?php echo $listing['image'] ?>'); background-size: cover; background-position: center;">
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col h5"><a href="/listing.php?id=<?php echo $listing['property_ID'] ?>">Property Title
                      <?php echo $i + 1 ?></a>
                  </div>
                  <div class="col-auto"><a href="#" data-bs-toggle="tooltip" data-bs-placement="left"
                      title="Add to Wishlist">+Wishlist</a></div>
                </div>
                <p><?php echo $listing['description'] ?></p>
                <p>3 Bdrm 1 Bthrm</p>
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

<?php
require_once('./includes/layouts/footer.php'); //Gets the footer
?>