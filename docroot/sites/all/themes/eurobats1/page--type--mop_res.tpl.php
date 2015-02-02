<?php 
include( "inc/php/page_top.inc" );
getBredCrumbs( menu_tree_all_data( 'main-menu' ), $out, Array( 3, 1 ) );
$out = rtrim( $out, "\n\ »" );
$out .= "</div>";
?>

<div id="contentDivbt"></div>
<div id="contentiDiv">
    <?php // Выводим "хлебне крошки"  ?>
    <?php echo $out; ?>
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
