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
    <?php $arr = $page['content'][ 'system_main' ][ 'nodes' ];
    reset( $arr );
    $arr = current( $arr );
    $arr = $arr[ '#node' ];
    @ $doc_tlt = $arr->field_doc_title[ $arr->language ][ 0 ][ 'safe_value' ]; ?>
    <?php if($title): ?> <h1 style="margin-left : 15px;"><?php print $title ."</h1>". "<h3 style=\"margin-left : 15px;\">" .$doc_tlt.""; ?></h3><?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php print render($tabs); // Выводим табы  ?> 
    <?php print render($page['content']); // Выводим регион "контент"  ?>


</div>
<div id="contentDivbb"></div>


<?php 
include( "inc/php/page_bottom.inc" );
?>
