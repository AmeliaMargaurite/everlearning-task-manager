<?php 
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);

$json = file_get_contents('php://input');

if ($json) {
$jsonArray = json_decode($json, true);
['request' => $request] = $jsonArray;

  switch($request) {
    case 'get_project_data': get_project_data(); break;
    case 'delete_project': delete_project(); break;
  }
}

function get_project_data()  {
  global $jsonArray;
  ['project_id' => $project_id] = $jsonArray;
  $project = $_SESSION['projects'][$project_id];
  echo json_encode($project);
}


// Delete from database

function delete_project() {
  global $jsonArray;
  ['project_id' => $project_id] = $jsonArray;

  $conn = OpenConn();

  $stmt = $conn->prepare("DELETE FROM projects WHERE project_id = ? && owner_id = ?");
  $stmt->bind_param('ii', $project_id, $_SESSION['user_id']);
  

  if ($stmt->execute() === TRUE) {
    unset($_SESSION['projects'][$project_id]);
    echo json_encode("success");
    error_log('Sucessfully deleted project',0);
  } else {
    echo json_encode("Error: <br/>" . $conn->error);
    error_log('Failed to delete project: <br/>' . $conn->error,0);
  }
  $stmt->close();
  CloseConn($conn);
}
