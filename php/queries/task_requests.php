<?php
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
include_once(TASK_FUNCTIONS);

$json = file_get_contents('php://input');

if ($json) {
  $jsonObj = json_decode($json);
  $jsonArray = json_decode($json, true);
  ['request' => $request] = $jsonArray;

  switch ($request) {
    case 'get_task_data':
      get_task_data();
      break;
    case 'delete_task':
      delete_task();
      break;
    case 'update_task_status': {
        global $jsonArray;
        [
          'task_id' => $task_id,
          'newStatus' => $newStatus,
          'project_id' => $project_id
        ] = $jsonArray;
        update_task_status($task_id, $newStatus, $project_id);
        break;
      }
    case 'get_due_date':
      get_due_date();
      break;
    case 'get_allocated_days':
      get_allocated_days();
      break;
  }
}
if (isset($_POST['update_task_status'])) {
  error_log($task_id . '--' . $newStatus . '--' . $project_id);
  $task_id = $_POST['task_id'];
  $newStatus = $_POST['status'];
  $project_id = $_POST['update_task_status'];

  update_task_status($task_id, $newStatus, $project_id);
}


function get_task_data()
{
  global $jsonArray;
  ['task_id' => $task_id, 'project_id' => $project_id] = $jsonArray;

  $task = $_SESSION['projects'][$project_id]->tasks[$task_id];
  echo json_encode($task);
}

// Delete from database

function delete_task()
{
  global $jsonArray;
  ['task_id' => $task_id, 'project_id' => $project_id] = $jsonArray;

  $conn = OpenConn();

  $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
  $stmt->bind_param('i', $task_id);


  if ($stmt->execute() === TRUE) {
    unset($_SESSION['projects'][$project_id]->tasks[$task_id]);
    echo json_encode("success");
    error_log('Sucessfully deleted task', 0);
  } else {
    echo json_encode("Error: $conn->error");
    error_log('Failed to delete task: $conn->error', 0);
  }
  $stmt->close();
  CloseConn($conn);
}


function update_task_status($task_id, $newStatus, $project_id)
{
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
  CloseConn($conn);

  header('Location: ../../project?project_id=' . $project_id);
}

function completedOrArchived($task)
{
  global $completedStatusId, $archivedStatusId;
  if ((int)$task->status_id === (int)$completedStatusId) {
    return false;
  } else {
    return true;
  }
}


function get_due_date()
{
  global $jsonObj;

  $task_id = $jsonObj->task_id;
  $project_id = $jsonObj->project_id;

  echo json_encode($_SESSION['projects'][$project_id]->tasks[$task_id]->due_date);
}

function get_allocated_days()
{
  global $jsonObj;

  $task_id = $jsonObj->task_id;
  $project_id = $jsonObj->project_id;
  $date = $_SESSION['projects'][$project_id]->tasks[$task_id]->days_allocated_to;

  echo $date ? json_encode(date('Y-m-d', strtotime($date))) : json_encode(null);
}
