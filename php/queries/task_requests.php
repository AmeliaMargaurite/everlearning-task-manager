<?php 
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
include_once(TASK_FUNCTIONS);

$json = file_get_contents('php://input');

if ($json) {
$jsonObj = json_decode($json);
$jsonArray = json_decode($json, true);
['request' => $request] = $jsonArray;

switch($request) {
  case 'get_task_data': get_task_data(); break;
  case 'delete_task': delete_task(); break;
  case 'update_task_status': { 
    global $jsonArray;
    ['task_id'=> $task_id, 
    'newStatus' => $newStatus, 
    'project_id'=> $project_id] = $jsonArray;
    update_task_status($task_id, $newStatus, $project_id);
    break;
  }
  case 'get_all_data_for_todays_tasks': get_all_data_for_todays_tasks(); break;
  case 'toggle_on_todays_task': toggle_on_todays_task(); break;
  // case 'remove_from_todays_tasks': remove_from_todays_tasks(); break;
  case 'get_due_date': get_due_date(); break;

}} 
   if (isset($_POST['update_task_status'])) {
    error_log($task_id .'--' . $newStatus . '--' . $project_id);
    $task_id = $_POST['task_id']; 
    $newStatus = $_POST['status']; 
    $project_id = $_POST['update_task_status']; 

    update_task_status($task_id, $newStatus, $project_id);
  }


function get_task_data()  {
  global $jsonArray;
  ['task_id' => $task_id, 'project_id' => $project_id] = $jsonArray;
  $task = $_SESSION['projects'][$project_id]->tasks[$task_id];
  echo json_encode($task);
}

// Delete from database

function delete_task() {
  global $jsonArray;
  ['task_id' => $task_id, 'project_id' => $project_id] = $jsonArray;

  $conn = OpenConn();

  $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
  $stmt->bind_param('i', $task_id);
  

  if ($stmt->execute() === TRUE) {
    unset($_SESSION['projects'][$project_id]->tasks[$task_id]);
    echo json_encode("success");
    error_log('Sucessfully deleted task',0);
  } else {
    echo json_encode("Error: " . $request . "<br/>" . $conn->error);
    error_log('Failed to delete task: '. $request . "<br/>" . $conn->error,0);
  }
  $stmt->close();
  CloseConn($conn);
}


function update_task_status($task_id, $newStatus, $project_id) {
  $status_id = getStatusIdFromName($newStatus);
  $conn = OpenConn();
  $stmt = $conn->prepare("UPDATE tasks SET status_id = ? WHERE task_id = ?");
  $stmt->bind_param('ii', $status_id, $task_id);

  if ($stmt->execute() === TRUE) {
    $_SESSION['projects'][$project_id]->tasks[$task_id]->status_id = $status_id;  
    error_log("Task status updated successfully", 0);
    
  } else {
    error_log("Error: <br/>" . $conn->error, 0);
  }  
  $stmt->close();

$status = getStatusNameFromId($status_id);
  error_log($status);
  if ($status === 'completed' || $status === 'archived') {
    $newStmt = $conn->prepare("UPDATE tasks SET todays_task = ? WHERE task_id = ?");
    $notToday = 0;
    $newStmt->bind_param('ii', $notToday, $task_id);

    if ($newStmt->execute() === TRUE) {
      error_log("Task removed from todays_tasks", 0);
    } else {
      error_log("Error: " .  $conn->error, 0);
    }

    $newStmt->close();
  }

  CloseConn($conn);
  
  header('Location: ../../project?project_id=' . $project_id); 
}

function get_all_data_for_todays_tasks() {
  $conn = OpenConn();
  $projectsSql = "SELECT name, project_id FROM `projects` ";
  $projectsRequest = $conn->query($projectsSql);


  $projects = array();

  $completedStatusId = getStatusIdFromName('completed');
  $archivedStatusId = getStatusIdFromName('archived');

  if ($projectsRequest->num_rows > 0) {
    while ($projectData = $projectsRequest->fetch_assoc()) {
      $project_stmt = $conn->prepare(
        "SELECT name, task_id, todays_task
        FROM tasks 
        WHERE project_id = ? && status_id <> ? && status_id <> ? ");

      $project = new Project;
      foreach ($projectData as $key =>$prop) {
        $project->$key = $prop;
      }
      $project_stmt->bind_param('iii', $project->project_id, $completedStatusId, $archivedStatusId);
      $project_stmt->execute();
      $tasksRequest = $project_stmt->get_result();
      $project_stmt->close();


      if ($tasksRequest->num_rows > 0) {
        while ($taskData = $tasksRequest->fetch_assoc()) {
          $task = new Task;
          foreach ($taskData as $key =>$prop) {
            $task->$key = $prop;
          }

          $project->tasks[$task->task_id] = $task;
        }
      }

      $projects[$project->project_id] = $project;
    }
  }

  CloseConn($conn);
  echo json_encode($projects);
}


function toggle_on_todays_task() {
  global $jsonObj;

  $task_id = $jsonObj->task_id;
  $checked = $jsonObj->checked === true ? 1 : 0;

  $conn = OpenConn();

  $stmt = $conn->prepare("UPDATE tasks SET todays_task = ? WHERE task_id = ?");
  $stmt->bind_param('ii', $checked, $task_id);

  if ($stmt->execute()) {
    echo "success";
    error_log("Successfully toggled task's todays task status", 0);
  } else {
    echo "Error: " . $stmt . "<br/>" . $conn->error;
    error_log("Error: " . $stmt . "<br/>" . $conn->error, 0);
  }
  $stmt->close();
  CloseConn($conn);
}

function get_due_date() {
  global $jsonObj;

  $task_id = $jsonObj->task_id;

  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT due_date FROM tasks WHERE task_id = ?");
  $stmt->bind_param('i', $task_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc()['due_date']);
  } else echo json_encode(false);
  $stmt->close();
  CloseConn($conn);
}

?>