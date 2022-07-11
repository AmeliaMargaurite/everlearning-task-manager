<?php
include_once('../config.php');
include_once(DB_CONNECTION);

$title = "Register";
include_once(PAGE_START);
include_once(HEADER);

if (is_user_logged_in()) {
  redirect_to(HOME_URL);
}

flash();

if (is_post_request()) {

  $fields = [
    'username' => 'string | required | alphanumeric | between: 3, 25 | unique: users, username',
    'email' => 'email | required | email | unique: users, email',
    'password' => 'string | required | secure',
    'password2' => 'string | required | same: password',
  ];

  // custom messages
  $messages = [
    'password2' => [
      'required' => 'Please enter the password again', 
      'same' => 'The password does not match'
    ],
  ];

  [$inputs, $errors] = filter($_POST, $fields, $messages);
    print_r($errors);

  if ($errors) {
    redirect_with(HOME_URL . 'register', [
      'inputs' => $inputs,
      'errors' => $errors
    ]);
  }

  if (register_user($inputs['email'], $inputs['username'], $inputs['password'], false)) {
    redirect_with_message(
     HOME_URL . 'login',
      'Your account has been created successfully. Please login here.'
    );
  } else echo 'boop';
  
} else if (is_get_request()) {
  [$inputs, $errors] = session_flash('inputs', 'errors');
}

flash();
?>


<div >
  <form class="register" method="POST"id="register-form">
    <legend>Register</legend>
     

    <label for="username" id="register-form" >
      <p>Username</p>
      <input type="text" name="username" id="username" 
      value="<?= $inputs['username'] ?? '' ?>"
      class="<?= error_class($errors, 'username') ?>"
      />  
      <small><?= $errors['username'] ?? '' ?></small>
    </label>

     <label for="email"  >
      <p>Email</p>
      <input type="email" name="email" id="email"
       value="<?= $inputs['email'] ?? '' ?>"
      class="<?= error_class($errors, 'email') ?>"
      />  
      <small><?= $errors['email'] ?? '' ?></small>
      
    </label>

    <label for="password" >
      <p>Create password</p>
      <input type="password" name="password" autocomplete="new-password"
       value="<?= $inputs['password'] ?? '' ?>"
      class="<?= error_class($errors, 'password') ?>"
      />  
      <small><?= $errors['password'] ?? '' ?></small>  
      
    </label>

    <label for="password2" >
      <p>Reenter password</p>
      <input type="password" name="password2" id="password2" 
       value="<?= $inputs['password2'] ?? '' ?>"
      class="<?= error_class($errors, 'password2') ?>"
      />  
      <small><?= $errors['password2'] ?? '' ?></small>
    </label>

    <input type="submit" name="submit" id="submit" form="register-form" value="Register" />
    <p>Already registered? <a href="<?php echo HOME . '/login' ?>">Log in here</a></p>
  </form>
</div>
<?php
  include_once(PAGE_END);
?>