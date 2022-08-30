<div>
  <?php
  if ($projects) :
    foreach ($projects as $project) :
      if ($project && $project->tasks) :
        if ($project->tasks) :
          $archivedStatus = getStatusIdFromName('archived');
          $completedStatus = getStatusIdFromName('completed');
          $calendar_tasks = array();

          $today = date('Y-m-d');
          foreach ($project->tasks as $task) {
            if (
              $task->days_allocated_to
              && $task->days_allocated_to === $today
              && $task->status !== $archivedStatus
              && $task->status !== $completedStatus
            ) {

              $queryArray = array(
                'project_id' => $project->project_id,
                'task_id' => $task->task_id
              );
              $queryStr = http_build_query($queryArray);
              $calendar_tasks[] = array(
                'task' => $task,
                'link' =>  HOME . '/project?' . $queryStr
              );
            }
          }
          if (!empty($calendar_tasks)) : ?>
            <p class="project__title"><?= $project->name ?></p>
            <ul>
              <?php foreach ($calendar_tasks as $task) : ?>
                <li>
                  <a href=" <?= $task['link'] ?>">
                    <?= $task['task']->name ?>
                  </a>
                </li>
              <?php
              endforeach;
              ?>
            </ul>
  <?php
          endif;
        endif;
      endif;
    endforeach;
  endif;
  ?>
</div>