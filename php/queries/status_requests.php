<?php
include_once(__DIR__ . '/../../config.php');
  $json = file_get_contents('php://input');

if ($json) {
  $jsonArray = json_decode($json, true);
  ['request' => $request] = $jsonArray;

  if ($request === 'get_statuses') {
   ['project_id'=>$project_id] = $jsonArray;
    get_statuses($project_id);
  }
} else if (isset($_POST)) {
  echo 'boop';
}

function get_statuses($project_id) {
  echo json_encode($_SESSION['projects'][$project_id]->statuses);
}


?>