<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */

/*
SELECT field_country_a2_ebenum AS alpha2, title AS name, nid AS pkey, 

IF(field_countryp_pr_value = "Parties", 0, IF(field_countryp_pr_value = "Range states", 1, 2 ) ) AS parties, 

"" AS sdate, "" AS ddate, uri
FROM ( ( ( node LEFT JOIN field_data_field_country_a2 AS a2 ON nid = a2.entity_id )
    LEFT JOIN field_data_field_countryp_pr AS pr ON nid = pr.entity_id )
    LEFT JOIN field_data_field_country_fp AS fp ON nid = fp.entity_id )
    LEFT JOIN file_managed ON file_managed.fid = fp.field_country_fp_fid
WHERE type = "country_profiles"
ORDER BY name
*/
?>

<?php
//include( "inc/php/libcommon.php" );
checkIE();

$sql = "SELECT field_country_a2_ebenum AS alpha2, title AS name, nid AS pkey,
        IF(field_countryp_pr_value = 'Parties', 0, IF(field_countryp_pr_value = 'Range states', 1, 2 ) ) AS parties,
        field_sign_date_value AS sdate, field_deposit_date_value AS ddate, uri
        FROM ( ( ( node LEFT JOIN field_data_field_country_a2 AS a2 ON nid = a2.entity_id )
            LEFT JOIN field_data_field_countryp_pr AS pr ON nid = pr.entity_id )
            LEFT JOIN field_data_field_country_fp AS fp ON nid = fp.entity_id )
            LEFT JOIN file_managed ON file_managed.fid = fp.field_country_fp_fid
            LEFT JOIN field_data_field_sign_date AS tlbsd ON nid = tlbsd.entity_id
            LEFT JOIN field_data_field_deposit_date AS tlbdd ON nid = tlbdd.entity_id
        WHERE type = 'country_profiles'
        ORDER BY name";

$res = db_query( $sql );

$cjArray = "";
$cpArray = Array(); $i = 0;
foreach ($res as $row) /*{
    echo "<h1>" . $row->name . "</h1>";
}*/



{
    $pgHref = "/" . drupal_get_path_alias( "node/" . $row->pkey );
    $flagUri = file_create_url( $row->uri );
    $sd = strtotime( escapeString( $row->sdate ) );
    $sdate = ((($sd != -1) && ($sd != "") )?date( "Y", $sd ):""); //date( "jS M Y", $sd )
    
    $dd = strtotime( $row->ddate );
    $ddate = ((($dd != -1)  && ($dd != "") )?date( "Y", $dd ):""); //date( "jS M Y", $dd )
    
    $cpArray[ $i++ ] = Array( "alpha2"=>$row->alpha2, "name"=>$row->name, "sdate"=>$sdate, "ddate"=>$ddate, "parties"=> $row->parties, "href" => $pgHref, "flaguri" => $flagUri );
    switch ( $row->parties + 0 ) {
        case 0 : 
            $color1 = "#3a6693";//"#8aaccd";
            $color2 = "#092d51";//"#5582af";
            break;
        case 1 :
            $color1 = "#f2e6b4";
            $color2 = "#e8d173";
            break;
        case 2 :
            $color1 = "#f2e6b4";
            $color2 = "#e8d173";
            break;
    }
    
    
    $cjArray .= "{ alpha2 : \"" . $row->alpha2 . "\",  name : \"" . $row->name . "\", color1 : \"$color1\", color2 : \"$color2\", sdate : \"$sdate\", ddate : \"$ddate\", href : \"" . escapeString( $pgHref ) . "\", flaguri : \"" . escapeString( $flagUri ) . "\" },\n";
    
}
$cjArray = "[\n" . rtrim( $cjArray, "\n\ ," ) . "]\n";


//echo "<textarea style=\"height : 800px; width : 100%\">";
//echo $cjArray;
//echo "</textarea>"
?>



<?php //print render($content['field_prs_head']); ?>
<div id="headDiv" style="background-color : #ffffff; left : 30px; position : absolute; top : 170px; overflow : hidden; width : 930px; z-index : 1;">
<?php print render($content['field_prs_head']); ?>
</div>
<div id="id1" style="background-color : #ffffff; border : solid black 1px; left : 50px; position : absolute; top : 295px; overflow : hidden; width : 900px; height : 553px; z-index : 1;">

