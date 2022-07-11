<?php 
include_once './config.php';
require_login();
$title = "Projects";
include_once(PAGE_START); 

?>

<?php 
include_once(HEADER) ;
include_once(DB_CONNECTION);
include_once(PROJECT_FUNCTIONS);
include_once('./renderComponents.php');

if ($_SESSION['projects'] == '' || !isset($_SESSION['projects'])) {
    getUsersProjects($_SESSION['users_id']);
}
$projects = $_SESSION['projects'];
echo getenv('ENVIRONMENT');
?>
<div class="projects--page__wrapper">
<!-- Header of Page, incl light/dark mode switch -->
    <div class="projects__heading">
        <span>
            <h1>Projects</h1>
            <add-new-project-button></add-new-project-button>
        </span>
           <?php include_once(DASHBOARD_LINK) ?>

    </div>
    <div class="projects__wrapper">
        <div class="todays-tasks">
            <div class="todays-tasks__title">
                <h3>Todays Tasks</h1><div class="icon edit" onclick="editTodaysTasks()"></div>
            </div>
            <?php include_once(TODAYS_TASKS); ?>
        </div>
        
    <!-- Todays Tasks -->
        <div class="overview__wrapper">
        <!-- List all Project Tiles -->
        
            <div class="project_tiles">
            <?php 
                foreach($projects as $project) {
                    // Statuses
                    $incomplete = 0;
                    $current = 0;
                    $completed = 0;
                    renderProjectTiles($project);
                }
            ?>
            </div>
        </div>
    </div>
</div>

   
<?php 
include_once(PAGE_END); 
?>