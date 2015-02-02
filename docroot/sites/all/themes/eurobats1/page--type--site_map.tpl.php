<?php 
include( "inc/php/page_top.inc" );
?>

<div id="contentDivbt"></div>
<div id="contentiDiv">
    <?php // Выводим "хлебне крошки"  ?>
    <div class="breadcrumb">
        <a href="/">Home</a>
    </div>
    <?php print $messages; // Выводим сообщения системы  ?>
    <!-- Заголовок с суффиксом и преффиксом -->
    <?php print render($title_suffix); ?>
    <?php if($title): ?> <h1 style="margin-left : 15px;"><?php print $title; ?></h1><?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php print render($tabs); // Выводим табы  ?> 
    <?php print render($page['content']); // Выводим регион "контент"  ?>


</div>
<div id="contentDivbb"></div>


<?php 
include( "inc/php/page_bottom.inc" );
?>
