<?php

class Calendar {
  // https://startutorial.com/view/how-to-build-a-web-calendar-in-php

  /**
   * Constructor
   */

  public function __construct() {
    $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
  }

  /* Property */
  private $dayLabels = array('Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat', 'Sun');
  private $currentYear = 0;
  private $currentMonth = 0;
  private $currentDay = 0;
  private $currentDate = null;
  private $daysInMonth = 0;
  private $naviHref = null;

  /* Public */

  /**
   * print out calendar
   */

  public function show() {
    $year = null;
    $month = null;
    
    if (null === $year && isset($_GET['year'])) {
      $year = $_GET['year'];
    } else if (null === $year) {
      $year = date('Y', time());
    }
  
    if (null === $month && isset($_GET['month'])) {
      $month = $_GET['month'];
    } else if (null === $month) {
      $month = date('m', time());
    }

    $this->currentYear = $year;
    $this->currentMonth = $month;
    $this->daysInMonth = $this->_daysInMonth($month, $year);

    $content = "
      <div id='calendar' class='calendar'>
        <div class='box'>" .
        $this->_createNavi()
      ."</div>
        <div class='box-content'>
          <ul class='label'>".
            $this->_createLabels()
        ."</ul>
        <ul class='dates'>";
        $weeksInMonth = $this->_weeksInMonth($month, $year);
        
        // Create weeks in a month
        for ($w = 0; $w < $weeksInMonth; $w++) {
          // Create days in week
          for ($d = 1; $d <= 7; $d++) {
            $content.= $this->_showDay($w * 7 + $d);
          }
        }

        $content.="</ul>
        </div>
      </div>";

  return $content;
}

/* Private /*

/**
 * Create the li element for ul
 */

 private function _showDay($cellNumber) {
  if ($this->currentDay === 0) {
    $firstDayOfTheWeek = date('N', strtotime($this->currentYear . '-'. $this->currentMonth . '-01'));
    if(intval($cellNumber) === intval($firstDayOfTheWeek)) {
      $this->currentDay = 1;
    }
  }

  if ($this->currentDay !== 0 && $this->currentDay <= $this->daysInMonth) {
    $this->currentDate = date('d-m-Y', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . $this->currentDay));
    $cellContent = $this->currentDay;
    $this->currentDay++;
  } else {
    $this->currentDate = null;
    $cellContent = null;
  }

  $weekMarkersClass = ($cellNumber % 7 == 1 ? 'start' :( $cellNumber % 7 == 0  || $cellNumber % 7 === 6  ? 'end': ''));
  $maskClass = $cellNumber == null ? 'mask': '';
  $todayClass = $this->currentDate === date('d-m-Y', time()) ? 'today' : '';

  $content = "<li id='li-$this->currentDate' class='$weekMarkersClass $maskClass $todayClass'><span class='cell-number'>$cellContent</span>";
  
    foreach($_SESSION['projects'] as $project) {
      if ($project->tasks) {
        foreach ($project->tasks as $task) {
          
          if ($task->days_allocated_to) {
            $days_allocated_to_reformatted = date(
              'd-m-Y', 
              strtotime($task->days_allocated_to)
            );

            if ($this->currentDate == $days_allocated_to_reformatted) {
              $className = $task->status_id === getStatusIdFromName('completed') || $task->status_id === getStatusIdFromName('archived') ? 'completed' : '';
              $content .= "<span class='task $className' project_id='$project->project_id' task_id='$task->task_id' onclick='openDialog(\"edit-task-dialog\",{task_id:\"$task->task_id\", project_id: \"$project->project_id\"})'>
              $task->name
              </span> ";
            }
          }
        }
      }
    }
  
  $content .= "</li>";
  return $content;
 }

 /**
  * Create navigation
  */

  private function _createNavi() {
    $nextMonth = $this->currentMonth === 12 ? 1 : intval($this->currentMonth) + 1;
    $nextYear = $this->currentMonth === 12 ? intval($this->currentYear) + 1 : $this->currentYear;
    $preMonth = $this->currentMonth === 1 ? 12 : intval($this->currentMonth) - 1;
    $preYear = $this->currentMonth === 1 ? intval($this->currentYear) - 1 : $this->currentYear;

    $prevHref = $this->naviHref . '?month=' . sprintf('%02d', $preMonth) . '&year=' . $preYear;
    $nextHref = $this->naviHref . '?month=' . sprintf('%02d', $nextMonth) . '&year=' . $nextYear;
    $displayDate = date('F Y', strtotime($this->currentYear . '-' . $this->currentMonth . '-01' ));
    return "
      <div class='header'>
        <a class='prev' href='$prevHref'>Prev</a>
          <span class='title'>$displayDate</span>
        <a class='next' href='$nextHref'>Next</a>
      </div>
    ";
  }

  /**
   * Create calendar week labels
   */

   private function _createLabels() {
    $content = '';

    foreach($this->dayLabels as $index => $label) {
      $class = $label === 6 ? 'end title' : $label === 0 ? 'start title' : 'title';
      $content .= "<li class='$class'>$label</li>";
    }

    return $content;
  }

  /**
   * Calculate number of weeks in a particular month
   */

   private function _weeksInMonth($month = null, $year = null) {
    if (null === $year) {
      $year = date('Y', time());
    }

    if (null === $month) {
      $month = date('m', time());
    }

    $daysInMonths = $this->_daysInMonth($month, $year);

    $numOfWeeks = ($daysInMonths % 7 === 0 ? 0 : 1) + intval($daysInMonths / 7);
    $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));
    $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

    if ($monthEndingDay < $monthStartDay) {
      $numOfWeeks++;
    }

    return $numOfWeeks;
   }

   /**
    *  Calculate number of days in a particular month
    */

    private function _daysInMonth($month = null, $year = null) {
      if (null === $year) {
        $year = date('Y', time());
      }

      if (null === $month) {
        $month = date('m', time());
      }

      return date('t', strtotime($year . '-' . $month . '-01'));
    }
}
?>