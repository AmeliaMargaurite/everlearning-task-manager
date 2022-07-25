<?php 
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
 
if (isset($_POST['save_new_task'])) {
  saveNewTask();
} else if (isset($_POST['edit_task'])) {
  editTask();
} else if (isset($_POST['toggle_todays_tasks'])){
  header('Location: ../../index.php'); 
} 

function saveNewTask() {
  // _POST data
  $project_id = $_POST['save_new_task'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $category_id = $_POST['category'] ? $_POST['category'] : NULL;
  $due_date = $_POST['due_date'] ? $_POST['due_date'] : NULL;
  $priority_id = $_POST['priority'] ? $_POST['priority']: NULL;
  $days_allocated_to = $_POST['days_allocated_to'] ? $_POST['days_allocated_to']: NULL;

  // Standard initial settings
  $date_created = date('Y-m-d');
  $status_id = getStatusIdFromName('incomplete');

  $conn = OpenConn();
  $stmt = 
    $conn->prepare(
      "INSERT INTO tasks(name, description, date_created, status_id, project_id, category_id, due_date, priority_id, days_allocated_to) 
      VALUES(?,?,?,?,?,?,?,?,?)");
  $stmt->bind_param('sssiiisis', 
    $name, $description, $date_created, $status_id, $project_id, $category_id, $due_date, $priority_id, $days_allocated_to);

  if ($stmt->execute() === TRUE) {
    getProjectsTasks($project_id);
    error_log("New task added successfully", 0);
  } else {
    error_log("Error: " . $request . "<br/>" . $conn->error, 0);
  }
  $stmt->close();
  CloseConn($conn);
  header('Location: ../../project?project_id=' . $project_id); 
}

/**
 * Gets post data for existing task. Updates database and _SESSION
 * Redirects page to original page sent from
 * @return void;
 */

function editTask(): void {
  $task_id = $_POST['task_id'];
  $project_id = $_POST['edit_task'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $category_id = !empty($_POST['category']) ? (int)$_POST['category'] : NULL ;
  $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : NULL;
  $priority_id = !empty($_POST['priority']) ? (int)$_POST['priority'] : NULL;
  $days_allocated_to = !empty($_POST['days_allocated_to']) ? $_POST['days_allocated_to']: NULL;

  
  $conn = OpenConn();
  $stmt = $conn->prepare("UPDATE tasks SET name = ?, description = ?, category_id = ?, priority_id = ?, due_date = ?, days_allocated_to = ? WHERE task_id = ?");

  $stmt->bind_param('ssiissi', $name, $description, $category_id, $priority_id,  $due_date, $days_allocated_to, $task_id);

  if ($stmt->execute() === TRUE) {
    
    $updates = array('name' => $name, 'description'=>$description, 'category_id' =>$category_id, 'due_date' => $due_date, 'priority_id' => $priority_id, 'days_allocated_to' => $days_allocated_to);
    foreach($updates as $key => $prop) {
      $_SESSION['projects'][$project_id]->tasks[$task_id]->$key = $prop;
    }
    error_log("Task updated successfully.\n Name: " . $name . '\nProject_id: ' . $project_id);
  } else {
    error_log("Error: " . $request . "<br/>" . $conn->error . "\n Name: " . $name . '\nProject_id: ' . $project_id);
  }

  $redirectUrl = $_POST['referrer'];
  CloseConn($conn);
  header("Location: $redirectUrl"); 
}




?>