<?php

## REGISTER

/**
 * Return true if user has been registered successfully
 * @param string $email
 * @param string $username
 * @param string $password
 * @param bool $is_admin
 * @param bool $is_admin false
 */


function register_user(string $email, string $username, string $password, bool $is_admin = false): bool {
  $conn = OpenConn();
  $date_created = date("Y-m-d H:i:s");
  $stmt = $conn->prepare("INSERT INTO  `users`(`username`, `email`, `password`, `is_admin`, `date_created`) VALUES(?,?,?,?,?)");
  $is_is_admin = (int) $is_admin;
  $stmt->bind_param('sssis', $username, $email, password_hash($password, PASSWORD_BCRYPT), $is_is_admin, $date_created);
  
  $result =  $stmt->execute();
  CloseConn($conn);
  
  return $result;
}

## LOGIN

/**
 * If user is found returns Assoc Array of username and password
 * Else returns FALSE
 * 
 * @param string $username
 * @return assoc array of username and password else FALSE
 */

 function find_user_by_username(string $username) {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT username, password, `user_id` FROM `users` WHERE username = ?");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  CloseConn($conn);

  return $result->fetch_assoc();
 }

 /**
  * If username and password match database, returns true else false
  * @param string $username
  * @param string $password
  * @return bool
  */

 function login(string $username, string $password): bool {
  $user = find_user_by_username($username);

  // if user found, check the password
  if ($user && password_verify($password, $user['password'])) {
    // prevent session fixation attack, TODO
    session_regenerate_id();

    // set username in the session
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['user_id'];

    return true;
  }
  return false;
 }

 /**
  * Returns boolean on if user is logged in
  * @return bool
  */

  function is_user_logged_in(): bool {
    return isset($_SESSION['username']);
  }

/**
 * Redirects if user is meant to be signed in and isn't
 * @return void
 */

function require_login(): void {
  if (!is_user_logged_in()) {
    redirect_to(HOME_URL . 'login');
  }
}

/**
 * Logs out user if logged in
 * @return void
 *  */  

 function logout(): void {
  if (is_user_logged_in()) {
    unset($_SESSION['username'], $_SESSION['user_id']);
    session_destroy();
    redirect_to(HOME_URL . 'login');
  }
 }

 /**
  * Definds current user
  * @return $username or null
  */

  function current_user() {
    if (is_user_logged_in()) {
      return $_SESSION['username'];
    }

    return null;
  }

?>