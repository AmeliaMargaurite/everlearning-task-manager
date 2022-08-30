<!-- PROJECT TILES -->

<?php
function renderProjectTiles($project)
{
  global $incomplete, $current, $completed;
  if ($project->tasks) {
    foreach ($project->tasks as $task) {

      $status = getStatusNameFromId($task->status_id);
      switch ($status) {
        case 'incomplete':
          $incomplete++;
          break;
        case 'current':
          $current++;
          break;
        case 'completed':
          $completed++;
          break;
      }
    }
  }
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
<!-- TASK TILES -->
<?php

/**
 * 
 */

function sortTasks($tasks, $type, $order)
{
  $new_array = array();
  $sortable_array = array();

  foreach ($tasks as $position => $task) {
    foreach ($task as $prop => $data) {
      if ($prop == $type) {
        $sortable_array[$position] = $data;
      }
    }
  }

  switch ($order) {
    case 'asc':
      asort($sortable_array);
      break;
    case 'desc':
      arsort($sortable_array);
      break;
  }

  foreach ($sortable_array as $position => $product) {
    $new_array[$position] = $tasks[$position];
  }

  return $new_array;
}

function getTaskCount($status, $project_id)
{
  $status_id = getStatusIdFromName($status);
  $tasks = $_SESSION['projects'][$project_id]->tasks;
  $count = 0;

  if ($tasks) {
    foreach ($tasks as $task) {
      if ($task->status_id == $status_id) {
        $count += 1;
      }
    }
  }
  return $count;
}

function renderTaskTiles($status, $project_id)
{
  global $currentTask_id;
  $unsortedTasks = $_SESSION['projects'][$project_id]->tasks;
  $status_id = getStatusIdFromName($status);
  $categories = $_SESSION['projects'][$project_id]->categories;
  $sortOrder = $_SESSION['projects'][$project_id]->sortOrder;
  $sortParts = explode('-', $sortOrder);

  $type = $sortParts[0];
  $order = $sortParts[1];

  if ($unsortedTasks) {
    $tasks = $sortOrder === 'auto' ? $unsortedTasks : sortTasks($unsortedTasks, $type, $order);

    foreach ($tasks as $task) {
      if ($task->status_id == $status_id) {
        $current = $task->task_id === $currentTask_id ? "current" : "";
?>

        <div class="task__tile <?= $current ?>" draggable="true" task_id="<?= $task->task_id ?>" onDragStart="handleDragStart(event, '<?= $task->task_id ?>')" onclick="openDialog('edit-task-dialog',{task_id:'<?= $task->task_id ?>', project_id: '<?= $project_id ?>'})" project_id="<?= $project_id ?>">

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
          </div>
        </div>
<?php }
    }
  }
}
?>

<!-- NOTE TILES -->
<?php


function renderNotes($notes)
{
  if ($notes) {
    foreach ($notes as $note) {
?>
      <li id="note_<?= $note->note_id ?>" onclick="openDialog('edit-note-dialog', {note_id: <?= $note->note_id ?>})">
        <?= $note->note ?>
        <div class="icon edit medium"></i>
      </li>
      <div class="divider"></div>
<?php
    }
  }
}

function getNoteCount($project_id)
{
  $notes = $_SESSION['projects'][$project_id]->notes;
  return $notes ? count($notes) : 0;
}

?>