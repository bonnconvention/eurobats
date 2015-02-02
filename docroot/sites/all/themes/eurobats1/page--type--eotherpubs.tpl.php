<?php 
include( "inc/php/page_top.inc" );
getBredCrumbs( menu_tree_all_data( 'main-menu' ), $out, Array( 5, 3 ) );
$out = rtrim( $out, "\n\ »" );
$out .= "</div>";
?>

<div id="contentDivbt"></div>
<div id="contentiDiv">
    <?php echo $out; ?>

    <?php //print $breadcrumb; // Выводим "Выводим "хлебне крошки"  ?>
    <?php print $messages; // Выводим сообщения системы  ?>
    <!-- Заголовок с суффиксом и преффиксом -->
    <?php print render($title_suffix); ?>
    <?php if($title): ?> <h1 style="margin-left : 15px;"><?php print $title; ?></h1><?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php print render($tabs); // Выводим табы  ?> 
    <?php print render($page['content']); // Выводим регион "контент"  ?>
    <?php 
        //echo "<textarea style=\"height : 200px; width : 100%;\">";
        //print render($tabs);
        //echo "</textarea>";
    ?>

</div>
<div id="contentDivbb"></div>


<?php 
include( "inc/php/page_bottom.inc" );
?>
