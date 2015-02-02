<?php
//<div id="teazerDiv"></div> 
checkIE();


$query = new EntityFieldQuery();
$query->entityCondition('entity_type', 'node');
$query->propertyCondition('status', 1);
$query->propertyCondition('type', 'bat_species');
$result = $query->execute();

$rowCount = count( $result[ "node" ] );
$rIndexes = Array();
$keys = Array();
$maxPic = ( ($rowCount < 10)?$rowCount:10 );
$i = 0;
while( $i < $maxPic ) {
    $index = rand( 0, ($rowCount - 1) );
    $f = 0;
    for ( $j = 0; $j < $i; $j++ )
        if ( $index == $rIndexes[ $j ] ) {
            $f = 1;
            break;
        }
    if ( $f == 1 ) continue;
    $rIndexes[ $i ] = $index;
    //echo "#" . $index . "#";
    $i++;
}


$ak = array_keys( $result['node'] );
for ( $i = 0; $i < count( $rIndexes ); $i++ ) {
    $keys[ $i ] = $ak[ $rIndexes[ $i ] ];
}
$nodes = node_load_multiple( $keys );
//echo count( $nodes );
//$nodes1 = Array();
$i = 0; 
$jsArray = "[\n";
foreach ($nodes as $row) {
    $i++;
    //echo "$i  #  $maxPic<br />";
    $lang = $row->language;
    if ( count( $row->field_bat_image ) == 0 ) {
        $maxPic--;
        continue;
    }
    $author = ( ( count( $row->field_author ) == 0 )?"":"&nbsp;&nbsp;Photographer : " . $row->field_author[ $lang ][ 0 ][ "safe_value" ] );
    $jsArray .= "[ \"$directory/inc/php/gallery/slideresize.php?p0=246&p1=" .
        file_create_url( $row->field_bat_image[ $lang ][ 0 ][ "uri" ] ) . "\", \"" .
        drupal_get_path_alias( "node/" . $row->vid ) . "\", \"<center><strong><i>" .
        $row->title . "</i></strong><br />" . $author . "</center>\" ]" . ",\n";// (( $i == $rowCount )?"":",") . "\n";
    //$nodes1[ $i++ ] = Array(
    //    file_create_url( $row->field_bat_image[ $lang ][ 0 ][ "uri" ] ),
    //    $row->field_author[ $lang ][ 0 ][ "safe_value" ],
    //    $row->field_description[ $lang ][ 0 ][ "safe_value" ] );
}
$jsArray = substr( $jsArray, 0, strlen( $jsArray ) - 2 ) . "\n";
$jsArray .= "]\n";

?>
<div style="height : 20px; overflow : hidden; width : 100%">
</div>

<div id="galleryContainer">
    <div class="leftBtn"><div class="leftBtnInner" id="galleryLeftBtn"></div></div>
    <div class="rightBtn"><div class="rightBtnInner" id="galleryRightBtn"></div></div>
</div>

<div style="height : 25px; overflow : hidden; width : 100%">
</div>

<script type="text/javascript">
var gallery = new euroBatsGallery( {
// ------ Container
        mainCont    : "galleryContainer",
// ------ Content
        pathToImg   : "/sites/default/files/images/gallery/0/",
//                      "/sites/default/files/images/batnight.gif
        imgFolder   : "slides/",
        imgs        : <?php echo $jsArray; ?>/*[
                        ["1.jpg", "", "Name : pic1<br />Author : Author1"],
                        ["2.jpg", "", "Name : pic2<br />Author : Author2"],
                        ["3.jpg", "", "Name : pic3<br />Author : Author3"],
                        ["4.jpg", "", "Name : pic4<br />Author : Author4"],
                        ["5.jpg", "", "Name : pic5<br />Author : Author5"]
                      ]*/,
// ------ Inner classes
        picDivContClass     : "picDivCont",
        picDivMsgClass      : "picDivMsg",
        picDivHoverClass    : "picDivHover",
// ------ Animation setup
        picResize           : 1.1,
        minOpacity          : 0,
        maxOpacity          : 0.5,
        stepScrollTimeout   : 20,
        stepBtnTimeout      : 20,
        stepScrollDx        : 3,
        stepBtnDx           : 8,
// ------ Scrolling setup
        isScroll            : true,
        scrollTimeout       : 3000
        });

gallery.init();

</script>

<div id="contentDiv">
<?php 
//echo "<textarea style=\"height : 200px; width : 100%\">";
//echo  count( $result[ "node" ] );
//print_r( $rIndexes );
//print_r( $keys );
//echo $jsArray;
//print_r( $nodes );
//print_r( $result );
//echo "</textarea>";
?>

    <div id="newsEventsColumn">
<?php 
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'node');
    $query->propertyCondition('status', 1);
    $query->propertyCondition('type', 'front_page_editable_area');
    $result = $query->execute();
    $keys = array_keys( $result[ "node" ] );
    $nodes = node_load_multiple( $keys );

    $node = $nodes[ $keys[ 0 ] ];
    $lang = $node->language;

    $fld_array = $node->field_bat_news_title;
    $bat_news = ( (count( $fld_array ) == 0) ? "BAT NEWS" : $fld_array[ $lang ][ 0 ][ "safe_value" ] );

    $fld_array = $node->field_bat_events_title;
    $bat_events = ( (count( $fld_array ) == 0) ? "BAT EVENTS" : $fld_array[ $lang ][ 0 ][ "safe_value" ] );

    $fld_array = $node->field_ibn_title;
    $ibn = ( (count( $fld_array ) == 0) ? "International Bat Night" : $fld_array[ $lang ][ 0 ][ "safe_value" ] );

    $fld_array = $node->field_quicklinks_title;
    $quicklinks = ( (count( $fld_array ) == 0) ? "Quicklinks" : $fld_array[ $lang ][ 0 ][ "safe_value" ] );


?>
        <h1 class="headerTxt"><?php echo $bat_news; ?></h1>
        <div class="stripe"></div>
        <div style="margin-bottom : 30px; margin-left : 25px; margin-right : 20px; margin-top : 20px;">
            <?php print render($page['firstpagenews']); ?>

        </div>
		<h1 class="headerTxt"><?php echo $bat_events; ?></h1>
        <div class="stripe"></div>
        <div style="margin-left : 25px; margin-right : 20px; margin-top : 20px;">
            <?php print render($page['firstpageevents']); ?>
        </div>


    </div>
    <div id="newsEventsColumn">
		<h1 class="headerTxt"><?php echo $ibn; ?></h1>
        <div class="stripe"></div>
		<div style="margin-left : 25px; margin-right : 20px; margin-top : 20px;">
            <?php print render($page['firstpagecentralcol']); ?>
        </div>
		   


    </div>
    <div id="newsEventsColumn">

        <h1 class="headerTxt"><?php echo $quicklinks; ?></h1>
        <div class="stripe"></div>
		<div style="margin-left : 25px; margin-right : 20px; margin-top : 20px;">
            <?php print render($page['firstpagerightcol']); ?>
        </div>	
    </div>

    <div style="clear:both;"></div>
	<div style="margin-left : 25px; margin-right : 20px; margin-top : 20px;">
		<div class="separator"></div>
		<?php print render($page['firstpageinfo']); ?>
		<div class="separator"></div>
	</div>
</div>