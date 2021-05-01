<div class="container-fluid bg-dark text-white py-3">
  <div class="container mt-3">
    <div class="row text-center">
      <div class="col-md-6 text-md-start">
        <img src="<?php echo $site_root ?>/static/img/logos/logo-inverted.svg" alt="Key Homes Logo" height="75px">
      </div>
      <div class="col-md-6 text-md-end">
        <a href="https://visioncollege.ac.nz/" class="d-inline-block btn-primary p-3 text-white rounded-circle me-1"
          target="_blank">
          <i class="fab fa-facebook fs-3"></i></a>
        <a href="https://visioncollege.ac.nz/" class="d-inline-block btn-primary p-3 text-white rounded-circle"
          target="_blank">
          <i class="fab fa-instagram fs-3"></i>
        </a>

      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <h5>ABOUT</h5>
        <p>Founded in the late 2020s by two passionate property developers, Key Homes aims to curate for-sale homes
          throughout New Zealand and connect you with life changing oppurtinities at affordable prices.</p>
      </div>
      <div class="col-sm-6 col-md-3 offset-md-6">
        <h5>CONTACT INFO</h5>
        <p>Toll Free: 0800 123 456<br>Landline: 07 123 456<br>Email: keyhomes@email.com</p>
        <p>123 Realestate Ave,<br>Housington, 5134<br>Monday - Friday: 9:00am - 5:00pm</p>
      </div>
      <div class="col-sm-6 col-md-4"></div>
      <div class="col-sm-6 col-md-4"></div>
    </div>
    <div class="text-center text-muted my-3">&copy; Key Homes <?php echo date("Y") ?>, Website by Chris Becker & Ben
      Mitchell</div>
  </div>
</div>
</div> <!-- end header #content -->

<script src="<?php echo $site_root ?>/static/js/popper.min.js"></script>
<script>
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>

</body>

</html>