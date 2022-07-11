<?php
include_once('../config.php');
include_once(DB_CONNECTION);

$title = "Login";
include_once(PAGE_START);
include_once(HEADER);
logout();
?>
<p> Successfully logged out </p>
<?php
  include_once(PAGE_END);
?>