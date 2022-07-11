<?php 
// Helper functions 

/**
 * @param $status_name
 * @return int
 */

function getStatusIdFromName($status_name):int {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT status_id FROM statuses WHERE name = ?");
  $stmt->bind_param('s', $status_name);
  $stmt->execute();

  $result = $stmt->get_result();
  $stmt->close();
  CloseConn($conn);
  
  return $result->fetch_assoc()['status_id'];

}

/**
 * @param $status_id
 * @return string
 */

function getStatusNameFromId($status_id): string {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT name FROM statuses WHERE status_id = ?");
  $stmt->bind_param("i", $status_id);
  $stmt->execute();

  $result = $stmt->get_result();
  $stmt->close();
  CloseConn($conn);
  return $result->fetch_assoc()['name'];

}

/**
 * @param $priority_name
 * @return int
 */
  
function getPriorityIdFromName($priority_name): int {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT priority_id FROM priorities WHERE name = ?");
  $stmt->bind_param('s', $priority_name);
  $stmt->execute();

  $result = $stmt->get_result();
  $stmt->close();  
  CloseConn($conn);
  
  return $result->fetch_assoc()['priority_id'];
}

/**
 * @param $priority_id
 * @return string
 */

function getPriorityNameFromId($priority_id):string {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT name FROM priorities WHERE priority_id = ?");
  $stmt->bind_param("i", $priority_id);
  $stmt->execute();

  $result = $stmt->get_result();
  $stmt->close();  
  CloseConn($conn);
  
  return $result->fetch_assoc()['name'];
}

/**
 * Used for newly added category
 * @param $name
 * @param $project_id
 * @return int
 */

function getCategoryId($name, $project_id):int {
  $conn = OpenConn();
  $stmt = $conn->prepare("SELECT category_id 
    FROM `categories` 
    WHERE name = ? 
    && project_id = ?");

  $stmt->bind_param('si', $name, $project_id);
  $stmt->execute(); 
  
  $result = $stmt->get_result();
  $stmt->close();  
  CloseConn($conn);
  
  return $result->fetch_assoc()['category_id'];
}

?>