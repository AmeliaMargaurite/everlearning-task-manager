<?php 
include_once(__DIR__ . '/../../config.php');
include_once(DB_CONNECTION);
include_once(TASK_FUNCTIONS);
 
$json = file_get_contents('php://input');
$jsonObj = json_decode($json);
$jsonArray = json_decode($json, true);

['request' => $request] = $jsonArray;

switch($request) {
  case 'save_new_category': save_new_category(); break;
  case 'get_dropdown_categories': get_dropdown_categories(); break;
  case 'get_category_data': get_category_data(); break;
  case 'update_category_data': update_category_data(); break;
  case 'delete_category': delete_category(); break;
  case 'get_categories' : get_categories(); break;
}


function get_dropdown_categories() {
  global $jsonArray;
  ['project_id' => $project_id, 'task_id' => $task_id] = $jsonArray;
  
  $category_results = $_SESSION['projects'][$project_id]->categories;
  $task_category_id = $_SESSION['projects'][$project_id]->tasks[$task_id]->category_id;

  $categories = array();
  if ($category_results) {
  foreach($category_results as $category) {
    $category->this_task = false;
    if ($task_category_id === $category->category_id) {
      $category->this_task = true;
    }
    $categories[$category->category_id] = $category;
  }}
   echo json_encode($categories);
}


function get_category_data() {
  global $jsonArray;
  ['project_id' => $project_id, 
  'category_id' => $category_id] = $jsonArray;

  $category = $_SESSION['projects'][$project_id]->categories[$category_id];
  echo json_encode($category); 
}


function save_new_category() {
  global $jsonArray;

  ['project_id' => $project_id, 
  'category_name' => $category_name, 
  'color' => $color] = $jsonArray;

  $conn = OpenConn();

  $stmt = $conn->prepare("INSERT INTO categories(name, project_id, color) VALUES(?, ?, ?)");
  $stmt->bind_param('sis', $category_name, $project_id, $color);

  
  if ($stmt->execute() === TRUE) {
    $newCategory_id = getCategoryId($category_name, $project_id);
    // $updates = array('project_id' => $project_id, 'category_name'=>$category_name, 'color' =>$color);
    getProjectsCategories($project_id);
    echo $newCategory_id;
    error_log('Successfully added new category', 0);
  } else {
    echo "Error: " . $stmt . "<br/>" . $conn->error;
    error_log("Error: " . $stmt . "<br/>" . $conn->error, 0);
  }

  $stmt->close();
  CloseConn($conn); 
}

function update_category_data() {
  global $jsonArray;

  ['project_id' => $project_id,
  'category_id' => $category_id ,
  'category_name' => $name,
  'color' => $color] = $jsonArray;

  $conn = OpenConn();

  $stmt = $conn->prepare("UPDATE categories SET name = ?, color = ? WHERE project_id = ? && category_id = ? ");
  $stmt->bind_param('ssii', $name, $color, $project_id, $category_id);
  
  if ($stmt->execute()) {
    $updates = array('name'=>$name, 'color'=>$color);
    foreach($updates as $key => $prop) {
      $_SESSION['projects'][$project_id]->categories[$category_id]->$key = $prop;
    }

    error_log('Category: ' . $category_id .' on Project: '. $project_id . ' successfully updated');
  } else {
    error_log('Category: ' . $category_id .' on Project: '. $project_id . ' failed. Rethink your career choice');
  }

  $stmt->close();
  CloseConn($conn);
}

function delete_category() {
  global $jsonArray;

  ['project_id' => $project_id,
  'category_id' => $category_id ] = $jsonArray;

  $conn = OpenConn();
  $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ? && project_id = ?");
  $stmt->bind_param('ii', $category_id, $project_id);

  if ($stmt->execute()) {
    echo json_encode('success');
    unset($_SESSION['projects'][$project_id]->categories[$category_id]);

    error_log('Category with category_id: '. $category_id . ' and project_id: ' . $project_id . ' successfully deleted');
  } else {
    error_log("Error: " . $stmt . "<br/>" . $conn->error, 0);
  }

  $stmt->close();
  CloseConn($conn);
}

function get_categories() {
   global $jsonArray;

  ['project_id' => $project_id ] = $jsonArray;
  $categories = $_SESSION['projects'][$project_id]->categories;
  echo json_encode($categories);
}
?>