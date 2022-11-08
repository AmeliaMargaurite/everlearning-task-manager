<?php
include_once './config.php';
require_login();
$title = "Projects";
include_once(PAGE_START);
include_once(HEADER);
include_once(DB_CONNECTION);
include_once(PROJECT_FUNCTIONS);
include_once(RENDER_COMPONENTS);

if ((!isset($_SESSION['projects']) || $_SESSION['projects'] == '') && isset($_SESSION['users_id'])) {

    getUsersProjects($_SESSION['users_id']);
}
$projects = isset($_SESSION['projects']) ? $_SESSION['projects'] : [];

?>
<div class="projects--page__wrapper">
    <!-- Header of Page, incl light/dark mode switch -->
    <div class="projects__heading">
        <span>
            <h1>Projects</h1>

        </span>
        <?php include_once(DASHBOARD_LINK) ?>

    </div>
    <div class="projects__wrapper">

        <div class="calendar-link">
            <span class="title">
                <a href="<?= HOME . '/calendar-view' ?>">Calendar View </a>
                <span class="icon calendar large"></span>
            </span>
            <div class="tasks">
                <h5>Tasks allocated to today:</h5>
                <?php include_once(CALENDAR_TASKS); ?>
            </div>
        </div>

        <div class="overview__wrapper">
            <!-- List all Project Tiles -->

            <div class="project_tiles">
                <?php
                foreach ($projects as $project) {

                    // Statuses
                    $incomplete = 0;
                    $current = 0;
                    $completed = 0;
                    renderProjectTiles($project);
                }
                ?>
            </div>
            <button class="btn add-new-project special" onclick="openDialog('add-new-project-dialog')">Add new project
                <span class="icon plus"></span>
            </button>
        </div>
    </div>
</div>

<?php
include_once(PAGE_END);
?>