<!--[if lt ie 9]>
<div id="mapCont" style="position : absolute; top :0px; left:0px; margin : 0px; overflow : hidden; width : 100%; height : 100%; ">
<div style="background-color : #ff00ff; position : absolute; top :0px; left:0px; margin : 0px; width : 1000px; height :1000px;">
<?php 
include( "img/map/map.vml");
?>
</div>
</div>
<![endif]-->
<script type="text/javascript">
    isVML = ( isIE && ( (IEVersion > 5) && (IEVersion < 9) ));
    if( !isVML ) document.write( "<object id=\"mapCont\" type=\"image/svg+xml\" data=\"/"+Drupal.settings.pathToTheme.pathToTheme+"/img/map/map.svg\" wmode=\"transparent\" style=\"overflow : hidden; position : absolute;\"></object>" );
    //height=\"553\" width=\"900\"
    //<object id="svgObj" height="200" width="650" type="image/svg+xml" data="img/map1.svg" wmode="transparent" style="display : none; overflow : hidden;"></object>
</script>

    
</div>









<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>



<div id="spacer1" style="background-color : #ffffff; position : relative; width : 100%; height : 685px; padding-bottom : 100px; ">

<?php /*<div id="mapMessage" style="border : solid black 1px; background-color : #FFFFFF; position : absolute; display : none; top :0px; padding: 5px; left:0px; overflow : hidden; z-index : 5;">
asdasdasd
</div> 

   <div id="btnCont" style="display : block; left : 40px; overflow : hidden; padding: 5px; position : absolute; top : 60px; width: 40px; z-index : 3;">

    <div>
        <div style="text-align : center; width : 100%;">
            <img id="shTBtn" src="/<?php print $directory; ?>/img/map/btn/arrow_top.png" alt="/\" onclick="document.getElementById('id1').scrollTop += 50;"/>
        </div>
        <div style="text-align : center; width : 100%;">
            <img id="shLBtn" src="/<?php print $directory; ?>/img/map/btn/arrow_left.png" alt="<" onclick="document.getElementById('id1').scrollLeft += 100;" />
            <img id="shRBtn" src="/<?php print $directory; ?>/img/map/btn/arrow_right.png" alt=">" onclick="document.getElementById('id1').scrollLeft -= 100;" />
        </div>
        <div style="text-align : center; width : 100%;">
            <img id="shBBtn" src="/<?php print $directory; ?>/img/map/btn/arrow_bottom.png" alt="\/" onclick="document.getElementById('id1').scrollTop -= 50;"/>
        </div>

        <div style="height : 10px; overflow : hidden; text-align : center; width : 100%;">
        </div>

        <div style="text-align : center; width : 100%;">
            <img id="incScaleBtn" src="/<?php print $directory; ?>/img/map/btn/zoom_plus.png" alt="+" /></td>
        </div>
        <div style="text-align : center; width : 100%;">
            <img id="decScaleBtn" src="/<?php print $directory; ?>/img/map/btn/zoom_minus.png" alt="-" />
        </div>
    </div>

</div>
*/ ?>

<table id="legendCont" style="position : absolute; left : 45px; top : 725px;">
    <tr>
    <td style="background-color : #3a6693; border : solid black 1px; height : 20px; width: 30px;"></td><td>&nbsp;Parties&nbsp;&nbsp;&nbsp;</td>
    <td style="background-color : #f2e6b4; border : solid black 1px; height : 20px; width: 30px;"></td><td>&nbsp;Range states&nbsp;&nbsp;&nbsp;</td>
<?php /*    <td style="background-color : #CFCFCF; border : solid black 1px; height : 20px; width: 20px;"></td><td> Other countries</td> */ ?>
    </tr>
    
</table>
<?php /*"#3a6693";//"#8aaccd";
            $color2 = "#092d51";//"#5582af";
            break;
        case 1 :
            $color1 = "#f2e6b4";
            $color2 = "#e8d173"; */?>
</div>


<script type="text/javascript">

var map;
//alert( IEVersion );
var _body = document.getElementsByTagName('BODY') [0];

if (!_body)
    _body = document.getElementsByTagName('body') [0];

if (!_body)
    _body = document.body;

if (!_body)
{
    var htmlTag = document.documentElement;
    for(var i = 0; i < htmlTag.childNodes.length; i++)
    {
        if (htmlTag.childNodes[i].nodeName.toLowerCase() == 'body')
        {
            _body = htmlTag.childNodes[i];
            break;
        }
    }
}


//var parent = document.getElementsByTagName('BODY')[0];
//var parent = document.body;
var mapScale    = 600;
var mapXOffset  = 1700;
var mapYOffset  = 140;

var element =  document.createElement('div');
_body.appendChild( element );
element.id = "mapMessage1";
element.style.border            = "solid black 1px"; 
element.style.backgroundColor   = "#FFFFFF";
element.style.position          = "absolute" ;
element.style.display           = "none";
element.style.top               = "0px";
element.style.padding           = "5px";
element.style.left              = "0px";
element.style.overflow          = "hidden";
element.style.zIndex            = "5";

