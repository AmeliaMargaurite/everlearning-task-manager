<?php 
include_once '../config.php';
require_login();
include_once(DB_CONNECTION);
include_once(PROJECT_FUNCTIONS);
include_once(TASK_FUNCTIONS);
include_once(NOTE_FUNCTIONS);
include_once(CATEGORY_REQUESTS);
include_once(RENDER_COMPONENTS);



$project_id = '';
$currentTask_id = '';

$currentURL = $_SERVER['REQUEST_URI'];
$base_urlQueries = parse_url($currentURL, PHP_URL_QUERY);
$queries = explode('&', $base_urlQueries);

foreach($queries as $query) {
  $option = explode('=', $query);
  if ($option[0] === 'project_id') {
    $project_id = $option[1];
  } else if ($option[0] === 'task_id') {
    $currentTask_id = $option[1];
  } else if ($option[0] === 'sort_order') {
    $order = $option[1];
  }
}

// need to check if this project_id belongs to user_id
if (!doesUserOwnProject($project_id)) {
  redirect_to(HOME_URL);
}

getProjectsTasks($project_id);
getProjectsNotes($project_id);
getProjectsCategories($project_id);
 $project = $_SESSION['projects'][$project_id];
$projectName = $project->name;
$tasks = $project->tasks;
$notes = $project->notes;

$title = $projectName . ' | Task Manager';
include_once(PAGE_START); 
?>
<div class="page__wrapper">
  <div class="title__wrapper">
    <a href="<?= HOME_URL ?>" class="back-btn"><div class="icon arrow"></div>back</a>
    <span class="project__name">
      <h1><?= $projectName ?></h1>
      <button class="icon-only" onclick="openDialog('edit-project-dialog')">
        <div class="icon edit"></div>
      </button>
    </span>
    <?php include_once(DASHBOARD_LINK) ?>
  </div>
  <div class="category-sort__wrapper">
    <?php include_once(ROOTPATH .'/php/category_legend.php') ?>
    <!-- <div class="sort__wrapper">Sort by
      <select>
        <option value="">Select</option>
        <option value="due_date-asc">Due Date - Ascending</option>
        <option value="due_date-desc">Due Date - Decending</option>
        <option value="last_modified-asc">Last Modified - Ascending</option>
        <option value="last_modified-desc">Last Modified - Ascending</option>
        <option value="date_created-asc">Date Created - Ascending</option>
        <option value="date_created-desc">Date Created - Decending</option>

      </select>
    </div> -->
  </div>
  <section class="tasks__wrapper">
  
    <div class="task__column--wrapper" onDragOver="handleOnDragOver(event)" onDrop="handleDrop(event, 'incomplete')">
      <h3>
        To do 
        <button class="icon-only small" onclick="openDialog('add-new-task-dialog')">
          <div class="icon plus"></div>
        </button>
      </h3>
      <div id="incomplete" class="incomplete task__column" >
        
        <?php renderTaskTiles('incomplete', $project_id); ?>
      </div>
    </div>

    <div  class="task__column--wrapper" onDragOver="handleOnDragOver(event)" onDrop="handleDrop(event, 'current')">
      <h3>Current</h3>
      <div id="current" class="current task__column" >
        
        <?php renderTaskTiles('current', $project_id); ?>
      </div>
    </div>

    <div  class="task__column--wrapper" onDragOver="handleOnDragOver(event)" onDrop="handleDrop(event, 'completed')">
      <h3>Completed</h3>
      <div id="completed" class="completed task__column" >
       <?php renderTaskTiles('completed', $project_id); ?>
      </div>
    </div>
    
    <div class="task__column--wrapper">
      <h3>Notes 
        <button class="icon-only small" onclick="openDialog('add-new-note-dialog')">
          <div class="icon plus"></div>
        </button>
      </h3>
      <div class="notes task__column" id="notes_column">
        <?php
        renderNotes($notes);
         ?>
      </div>
    </div>

  </section>
  <?php if ($currentTask_id) {
      echo '<edit-task-dialog task_id="'. $currentTask_id .'"></edit-task-dialog>'; 
    } ?>
</div>

<script type="module">
  import {handleDragStart, handleDrop, handleOnDragOver} from '<?= HOME_URL . "project/functions.js" ?>';
  

  window.handleDragStart = handleDragStart;
  window.handleDrop = handleDrop;
  window.handleOnDragOver = handleOnDragOver;

</script>
<?php 
include_once(PAGE_END); 
?>