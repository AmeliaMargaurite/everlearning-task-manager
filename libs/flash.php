<?php

// https://www.phptutorial.net/php-tutorial/php-flash-messages/

  const FLASH = 'FLASH_MESSAGES';

  const FLASH_ERROR = 'error';
  const FLASH_WARNING = 'warning';
  const FLASH_INFO = 'info';
  const FLASH_SUCCESS = 'success';

  /**
   * Create a flash message
   * 
   * @param string $name
   * @param string $message
   * @param string $type
   * @return void
   */

   function create_flash_message(string $name, string $message, string $type): void {

    // remove existing message with the name
    if (isset($_SESSION[FLASH][$name])) {
      unset($_SESSION[FLASH][$name]);
    }

    // add message to the session
    $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
   }

  /**
   * Format a flash message
   * 
   * @param array $flash_message
   * @return string
   */

  function format_flash_message(array $flash_message): string {
  return sprintf('<span class="alert alert-%s">%s</span>', 
          $flash_message['type'], 
          $flash_message['message']);
  }

  /**
  * Display a flash message
  * 
  * @param string $home
  * @return void
  */

  function display_flash_message(string $name): void  {
    if (!isset($_SESSION[FLASH][$name])) {
      return;
    }

    // get message from the session
    $flash_message = $_SESSION[FLASH][$name];

    // delete the flash message
    unset($_SESSION[FLASH][$name]);

    // display the flash message
    echo format_flash_message($flash_message);
  }

  /**
   * Display all flash messages
   * 
   * @return void
   */

  function display_all_flash_messages(): void {
    echo $_SESSION[FLASH];
    if (!isset($_SESSION[FLASH])) {
      return;
    }

    // get flash messages
    $flash_messages = $_SESSION[FLASH];

    // remove all flash messages
    unset($_SESSION[FLASH]);

    // show all flash messages
    foreach ($flash_messages as $flash_message) {
      echo format_flash_message($flash_message);
    }
  }

  /**
   * Flash a message
   * 
   * @param string $name
   * @param string $message
   * @param string $type (error, warning, info, success)
   * @return void
   */

  function flash(string $name = '', string $message = '', string $type = ''):void {
    // If all three params are given, create message
    if ($name !== '' && $message !=='' && $type !== '') {
      create_flash_message($name, $message, $type);

      // If only $name is given, display stored message
    } elseif ($name !== '' && $message === '' && $type === '') {
      display_flash_message($name);

      // If no params given, display all stored messages
    } elseif($name === '' && $message === '' && $type === '') {
      display_all_flash_messages();
    }
  }

?>