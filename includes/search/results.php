 <?php
  //Prepare the main select statement
  $sql = "
  SELECT property.property_ID, streetNum, street, city, postcode, saleType, price, description, bedrooms, bathrooms, garage, image, wishlist.user_ID FROM property LEFT JOIN (
    SELECT * FROM gallery WHERE gallery.image_ID IN (
      SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID 
      )
      )
      AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID
      LEFT JOIN (SELECT * FROM wishlist WHERE user_ID = ?) AS wishlist ON wishlist.property_ID = property.property_ID
      ";

  if (!isset($conditions)) {
    $conditions = [];
  }
  if (!isset($parameters)) {
    $parameters = [];
  }

  //Add userid as first param for wishlist search
  array_unshift($parameters, $_SESSION['id'] ?? null);

  //Offsets the results by a id (for pagination)
  if (!empty($_GET['start'])) {
    $conditions[] = 'property.property_ID >= ?';
    $parameters[] = $_GET['start'];
  }

  //Add city to query
  if (!empty($_GET['city'])) {
    $conditions[] = 'city = ?';
    $parameters[] = $_GET['city'];
  }

  //Add min price to query
  if (!empty($_GET['minPrice'])) {
    $conditions[] = 'price > ?';
    $parameters[] = $_GET['minPrice'];
  }

  //Add max price to query
  if (!empty($_GET['maxPrice'])) {
    $conditions[] = 'price < ?';
    $parameters[] = $_GET['maxPrice'];
  }

  //Add Min Bedrooms to Query
  if (!empty($_GET['bedrooms'])) {
    $conditions[] = 'bedrooms >= ?';
    $parameters[] = $_GET['bedrooms'];
  }

  //Add the where conditions to the statement
  if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
  }

  //Max amount of results that should be displayed
  $limit = 3;
  $sql .= ' ORDER BY property.property_ID ASC';
  $sql .= ' LIMIT ' . ($limit + 1);

  //Attempt to execute the statement
  if ($stmt = $pdo->prepare($sql)) {

    if ($stmt->execute($parameters)) :
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($results) > 0) :

        $next_id = $results[$limit]['property_ID'] ?? false;

        require('pagination.php');

  ?>

 <div id="results" class="my-4">

   <?php
          //Loop over the results
          for ($i = 0; $i < count($results); $i++) :
            if ($i >= $limit) {
              break;
            }
            $listing = $results[$i];
          ?>

   <div class="row mb-3">
     <div class="col-md-4">
       <div class="ratio-4-3 mb-3 mb-md-0">
         <a href="/listing.php?id=<?php echo $listing['property_ID'] ?>"
           title="View <?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']} {$listing['postcode']}" ?>">
           <div class="ratio-content rounded"
             style="background-image: url('<?php echo $listing['image'] ?? '/static/img/no-image.png' ?>'); background-size: cover; background-position: center;">
           </div>
         </a>
       </div>
     </div>
     <div class="col-md-8">
       <div class="row">
         <div class="col fs-5"><a
             href="/listing.php?id=<?php echo $listing['property_ID'] ?>"><?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']} {$listing['postcode']}" ?></a>
         </div>
         <div class="col-auto">

           <!-- Show the correct wishlist button per listing -->
           <?php if ($_SESSION['loggedin']) : ?>
           <i class="fs-5 wishlistButton <?php echo ($listing['user_ID'] != null) ? 'wishlisted fas fa-star' : 'far fa-star' ?>"
             data-bs-toggle="tooltip" data-bs-placement="left"
             data-kh-listing-id="<?php echo $listing['property_ID'] ?>" title="Add to Wishlist"></i>
           <?php else : ?>
           <a href="/login.php" class="text-dark" title="Login to Wishlist">
             <i class="fs-5 wishlistButton far fa-star" data-bs-toggle="tooltip" data-bs-placement="left"
               title="Wishlist (Requires Login)"> </i>
           </a>
           <?php endif; ?>
         </div>
       </div>
       <p class="mb-0 fs-5 fw-bold">
         <?php
                  require_once(__DIR__ . '/../functions/format_price_text.php');
                  echo format_price_text($listing['saleType'], $listing['price']);
                  ?>
       </p>
       <p>
         <?php
                  //Trims the description if it's too long
                  echo (strlen($listing['description']) > 150) ? trim(substr($listing['description'], 0, 150)) . '...' : $listing['description'];
                  ?>
       </p>
       <p>
         <span class="me-2">
           <i class="fas fa-bed text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom"
             title="Bedrooms"></i> <?php echo $listing['bedrooms'] ?>
         </span>
         <span class="me-2">
           <i class="fas fa-bath text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom"
             title="Bathrooms"></i> <?php echo $listing['bathrooms'] ?>
         </span>
         <span>
           <i class="fas fa-warehouse text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom"
             title="Garages"></i>
           <?php echo $listing['garage'] ?>
         </span>
       </p>
     </div>
   </div>

   <?php
            //Add a HR if this is not the last result to display
            if (count($results) <= $limit) {
              if ($i < count($results) - 1) {
                echo '<hr>';
              }
            } else {
              if ($i < count($results) - 2) {
                echo '<hr>';
              }
            }
          endfor;

          ?>
 </div> <!-- #results -->

 <?php
        require('pagination.php');

      else : //If there's no results
      ?>

 <p class="text-center text-muted">No Results</p>

 <?php
      endif; // Count($results) > 0
    endif; // $stmt->execute()
  }
  ?>

 <script>
/* Makes all of the wishlist buttons interactive */

var wishlistButtons = document.getElementsByClassName('wishlistButton');

for (button of wishlistButtons) {
  button.addEventListener('click', (e) => {

    xhr = new XMLHttpRequest();
    xhr.open("POST", '/services/wishlist-service.php');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.addEventListener('readystatechange', () => {

      if (xhr.readyState == 4 && xhr.status == 200) {

        var res = JSON.parse(xhr.responseText);

        if (res.wishlisted == "true") {
          e.target.classList.add('wishlisted', 'fas');
          e.target.classList.remove('far');
        } else {
          e.target.classList.remove('wishlisted', 'fas')
          e.target.classList.add('far');
        }
      }

    })

    xhr.send(`propertyid=${e.target.dataset.khListingId}`);

  })
}
 </script>