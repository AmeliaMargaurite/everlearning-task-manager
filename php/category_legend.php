<?php 
include_once './config.php';
include_once(DB_CONNECTION);
include_once(TASK_REQUESTS);
?>

<section class="category_legend--wrapper">

  <?php 
    // $categoryData = get_category_legend_data($project_id);
    $categoryData = $_SESSION['projects'][$project_id]->categories;
    if ($categoryData) {
      foreach ($categoryData as $category) { ?>
        <div class="category--wrapper" >
          <div class="category" category_id="<?=$category->category_id ?>">
            <?= $category->name ?> 
            <span class="category_color" color="<?= $category->color ?>"></span>
          </div>
        </div>
     <?php }
    }
  ?>
  <script>
    const markers = document.querySelectorAll('.category_color');
    
    function openCustomMenu(e, category, category_id) {
      e.preventDefault();
      e.stopPropagation();
      category.removeEventListener('click', () => openCustomMenu(e, category, category_id));
      const contextMenu = document.createElement('category-context-menu');
      contextMenu.setAttribute('category_id', category_id);
      category.parentElement.append(contextMenu);
      
    }

    for (const marker of markers) {
      const color = marker.getAttribute('color');
      marker.style.background = color;
      const category = marker.parentElement;
      const category_id = category.getAttribute('category_id');
      category.addEventListener('click', (e) => openCustomMenu(e, category,category_id))
    }

    // const editCategory
    </script>
</section>