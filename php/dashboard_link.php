<?php if (is_user_logged_in()) : ?>
  <div class="dashboard-link ">
    <a href="<?= HOME . '/dashboard' ?>" class="btn icon-only special">
      <div class="icon user-home medium"></div>
    </a>
  </div>
<?php endif ?>