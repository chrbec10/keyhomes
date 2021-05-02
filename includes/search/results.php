 <?php
  //Prepare the main select statement
  $sql = "
            SELECT property.property_ID, streetNum, street, city, postcode, saleType, price, description, bedrooms, bathrooms, garage, image, wishlist.user_ID FROM property LEFT JOIN (
              SELECT * FROM gallery WHERE gallery.image_ID IN (
                SELECT min(gallery.image_ID) from gallery GROUP BY gallery.property_ID 
              )
            )
            AS first_gallery_image ON property.property_ID = first_gallery_image.property_ID
            LEFT JOIN (SELECT * FROM wishlist WHERE user_ID = 3) AS wishlist ON wishlist.property_ID = property.property_ID
            ";

  //Init arrays to store conditions and parameters for the route query
  // $conditions = [];
  // $parameters = [];

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

  if (!empty($_GET['minPrice'])) {
    $conditions[] = 'price > ?';
    $parameters[] = $_GET['minPrice'];
  }

  if (!empty($_GET['maxPrice'])) {
    $conditions[] = 'price < ?';
    $parameters[] = $_GET['maxPrice'];
  }


  //Add the where conditions to the statement
  if ($conditions) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
  }

  $limit = 4;
  $sql .= ' ORDER BY property.property_ID ASC';
  $sql .= ' LIMIT ' . ($limit + 1);

  //Attempt to execute the statement
  if ($stmt = $pdo->prepare($sql)) {

    if ($stmt->execute($parameters)) :
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ?>

 <nav aria-label="Page navigation">
   <ul class="pagination justify-content-center">
     <li class="page-item <?php echo ($_GET['start'] <= 0) ? 'disabled' : '' ?>">
       <a class="page-link"
         href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'start', max(0, (int) $_GET['start'] - $limit - 1)); ?>">Previous</a>
     </li>
     <li class="page-item <?php echo (count($results) < $limit + 1) ? 'disabled' : '' ?>">
       <a class="page-link"
         href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'start', (int) $_GET['start'] + $limit + 1); ?>">Next</a>
     </li>
   </ul>
 </nav>

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
       <div class="col fs-5"><a
           href="/listing.php?id=<?php echo $listing['property_ID'] ?>"><?php echo "{$listing['streetNum']} {$listing['street']}, {$listing['city']} {$listing['postcode']}" ?></a>
       </div>
       <div class="col-auto">

         <!-- Show the correct wishlist button per listing -->
         <?php if ($_SESSION['loggedin']) : ?>
         <i class="fs-5 wishlistButton <?php echo ($listing['user_ID'] != null) ? 'wishlisted fas fa-star' : 'far fa-star' ?>"
           data-bs-toggle="tooltip" data-bs-placement="left" data-kh-listing-id="<?php echo $listing['property_ID'] ?>"
           title="Add to Wishlist"></i>
         <?php else : ?>
         <a href="/login.php" class="text-dark">
           <i class="fs-5 wishlistButton far fa-star" data-bs-toggle="tooltip" data-bs-placement="left"
             title="Wishlist (Requires Login)"> </i>
         </a>
         <?php endif; ?>
       </div>
     </div>
     <p class="mb-0 fs-5 fw-bold">
       <?php switch ($listing['saleType']) {
                case 'Sale':
                  echo "Sale";
                  if ($listing['price'] > 0) {
                    echo ' $' . number_format($listing['price']);
                  }
                  break;
                case 'Auction':
                  echo "Auction";
                  if ($listing['price'] > 0) {
                    echo ', Reserve $' . number_format($listing['price']);
                  }
                  break;
              } ?>
     </p>
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
 <nav aria-label="Page navigation">
   <ul class="pagination justify-content-center">
     <li class="page-item <?php echo ($_GET['start'] <= 0) ? 'disabled' : '' ?>">
       <a class="page-link"
         href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'start', max(0, (int) $_GET['start'] - $limit - 1)); ?>">Previous</a>
     </li>
     <li class="page-item <?php echo (count($results) < $limit) ? 'disabled' : '' ?>">
       <a class="page-link"
         href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'start', (int) $_GET['start'] + $limit + 1); ?>">Next</a>
     </li>
   </ul>
 </nav>