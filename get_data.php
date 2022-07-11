<?php 

/**
 * Gets users projects and saves to _SESSION
 * @param $user_id
 * @return void
 */

function getUsersProjects($user_id): void {
  $conn = OpenConn();
  $projects_stmt = $conn->prepare("SELECT * FROM `projects` WHERE owner_id = ?");
  $projects_stmt->bind_param('i', $_SESSION['user_id']);
  $projects_stmt->execute();
  $results = $projects_stmt->get_result();
  $projects = array();

  if ($results->num_rows > 0) {
    while ($projectData = $results->fetch_assoc()) {
      if (!empty($projectData)) {
        $project = new Project;
        foreach ($projectData as $key =>$prop) {
            $project->$key = $prop;
        }
        $_SESSION['projects'][$project->project_id] = $project;
        getStatuses($project->project_id);
        getProjectsTasks($project->project_id);
        getProjectsNotes($project->project_id);
        getProjectsCategories($project->project_id);
      }
    }
  } 
  CloseConn($conn);
}

/**
 * 
 */

 function doesUserOwnProject($project_id) {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT owner_id FROM projects WHERE owner_id = ? && project_id = ?");
  $stmt->bind_param('ii', $_SESSION['user_id'], $project_id);
  $stmt->execute();
  $request = $stmt->get_result();
  $stmt->close();

  if ($request->num_rows > 0) {
    return true;
  }
  return false;
 }

/**
 * Sets all statuses to all projects -- would be better to set it per the _SESSION['projects'] object but need to attack it differently
 * @param $project_id
 * @return void;
 */

function getStatuses($project_id): void {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT * FROM statuses");
  $stmt->execute();
  $request = $stmt->get_result();
  $stmt->close();

  if ($request->num_rows > 0) {
    unset($_SESSION['projects'][$project_id]->statuses);
    while ($data = $request->fetch_assoc()) {
      $status = new Status;
       foreach ($data as $key =>$prop) {
        $status->$key = $prop;
      }
      $_SESSION['projects'][$project_id]->statuses[$status->status_id] = $status;
    }
  }
}

/**
 * Gets Projects tasks and saves to _SESSION
 * @param $project_id;
 * @return void
 */

function getProjectsTasks($project_id):void {
  $conn = OpenConn();
  $tasks_stmt = $conn->prepare("SELECT * FROM tasks
  WHERE project_id = ? ");
  $user_id =  $_SESSION['user_id'];
  $tasks_stmt->bind_param('i', $project_id);
  $tasks_stmt->execute();
  
  $tasksRequest = $tasks_stmt->get_result();
  $tasks_stmt->close();
  if ($tasksRequest->num_rows > 0) {
    unset( $_SESSION['projects'][$project_id]->tasks);
    while ($data = $tasksRequest->fetch_assoc()) {
      $task = new Task;
      foreach ($data as $key =>$prop) {
        $task->$key = $prop;
      }
      $_SESSION['projects'][$project_id]->tasks[$task->task_id] = $task;
    }
  }
  CloseConn($conn);
}

function getProjectsNotes($project_id) {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT * FROM notes WHERE project_id = ?");
  $stmt->bind_param('i', $project_id);
  $stmt->execute();

  $request = $stmt->get_result();

  if ($request->num_rows > 0) {
    unset($_SESSION['projects'][$project_id]->notes);

    while ($data = $request->fetch_assoc()) {
      $note = new Note;
      foreach ($data as $key =>$prop) {
        $note->$key = $prop;
      }
      $_SESSION['projects'][$project_id]->notes[$note->note_id] = $note;
    }
  }
    $stmt->close();

  CloseConn($conn);
}

/**
 * Uses $project_id to find categories and updates $_SESSION
 * @param $project_id
 * @return void
 */

function getProjectsCategories($project_id): void {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT * FROM categories WHERE project_id = ?");
  $stmt->bind_param('i', $project_id);
  $stmt->execute();

  $request = $stmt->get_result();
  $stmt->close();
  if ($request->num_rows > 0) {
    unset( $_SESSION['projects'][$project_id]->categories);
    while ($data = $request->fetch_assoc()) {
      $category = new Category;
      foreach ($data as $key =>$prop) {
        $category->$key = $prop;
      }
      
      $_SESSION['projects'][$project_id]->categories[$category->category_id] = $category;
    }
  }
  CloseConn($conn);
}

?>