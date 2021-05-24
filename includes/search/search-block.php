<div class="row">
  <div class="col-lg-4 col-xl-3 mb-3 mb-lg-0 order-lg-2">
    <div class="card card-body">

      <?php require('filter.php') ?>

    </div>
    <div class="d-none d-lg-block">
      <a href="https://visioncollege.ac.nz/" class="text-decoration-none" target="_blank" rel="noreferrer noopener">
        <img src="/static/img/info/<?php echo rand(1, 3) ?>.png" class="mt-3 shadow border border-secondary"
          alt="Advert">
        <p class="text-muted text-end">Adverts by We Sold A Thing Ltd</p>
      </a>
    </div>
  </div>

  <div class="col-lg-8 col-xl-9 order-lg-1">
    <div class="card card-body py-4">
      <div class="container-fluid">

        <?php require('results.php') ?>


      </div>
    </div>
  </div>
</div>