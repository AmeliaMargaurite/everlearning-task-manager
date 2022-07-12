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
    $name = $_POST['name'];
    $description = $_POST['description'];
    $project_id = $_POST['edit_project'];

    $conn = OpenConn();

    $stmt = $conn->prepare("UPDATE projects SET name = ?, description = ? WHERE project_id = ?");
    $stmt->bind_param('ssi', $name, $description, $project_id);

    if ($stmt->execute() === TRUE) {
        $updates = array('name' => $name, 'description'=>$description);
        foreach($updates as $key => $prop) {
            $_SESSION['projects'][$project_id]->$key = $prop;
        }
        error_log("New project added successfully", 0);
    } else {
        error_log("Error: " . $request . "<br/>" . $conn->error, 0);
    }

    CloseConn($conn);

    header('Location: ../../project?project_id=' . $project_id); 

}



?>

