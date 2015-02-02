<?php
//print_r( $main_menu );
//print_r( menu_tree_all_data( 'main-menu' ) );
//render(menu_tree('main-menu'));
//print_r( menu_tree_data( 'main-menu' ) );

$mainMenu;

$mainMenuItems;
include_once( "libcommon.php" );
function getBredCrumbs ( $menu, &$bredCrumbHTML, $lvls, $level = 0 ) {
    global $base_url;

    $sample1 = "<a title=\"@TITLE@\" href=\"@HREF@\">@TITLE@</a>
        »\n";
    $i = 0;
    if( $level == 0 )
        $bredCrumbHTML .= "<div class=\"breadcrumb\"><a href=\"/\">Home</a>
        »\n";
    foreach ( $menu as &$item ) {
        if(  $lvls[ $level ] == $i  ) {
            $l = $item [ "link" ];
            $isSub = ( count ( $item [ "below" ] ) > 0 );

            $href = drupal_get_path_alias( $l[ "href" ] );
            $href = (($href == "<front>")?$base_url:($base_url . "/" . $href));

            $bredCrumbHTML .= str_replace( Array( "@HREF@", "@TITLE@"), Array( $href, $l[ "title" ]  ), $sample1 );

            if ( $isSub && ( $level < (count( $lvls ) - 1 ) ) )
                getBredCrumbs( $item [ "below" ], $bredCrumbHTML, $lvls, $level + 1 );
        }
        $i++;
    }
}

function eurobats_dropdownmenu_render( $menu,   // IN Drupal menu
            &$menuHTML,     // OUT HTML text
            &$menuItems,    // OUT JavaScript array
            $miSample,      // IN Menu item sample
            $miSepSample,   // IN Menu item separator sample
            $level = 0 ) {
    global $base_url;
    $i = 0;
    foreach ( $menu as &$item ) {
        $l = $item [ "link" ];
        $isSub = ( count ( $item [ "below" ] ) > 0 );
        $miID = (($level == 0)?"menuMainItemL0I$i":escapeString( $l[ "title" ] ));
        if ( $level == 0 ) {
            //$menuHTML .= "<table class=\"menuItem\"><tr><td id=\"$miID\" class=\"menuItem\">" .
            //        $l[ "title" ] .
            //        "</td></tr></table><div class=\"menuSeparator\"></div>";
            $menuHTML .= str_replace( "@ITEM_TITLE@", escapeString( $l[ "title" ] ), str_replace( "@ITEM_ID@",  $miID, $miSample ) ) . $miSepSample;
        }
// ---------------------------------------------

        $href = drupal_get_path_alias( $l[ "href" ] );
        //$href = ($isSub?"":(($href == "<front>")?$base_url:($base_url . "/" . $href)));
        $href = (($isSub && ($level == 0))?"":(($href == "<front>")?$base_url:($base_url . "/" . $href)));
        //$menuItems .= str_pad( "", ($level + 1) * 4 );//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $menuItems .= "[ \"$miID\", \"$href\", " . (!$isSub?"null ":"[\n");
// ---------------------------------------------
        if ( $isSub )
            eurobats_dropdownmenu_render( $item [ "below" ], $menuHTML, $menuItems, $miSample, $miSepSample, $level + 1 );
        //$menuItems .= ($isSub?str_pad( "", ($level + 1) * 4 ):"");//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $menuItems .= "],\n";
        $i++;
    }

    if ( $level == 0 )
        $menuItems = "[\n" . substr( $menuItems, 0, strlen( $menuItems ) - 2 ) . "\n]";
    else
        $menuItems = substr( $menuItems, 0, strlen( $menuItems ) - 2 ) . "]\n";

}

$miSample = "<table class=\"menuItem\"><tr><td id=\"@ITEM_ID@\" class=\"menuItem\">@ITEM_TITLE@</td></tr></table>";
$miSepSample = "";//"<div class=\"menuSeparator\"></div>";
eurobats_dropdownmenu_render ( menu_tree_all_data( 'main-menu' ), $mainMenu, $mainMenuItems, $miSample, $miSepSample );
?>

