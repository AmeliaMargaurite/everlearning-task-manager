<?php

/**
 * Sanitize and validate data
 * @param array $data
 * @param array $fields
 * @param array $messages
 * @return array
 */

 function filter(array $data, array $fields, array $messages = []): array {
  $sanitization = [];
  $validation = [];

  // extract sanitization and validation rules
  foreach ($fields as $field => $rules) {
    if (strpos($rules, '|')) {
      /*  
      * The below takes:
      * eg  'username' => 'string | required | alphanumeric | between: 3, 25 | unique: users, username'
      * gives $sanitization['usernaame'] = 'string', $validation['username'] = ['required', 'alphanumeric', 'between: 3, 25', 'unique: users', 'username'];
      */
      [$sanitization[$field], $validation[$field]] = explode('|', $rules, 2);
    } else {
      $sanitization[$field] = $rules;
    }
  }

  $inputs = sanitize($data, $sanitization);
  $errors = validate($inputs, $validation, $messages);
   
  return [$inputs, $errors];
 }

?>