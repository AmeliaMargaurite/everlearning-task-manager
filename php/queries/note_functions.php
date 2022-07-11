<?php
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
 


if (isset($_POST['save_new_note'])) {
  save_new_note();
}else if (isset($_POST['edit_note'])) {
  edit_note();
}

function save_new_note() {
  $note = $_POST['note'];
  $project_id = $_POST['save_new_note'];
  $date_created = date('Y-m-d');

  $conn = OpenConn();

  $stmt = $conn->prepare("INSERT INTO notes(note, date_created, project_id) VALUES(?,?,?)");
  $stmt->bind_param('sss', $note, $date_created, $project_id);

  if ($stmt->execute() === TRUE) {
    getProjectsNotes($project_id);
    error_log('New note added successfully', 0);
  } else {
    error_log("Error: " . $sql . "<br/>" . $conn->error, 0);
  }
  $stmt->close();
  CloseConn($conn);
  header('Location: ../../project?project_id=' . $project_id);
}

function edit_note() {
  $note_id = $_POST['note_id']; 
  $project_id = $_POST['edit_note'];
  $note = $_POST['note'];
  $conn = OpenConn();

  $stmt = $conn->prepare("UPDATE notes SET note = ? WHERE note_id = ?");
  $stmt->bind_param('si', $note, $note_id);
  
  if ($stmt->execute()) {
    unset($_SESSION['projects'][$project_id]->notes[$note_id]->note);
    $_SESSION['projects'][$project_id]->notes[$note_id]->note = $note;
    error_log("Note successfully updated.\nNote: " . $note . '\nNote_id: ' . $note_id);
  } else error_log("Error: " . $sql . "<br/>" . $conn->error, 0);

  CloseConn($conn);
  header('Location: ../../project?project_id=' . $project_id); 

}

?>