<?php
/*echo "<textarea>";
//print_r( $page['search'] );
echo "\n\n\n" . $mainMenuItems;
//echo "\n\n\n" . $mainMenuZItems;

//echo "\n\n\n" . path_to_theme();
//echo "\n\n\n" . $mainMenu;
//echo "\n\n\n" . $form['search_form'];
//print_r( $page );

if ( $logged_in )
    echo "logged_in\n";
if ( $is_admin )
    echo "is_admin";

echo "</textarea>";*/
$imsLayerSample = "<div id=\"@IMSCONT_ID@\" class=\"mainMenyDropCont\">\n" .
/*"    <div class=\"mainMenyDropCont_tc\">\n" .
"        <div class=\"mainMenyDropCont_tc_l\">\n" .
"        </div>\n" .
"        <div class=\"mainMenyDropCont_tc_r\">\n" .
"        </div>\n" .
"        <div class=\"mainMenyDropCont_tc_c\">\n" .
"        </div>\n" .
"    </div>\n" .*/
"    <div class=\"mainMenyDropCont_l\">\n" .
"        <div class=\"mainMenyDropCont_r\">\n" .
"            <div id=\"itemsDrop\" class=\"mainMenyDropCont_b\">\n" .
"                <div id=\"@IMS_ID@\" style=\"width : 100%; left: -2px; position : relative;\">\n" .
"                </div>\n" .
"            </div>\n" .
"        </div>\n" .
"    </div>\n" .
"\n" .
"    <div class=\"mainMenyDropCont_bc\">\n" .
"        <div class=\"mainMenyDropCont_bc_l\">\n" .
"        </div>\n" .
"        <div class=\"mainMenyDropCont_bc_r\">\n" .
"        </div>\n" .
"        <div class=\"mainMenyDropCont_bc_c\">\n" .
"        </div>\n" .
"    </div>\n" .
"</div>";

$miDropSample = "<table id=\"@ITEM_ID@\" class=\"menuItem\"><tr><td class=\"menuItem\" onclick=\"location.href = '@ITEM_HREF@'\">@ITEM_TITLE@</td></tr></table>";
/*"                    <table class=\"menuItem\"><tr><td class=\"menuItem\">Official Documents</td></tr></table>\n" .
"                    <table class=\"menuItem\"><tr><td class=\"menuItem\">Official Documents</td></tr></table>\n" .*/
?>



<script type="text/javascript">

mainMenu = new Menus( { Name : "mainMenu",
Items : <?php print $mainMenuItems; ?>,
<?php /*imsLayerSample : "<div id=\"@IMS_ID@\" class=\"mainMenyDropCont\"></div>",*/ ?>
imsLayerSample : <?php print "\"" . escapeString($imsLayerSample) . "\""; ?>,
imSample : <?php print "\"" . escapeString($miDropSample) . "\""; ?>,
imSepSample : <?php print "\"" . escapeString($miSepSample) . "\""; ?>,
dy : -8 });

mainMenu.writeLayers();
</script>

<div id="page_wrapper" style="overflow : visible;">
<?php
if ( $is_front ) {
?>
    <div id="bat_t"></div>
    <div id="bat_b"></div>
<?php
}
else {
?>
    <div id="bat_t" style="top : 344px;"></div>
    <div id="bat_b" style="top : 600px;"></div>
<?php
}
?>

    <div style="display : block; width : 50%;">
        <div style="float : right; margin-right : -495px; width : 990px;">




            <div id="headerDiv">
                <div class="logoDiv"></div> 
                <div class="textDiv">
					<h1 class = "logo"><?php print $site_name; ?></h1>
                    <h2 class = "logo">Agreement on the Conservation <br />of Populations
					of European Bats<?php /*print $site_slogan;*/ ?></h2>
                </div>
                <div class="menuDiv">
                   <b> &nbsp;|&nbsp;<a class="menuA" href="/sitemap">Sitemap</a>&nbsp;|&nbsp; </b>
                </div>
                <div class="searchDiv">
                    <?php print render($page['search']); ?>
                </div>
                <div class="logoutDiv">
                    <?php 
                    global $base_url;
                    if ( $logged_in && !$is_admin )
                        print "<a href=\"$base_url/user/logout\">Log out</a>";
                    ?>
                </div>
            </div>

            <div style="position : absolute; top : 280px;">

                <div id="mainMenuDiv">
                    <div class="leftSide"></div>
                    <div class="rightSide"></div>
                    <?php print $mainMenu; ?>

                    <?php /* <table id="searchBlock"><tr><td class="container">
                        <?php print render($page['search']); ?>
                    </td></tr></table> */ ?>

                </div>

    <script type="text/javascript">
    mainMenu.init();
    </script>

