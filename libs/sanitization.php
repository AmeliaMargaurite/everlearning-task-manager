<?php

const FILTERS = [
  'string' => FILTER_SANITIZE_STRING,
  'string[]' => [
    'filter' => FILTER_SANITIZE_STRING,
    'flags' => FILTER_REQUIRE_ARRAY
  ],
  'email' => FILTER_SANITIZE_EMAIL,
  'int' => [
    'filter' => FILTER_SANITIZE_NUMBER_INT,
    'flags' => FILTER_REQUIRE_SCALAR
  ],
  'int[]' => [
    'filter' => FILTER_SANITIZE_NUMBER_INT,
    'flags' => FILTER_REQUIRE_ARRAY
  ],
  'float' => [
    'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
    'flags' => FILTER_FLAG_ALLOW_FRACTION
  ],
  'float[]' => [
    'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
    'flags' => FILTER_REQUIRE_ARRAY
  ],
  'url' => FILTER_SANITIZE_URL
];


/**
 * Recursively trim strings in an array
 * @param array $items
 * @return array
 */

function array_trim(array $items): array
{
  return array_map(function ($item) {
    if (is_string($item)) {
      return trim($item);
    } elseif (is_array($item)) {
      return array_trim($item);
    } else return $item;
  }, $items);
}

/**
 * Santize the inputs based on the rules and optionally trim 
 * the string
 * @param array $inputs
 * @param array $fields
 * @param int $default_filter FILTER_SANITIZE_STRING
 * @param array $filters FILTERS
 * @param bool $trim
 * @return array
 */

function sanitize(array $inputs, array $fields = [], int $default_filter = FILTER_SANITIZE_STRING, array $filters = FILTERS, bool $trim = true): array
{
  function filterFields($field, $filters)
  {

    return $filters[trim($field)];
  }
  if ($fields) {
    $options = array_map(function ($field) use ($filters) {
      return filterFields($field, $filters);
    }, $fields);
    $data = filter_var_array($inputs, $options);
  } else {
    $data = filter_var_array($inputs, $default_filter);
  }

  return $trim ? array_trim($data) : $data;
}
