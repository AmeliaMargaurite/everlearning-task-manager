<?php
include_once(__DIR__ . '/../../config.php');
$json = file_get_contents('php://input');

if ($json) {
  $jsonArray = json_decode($json, true);
  ['request' => $request] = $jsonArray;

  if ($request === 'get_statuses') {
    ['project_id' => $project_id] = $jsonArray;
    get_statuses($project_id);
  } else if ($request === 'get_dropdown_statuses') {
    get_dropdown_statuses();
  }
} else if (isset($_POST)) {
  echo 'boop';
}

function get_statuses($project_id)
{
  echo json_encode($_SESSION['projects'][$project_id]->statuses);
}

function get_dropdown_statuses()
{
  global $jsonArray;
  ['project_id' => $project_id, 'task_id' => $task_id] = $jsonArray;
  $statuses_results = $_SESSION['projects'][$project_id]->statuses;
  $task_status_id = isset($_SESSION['projects'][$project_id]->tasks[$task_id]->status_id) ? $_SESSION['projects'][$project_id]->tasks[$task_id]->status_id : null;
  $statuses = array();
  // echo $_SESSION['projects'][$project_id]->tasks[$task_id]->status_id;

  if ($statuses_results) {
    foreach ($statuses_results as $status) {
      // print_r($status);
      $status->this_task = false;
      if ($task_status_id && $task_status_id === $status->status_id) {
        $status->this_task = true;
      }
      $statuses[$status->status_id] = $status;
    }
  }
  echo json_encode($statuses);
}
