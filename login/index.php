<?php
include_once('../config.php');
include_once(DB_CONNECTION);

$title = "Login";
include_once(PAGE_START);

if (is_user_logged_in()) {
  redirect_to(HOME_URL);
}

$inputs = [];
$errors = [];

if (is_post_request()) {

  [$inputs, $errors] = filter($_POST, [
    'username' => 'string | required',
    'password' => 'string | required'
  ]);

  if ($errors) {

    redirect_with(HOME_URL . 'login', ['errors' => $errors, 'inputs' => $inputs]);
  }

  // if login fails

  if (!login($inputs['username'], $inputs['password'])) {
    $errors['login'] = 'Invalid username or password';

    redirect_with(HOME_URL . 'login',  ['errors' => $errors, 'inputs' => $inputs]);
  }

  // login successfully
  redirect_to(HOME_URL);
} else if (is_get_request()) {
  [$errors, $inputs] = session_flash('errors', 'inputs');
  if (isset($_GET['username']) && isset($_GET['password'])) {
    $inputs['username'] = $_GET['username'];
    $inputs['guest_password'] = $_GET['password'];
  }
}


?>
<div class="content">

  <form class="login" method="POST" id="login-form">

    <legend>Log in</legend>
    <?php if (isset($errors['login'])) : ?>
      <div class="alert alert-error">
        <?= $errors['login'] ?>
      </div>
    <?php endif ?>
    <label for="username">
      <p>Username</p>
      <input type="text" name="username" id="username" value="<?= $inputs['username'] ?? '' ?>" />
      <small><?= $errors['username'] ?? '' ?></small>
    </label>

    <label for="password">
      <p>Password</p>
      <input type="password" name="password" autocomplete="current-password" value="<?= $inputs['guest_password'] ?? null ?>" />
      <small><?= $errors['password'] ?? '' ?></small>

    </label>
    <input type="submit" name="submit" id="submit" form="login-form" value="Login" />
    <p>Don't have a login? <a href="<?php echo HOME ?>/register">Register here</a></p>
  </form>
</div>
<?php
include_once(PAGE_END);
?>