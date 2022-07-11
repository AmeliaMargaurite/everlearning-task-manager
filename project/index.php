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
    <input type="text" value="<?= $projectName ?>" />
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
      <h3>To do <add-new-task-icon></add-new-task-icon></h3>
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
      <h3>Notes <add-new-note-icon></add-new-note-icon></h3>
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
  import {taskRequestURL} from '<?= HOME_URL . "/js/helpers.js" ?>'
  
  window.editTask = function editTask(task_id) {
    const dialog = document.createElement("edit-task-dialog");
    dialog.setAttribute('task_id', task_id);
    document.body.appendChild(dialog)
  }

   window.editNote = function editNote(note_id) {
    const dialog = document.createElement("edit-note-dialog");
    dialog.setAttribute('note_id', note_id);
    document.body.appendChild(dialog)
  }

  // Drag and drop functions

  window.handleDragStart = function handleDragStart(e, task_id) {
    e.dataTransfer.setData('task_id', task_id);
  }

  window.handleDrop = async function handleDrop(e, status) {
    e.preventDefault();
    const task_id = e.dataTransfer.getData("task_id");
    if (task_id) {
        const response = await fetch(taskRequestURL, {
        method: "POST", 
        body: JSON.stringify({
          request: 'update_task_status', 
          task_id, 
          newStatus: status, 
          project_id: '<?php echo $project_id ?>',
          })
        }
      )
      location.reload();
    }
  }

  window.handleOnDragOver = function handleOnDragOver(e) {
    e.preventDefault();
  }

 // Mobile touch functions
  const taskTiles = document.querySelectorAll('.task__tile');
  for (const tile of taskTiles) {
    let timeout, longtouch;
    
    tile.addEventListener('touchstart', function() {
      timeout = setTimeout(function() {
        longtouch = true;
      }, 300);
    })
    tile.addEventListener('touchend', function(e) {
      if (longtouch) {
        e.preventDefault()
        tile.classList.add('mobile-selected');
        const tileContextMenu = document.createElement('tile-context-menu');
        tileContextMenu.setAttribute('task_id', tile.getAttribute('task_id'));
        const currentStatus = tile.parentElement.id;
        tileContextMenu.setAttribute('current_status', currentStatus )
        tile.parentElement.append(tileContextMenu);
      }
      longtouch = false;
      clearTimeout(timeout);
    });
  }




  // Adding colours to the legend markers
  const legendMarkers = document.querySelectorAll('.category_color');
  for (const marker of legendMarkers) {
    const color = marker.getAttribute('color');
    marker.style.background = color;
  }
</script>
<?php 
include_once(PAGE_END); 
?>