function createMapObject( mapDoc ) {
    map = new VectorMap( {
            mapDoc              : mapDoc,
            isVML               : isVML,
            IEVer               : IEVersion,
            scaleObjId          : "layer1",
            incScaleBtnId       : "incScaleBtn",
            decScaleBtnId       : "decScaleBtn",
            mapContainer        : "mapCont",
            countries           : <?php echo $cjArray; ?>,
            countriesObjCont    : "layer2"
            });

    if ( isIE && ( IEVersion = 9 ) ) {
        //map.setScale( mapScale );
        map.setPosition( mapXOffset, mapYOffset );
    }
}

window.onload = function () {

    if ( isVML )
        createMapObject( document );
    var headDiv = document.getElementById( "headDiv" );
    var id1 = document.getElementById( "id1" );
    var spacer1 = document.getElementById( "spacer1" );
    var btnCont = document.getElementById( "btnCont" );
    var legendCont = document.getElementById( "legendCont" );

    y1 = id1.offsetTop + headDiv.offsetHeight + "px"; //190
    //id1.style.top = y1;
    y2 = 580 + headDiv.offsetHeight + "px"; //580
    //spacer1.style.height = y2;
    //btnCont.style.top = btnCont.offsetTop + headDiv.offsetHeight + "px"; //60 !!!!!!!!!!!!!!!!!!!
    y3 = legendCont.offsetTop + headDiv.offsetHeight + "px";
    //legendCont.style.top = y3;
    //alert( "id1.style.top = " + y1 + "\nspacer1.style.height = " + y2 + "\nlegendCont.style.top = " + y3 );

    if ( !isIE ) {
        //map.setScale( mapScale );
        map.setPosition( mapXOffset, mapYOffset );
    }
    //map.setPosition( mapXOffset, mapYOffset );

}
    
</script>

<script type="text/javascript">
	(function ($) {
		$(document).ready(function(){
    		$("#c_list").click(function(){
        		$("#country_list").slideToggle("slow");
				$(this).toggleClass("active");
				return false;
        	});
		});
	})(jQuery);
</script>

<?php /*
<a href="#" id="c_list"><b> Countries list </b></a>
<div id="country_list" style="display : none; ">

<p>(The Parties to the Agreement are written in <strong>bold</strong> letters.)</p>
<table border="1" cellpadding="5" cellspacing="2" width="800">
    <tbody>
        <tr>
            <td width="24">
                No.</td>
            <td width="236">
                Range States</td>
            <td width="240">
                Date of Signing</td>
            <td width="282">
                Date of Deposit of Instrument of Ratification / Acceptance / Approval / Accession</td>
        </tr>

$sample = "        <tr>\n" .
"            <td>@NUM@</td>\n" .
//"            <td><img src=\"/$directory/img/map/flags/@CC@.png\" alt=\"\" />&nbsp;&nbsp;&nbsp;@NAME@</td>\n" .
"            <td><a href=\"@HREF@\"><img src=\"@CC@\" alt=\"\" />&nbsp;&nbsp;&nbsp;@NAME@</a></td>\n" .
"            <td>@SDATE@</td>\n" .
"            <td>@DDATE@</td>\n" .
"        </tr>\n";
$j = 1;
for ( $i = 0; $i < count( $cpArray ); $i++ ) {
    //$sd = strtotime( $cpArray[ $i ][ "sdate" ] );
    //$sdate = ((($sd != -1) && ($sd != "") )?date( "jS M Y", $sd ):"&nbsp;");
    $sdate = $cpArray[ $i ][ "sdate" ];
    
    //$dd = strtotime( $cpArray[ $i ][ "ddate" ] );
    //$ddate = ((($dd != -1)  && ($dd != "") )?date( "jS M Y", $dd ):"&nbsp;");
    $ddate = $cpArray[ $i ][ "ddate" ];
    
    if( $cpArray[ $i ][ "parties" ] == 2 ) continue;
    if( $cpArray[ $i ][ "parties" ] == 0 ) {
        $b1 = "<b>";
        $b2 = "</b>";
    }
    else {
        $b1 = "";
        $b2 = "";
    }
    echo str_replace( "@HREF@", $cpArray[ $i ][ "href" ], str_replace( "@DDATE@", $ddate, str_replace( "@SDATE@", $sdate, str_replace( "@NAME@", $b1 . $cpArray[ $i ][ "name" ] . $b2, str_replace( "@CC@", $cpArray[ $i ][ "flaguri" ], str_replace( "@NUM@", $j++, $sample ))))));
}

        
        
    </tbody>
</table>
</div>


*/?>




  <?php /*print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h3<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><img src="/<?php print $directory; ?>/img/bat_mark.png" alt="" />&nbsp;&nbsp;<?php print $title; ?></a></h3>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); */?>

</div>
