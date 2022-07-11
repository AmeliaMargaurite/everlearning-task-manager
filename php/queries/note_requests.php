<?php 
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);

$json = file_get_contents('php://input');
$jsonArray = json_decode($json, true);

['request' => $request] = $jsonArray;

 if ($request === 'delete_note') {
  delete_note();
} else if ($request === 'get_note_data') {
  get_note_data();
}

function delete_note() {  
  global $jsonArray;
  ['note_id' => $note_id,  'project_id' => $project_id] = $jsonArray;
  
  $conn = OpenConn();

  $stmt = $conn->prepare("DELETE FROM notes WHERE note_id = ?");
  $stmt->bind_param('i', $note_id);
  

  if ($stmt->execute() === TRUE) {
    unset($_SESSION['projects'][$project_id]->notes[$note_id]);
    echo json_encode("success");
    error_log('Sucessfully deleted note',0);
  } else {
    echo json_encode("Error: " . $request . "<br/>" . $conn->error);
    error_log('Failed to delete note: '. $request . "<br/>" . $conn->error,0);
  }
  $stmt->close();
  CloseConn($conn);

}

function get_note_data()  {
  global $jsonArray;
  ['note_id' => $note_id, 'project_id' => $project_id] = $jsonArray;
  $note = $_SESSION['projects'][$project_id]->notes[$note_id];
  echo json_encode($note);
}

?>