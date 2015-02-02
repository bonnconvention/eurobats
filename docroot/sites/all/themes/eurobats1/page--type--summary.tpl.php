<?php 
@ $mode = $_GET[ "mode" ];
if ( ( $mode != "print" ) && ( $mode != "lightframe" ) ) {
    include( "inc/php/page_top.inc" );
    getBredCrumbs( menu_tree_all_data( 'main-menu' ), $out, Array( 4, 0 ) );
}
?>

<?php if ( ( $mode != "print" ) && ( $mode != "lightframe" ) ) { ?>
<div id="contentDivbt"></div>
<div id="contentiDiv">
    <?php // Выводим "хлебне крошки"  
    echo $out; ?>
        <a title="Summaries of conducted projects" href="/summaries_of_conducted_projects">Summaries of conducted projects</a>
    </div>
    <?php print $messages; // Выводим сообщения системы  ?>
<?php } ?>
    <!-- Заголовок с суффиксом и преффиксом -->
    <?php print render($title_suffix); ?>
    <?php if($title): ?> <div style=" height : 7px; width : 100%;"></div> <h2 style=" margin-left : 15px;"><strong><?php print $title; ?></strong></h2><?php endif; ?>
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