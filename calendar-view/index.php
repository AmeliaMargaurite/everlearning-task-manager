<?php 
include_once '../config.php';
require_login();
$title = "Calendar View";
include_once(PAGE_START); 
// include_once(HEADER) ;
include_once(DB_CONNECTION);
include_once(PROJECT_FUNCTIONS);
include_once(RENDER_COMPONENTS);


if ($_SESSION['projects'] == '' || !isset($_SESSION['projects'])) {
    getUsersProjects($_SESSION['users_id']);
}
$projects = $_SESSION['projects'];

include_once('./calendar.php');
$calendar = new Calendar();
?>
<div class="content calendar-view">

<div class="page__wrapper">
  <div class="title__wrapper">
    <a href="<?= HOME_URL ?>" class="back-btn"><div class="icon arrow"></div>back</a>
    <span >
      <h1>Calendar</h1>
     <p class="written-date"><?= date('l\, jS \of F Y') ?></p>

    </span>
    <?php include_once(DASHBOARD_LINK) ?>
  </div>


<!-- Display a calendar, full screen, showing the current month -->
<!-- Have ability to move back and forward through the months -->
<?= $calendar->show() ?>
</div>
<?php 
include_once(PAGE_END); 
?>