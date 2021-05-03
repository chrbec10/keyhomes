<!-- Get the values to use in the filter options -->
<?php
$sql = "SELECT JSON_ARRAYAGG(DISTINCT city) AS cities FROM property";
if ($result = $pdo->query($sql)) {
  $filters =  $result->fetch(PDO::FETCH_ASSOC);
}

$cities = json_decode($filters['cities']);

?>

<form method="GET">
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