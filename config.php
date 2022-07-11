<?php 
session_start();
ini_set('display_errors', 1);
ini_set('track_errors', 1);
ini_set('html_errors', 1);

define('IS_LIVE', getenv("ENVIRONMENT") === 'production');
$local = '/task-manager';
define('CONFIG_PATH', IS_LIVE ? realpath(__DIR__ . '../.configs/config.ini') : realpath(__DIR__ . '../../.configs/config.ini'));
define('CONNECTION_TYPE', IS_LIVE ? 'live' : 'local');
define('ROOTPATH', __DIR__);
define('HOME', IS_LIVE ? '' : $local);

define('HOME_URL', IS_LIVE ? '/' : '/task-manager/'); // needs to be '/' when live, '/task-manager/' when local

include_once(ROOTPATH . '/libs/helpers.php');

define('DB_CONNECTION', ROOTPATH . '/php/db_connection.php');
define('DASHBOARD_LINK', ROOTPATH . '/php/dashboard_link.php');
// UI
define('HEADER', ROOTPATH . '/php/header.php');
define('PAGE_START', ROOTPATH . '/php/page_start.php');
define('PAGE_END', ROOTPATH . '/php/page_end.php');
define('TODAYS_TASKS', ROOTPATH . '/php/todays_tasks.php');

// QUERIES
define('QUERIES', ROOTPATH . '/php/queries/');

  // tasks
define('TASK_FUNCTIONS', QUERIES . '/task_functions.php');
define('TASK_REQUESTS', QUERIES . '/task_requests.php');

  // projects
define('PROJECT_FUNCTIONS', QUERIES . '/project_functions.php');
define('PROJECT_REQUESTS', QUERIES . '/project_requests.php');

  // notes
define('NOTE_FUNCTIONS', QUERIES . '/note_functions.php');
define('NOTE_REQUESTS', QUERIES . '/note_requests.php');

  // categories
define('CATEGORY_REQUESTS', QUERIES . '/category_requests.php');

define('RENDER_COMPONENTS', ROOTPATH . '/php/renderComponents.php');

include_once(ROOTPATH . '/php/query_helpers.php');
include_once(ROOTPATH . '/libs/flash.php');
include_once(ROOTPATH . '/libs/sanitization.php');
include_once(ROOTPATH . '/libs/validation.php');
include_once(ROOTPATH . '/libs/filter.php');
include_once(ROOTPATH . '/libs/auth.php');
include_once(ROOTPATH . '/php/get_data.php');

class Project {
  public $name;
  public $description;
  public $project_id;
  public $tasks;
  public $notes;
  public $statues;
}

class Task {
  public $task_id;
  public $name;
  public $description;
  public $project_id;
  public $status_id;
  public $date_created;
  public $last_modified;
  public $date_complete;
  public $date_archived;
  public $due_date;
  public $priority_id;
  public $category_id;
  public $todays_task;
}

class Note {
  public $note_id;
  public $note;
}

class Category {
  public $name;
  public $value;
  public $category_id;
  public $color;
  public $this_task;
}

class Status {
  public $name;
  public $status_id;
}
?>