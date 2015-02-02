<?php 
@ $mode = $_GET[ "mode" ];
if ( ( $mode != "print" ) && ( $mode != "lightframe" ) ) {
    include( "inc/php/page_top.inc" );
    getBredCrumbs( menu_tree_all_data( 'main-menu' ), $out, Array( 2, 2 ) );
    $out = rtrim( $out, "\n\ »" );
    $out .= "</div>";
}
?>

<?php if ( ( $mode != "print" ) && ( $mode != "lightframe" ) ) { ?>
<div id="contentDivbt"></div>
<div id="contentiDiv">
    <?php echo $out; ?>
    <?php //print $breadcrumb; // Выводим "Выводим "хлебне крошки"  ?>
    <?php print $messages; // Выводим сообщения системы  ?>
 <?php } ?>
    <!-- Заголовок с суффиксом и преффиксом -->
    <?php print render($title_suffix); ?>
    <?php if($title): ?> <i><h1 style=" font-size : 29px; margin-left : 15px; margin-top : 5px; ">
    <?php print $title; ?></h1></i><?php endif; ?>
    <?php print render($title_suffix); ?>
<?php if ( ( $mode != "print" ) && ( $mode != "lightframe" ) ) { ?>
    <?php print render($tabs); // Выводим табы  ?> 
    <?php } ?>
    <?php print render($page['content']); // Выводим регион "контент"  ?>
<?php if ( ( $mode != "print" ) && ( $mode != "lightframe" ) ) { ?>
</div>
<div id="contentDivbb"></div>
<?php } ?>


<?php 
if ( ( $mode != "print" ) && ( $mode != "lightframe" ) )
    include( "inc/php/page_bottom.inc" );
?>