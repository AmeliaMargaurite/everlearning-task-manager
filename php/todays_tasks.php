<div>
  <?php 
  if ($projects) {
    foreach ($projects as $project) {
      if ($project && $project->tasks) { ?>
        
        <?php if ($project->tasks) {
          print_r($projects->tasks);
          $todays_tasks = array();
          foreach ($project->tasks as $task) {
          if ($task->todays_task == 1) {
          $queryArray = array('project_id'=>$project->project_id, 'task_id'=>$task->task_id);
          $queryStr = http_build_query($queryArray); 
          $todays_tasks[] = array('task' => $task, 'link' =>  HOME . '/project?' . $queryStr)
          ?>
           
           
          <?php }}
          if (!empty($todays_tasks)) { ?>
          <h5><?= $project->name ?></h5>
        <?php } ?>
        <ul>

            <?php foreach($todays_tasks as $task) { ?>
               <li>
              <a href="<?=$task['link']?>" >
                <?= $task['task']->name ?>
              </a>
            </li>  
           <?php }
          }
        } ?>
        </ul>
        
       <?php }
      }
   
  ?>
</div>