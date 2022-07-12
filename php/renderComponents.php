
<?php 
function renderProjectTiles($project) {
if ($project->tasks) {
foreach($project->tasks as $task) {
  $status = getStatusNameFromId($task->status_id);
  switch ($status) {
      case 'incomplete': $incomplete++;
      break;
      case 'current': $current++;
      break;
      case 'completed': $completed++;
      break;
  }}}
?>
<a href="./project?project_id=<?= $project->project_id ?>" class="project_tile">
  <div class="project_tile--content">
    <h3><?= $project->name ?></h3>
    <p><?= $project->description ?></p>
    <div class="counts">
      <span>
        <div class="icon clipboard"></div><?= $incomplete ?>
      </span>

      <span>
        <div class="icon coding"></div><?= $current ?> 
      </span>
      <span>
        <div class="icon tick-square"></div> <?= $completed ?>
      </span>
			</div>
  </div>
</a>
 <?php } ?>

 <?php 
 function renderTaskTiles($status, $project_id) {
  global $currentTask_id;
  $tasks = $_SESSION['projects'][$project_id]->tasks;
  $status_id = getStatusIdFromName($status);
  $categories = $_SESSION['projects'][$project_id]->categories;
  
  if ($tasks) {
  foreach ($tasks as $task) { 
    $current = $task->task_id === $currentTask_id ? "current" : "";
    $todays_task = $task->todays_task === 1 ? 'todays_task': '';
    if ($task->status_id == $status_id) { ?>
      
       <div class="task__tile <?= $current ?> <?= $todays_task ?>" draggable="true" task_id="<?= $task->task_id ?>" onDragStart="handleDragStart(event, '<?= $task->task_id ?>')" onclick="editTask('<?= $task->task_id ?>')" >
     
        <div class="wrapper">
           <?php if (isset($task->category_id)) {
        foreach ($categories as $category) {
          if ($category->category_id == $task->category_id) { ?>
            <span class="category_color" color="<?= $category->color ?>"></span>
         <?php } 
        }
      } ?>
          <div class="name"><?= $task->name ?></div>
          <div class="description"><?= $task->description ?></div>
      </div></div>
   <?php } }

  }
}

function renderNotes($notes) {
  if($notes) {
    foreach($notes as $note) {
    ?>
    <li id="note_<?=$note->note_id ?>">
      <p><?= $note->note ?></p>
      <div class="icon edit medium" onclick="editNote('<?=$note->note_id ?>')"></i>
    </li>
    <div class="divider"></div>
    <?php
  }}
}

?>