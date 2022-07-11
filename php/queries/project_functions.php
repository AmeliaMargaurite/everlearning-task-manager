<?php
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
include_once(TASK_FUNCTIONS);

if (isset($_POST['save_new_project'])) {
    saveNewProject();
} else if (isset($_POST['edit_project'])) {
    editProject();
}

function saveNewProject() {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date_created = date('Y-m-d');
    $user_id = $_SESSION['user_id'];
    $conn = OpenConn();

    $stmt = $conn->prepare("INSERT INTO projects(name, description, date_created, owner_id) VALUES(?,?,?,?)");
    $stmt->bind_param('sssi', $name, $description, $date_created, $user_id);
    if ($stmt->execute() === TRUE) {
        getUsersProjects($user_id);
        error_log("New project added successfully", 0);
    } else {
        error_log("Error: " . $request . "<br/>" . $conn->error, 0);
    }

    CloseConn($conn);
    header('Location: ../../index.php'); 
}

function editProject() {

}



?>

