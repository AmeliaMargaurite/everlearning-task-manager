<?php 
include_once '../config.php';
require_login();
include_once(DB_CONNECTION);
include_once(PROJECT_FUNCTIONS);
include_once(TASK_FUNCTIONS);
include_once(NOTE_FUNCTIONS);
include_once(CATEGORY_REQUESTS);

include_once(PAGE_START); 
?>
<div class="content">
<h1><?= current_user() ?></h1>
<ul>
  <li>Change password option</li>
  <li>Look at archive list of tasks/projects</li>
  <li>Turn on/off dark mode</li>
  <li>Stats</li>
</ul>
<p class="logout"><a href="<?=  HOME . '/logout'; ?>">Logout</a></p></div>
</div>
<?php 
include_once(PAGE_END); 
?>