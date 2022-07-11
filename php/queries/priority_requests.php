<?php 
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
include_once(TASK_FUNCTIONS);

$json = file_get_contents('php://input');
$jsonObj = json_decode($json);

$request = $jsonObj->request;

switch($request) {
  case 'get_all_priorities': get_all_priorities(); break;
}

class Priority {
  public $name;
  public $priority_id;
  public $value;
  public $this_task;
}

function get_all_priorities() {
  global $jsonObj;

  $task_id = $jsonObj->task_id;

  $conn = OpenConn();
  $priorities_stmt = $conn->prepare("SELECT * FROM priorities");
  $priorities_stmt->execute();
  $priority_results = $priorities_stmt->get_result();

  $this_task_priority_id;

  if ($task_id) {
    $task_stmt = $conn->prepare("SELECT priority_id FROM tasks WHERE task_id = ?");
    $task_stmt->bind_param('i', $task_id);
    $task_stmt->execute();
    $task_results = $task_stmt->get_result();

    if ($task_results->num_rows > 0) {
      $this_task_priority_id = $task_results->fetch_assoc()['priority_id'];
// echo $this_task_priority_id;
    }
    $task_stmt->close();

  }

  $priorities = array();
  
  if ($priority_results->num_rows > 0) {
    while ($result = $priority_results->fetch_assoc()) {
// echo $this_task_priority_id . '____';
      $priority = new Priority;
      $priority->name = $result['name'];
      $priority->priority_id =$result['priority_id'];
      $priority->value = $result['value'];
      // echo $task_results_priority_id . '___' . $priority->priority_id;
      if ($this_task_priority_id 
        && $this_task_priority_id == $priority->priority_id 
        ) {
        $priority->this_task = true;
      } else $priority->this_task = false;

      $priorities[$priority->priority_id] = $priority;
    }
  }

  echo json_encode($priorities);
  $priorities_stmt->close();
  CloseConn($conn);
}

function get_priorities_dropdown() {


}

?>