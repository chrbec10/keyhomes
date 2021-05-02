<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center">
    <li class="page-item <?php echo ($_GET['start'] <= 0) ? 'disabled' : '' ?>">
      <a class="page-link"
        href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'start', max(0, (int) $_GET['start'] - $limit - 1)); ?>">Previous</a>
    </li>
    <li class="page-item <?php echo (!$next_id) ? 'disabled' : '' ?>">
      <a class="page-link"
        href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'start', $next_id); ?>">Next</a>
    </li>
  </ul>
</nav>