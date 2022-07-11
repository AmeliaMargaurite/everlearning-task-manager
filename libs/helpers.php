<?php


function is_post_request(): bool {
  return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

function is_get_request(): bool {
  return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}

function error_class(array $errors, string $field): string {
  return isset($errors[$field]) ? 'error' : '';
}

// REDIRECTS

function redirect_to(string $url): void {
  header('Location:' . $url);
  exit;
}

// Saves data to SESSION for use on page being redirected to
function redirect_with(string $url, array $items): void {
  foreach ($items as $key => $value) {
    $_SESSION[$key] = $value;
  }

  redirect_to($url);
}

function redirect_with_message(string $url, string $message, string $type=FLASH_SUCCESS) {
  flash('flash_' . uniqid(), $message, $type);
  redirect_to($url);
}

// Checks SESSION for keys given and returns stored data if 
// there is any
// eg [$errors, $inputs] = session_flash('errors', 'inputs');
function session_flash(...$keys): array {
  $data = [];
  foreach ($keys as $key) {
    if (isset($_SESSION[$key])) {
      $data[] = $_SESSION[$key];
      unset($_SESSION[$key]);
    } else {
      $data[] = [];
    }
  }

  return $data;
}

?>