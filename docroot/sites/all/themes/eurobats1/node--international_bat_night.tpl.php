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
@ $formid = 0 + $_POST[ "formid" ];

$sql = "SELECT DISTINCT field_ibn_event_type_value AS title\n" .
        "FROM ( field_data_field_ibn_event_type AS etype INNER JOIN node ON nid = etype.entity_id )\n" .
        "   LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id\n" .
        "WHERE ( status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 )\n" .
        "ORDER BY field_ibn_event_type_value";

$res = db_query( $sql );
$types = Array(); $i = 1;
$typeCondStr = "";
foreach ( $res as $row ) {
    $name = "type" . $i;
    @ $on = $_POST[ $name ];
    $on = (($on != "on")?"":"checked");
    $types[ $i - 1 ] = Array( "title" => $row->title, "name" => $name, "on" => $on );
    if ( $on == "checked" ) $typeCondStr .= (($typeCondStr != "")?", ":"") . "'" . $row->title . "'";
    $i++;
}

@    $regKey = 0 + $_POST[ "regSelect" ];
@    $couKey = 0 + $_POST[ "couSelect" ];
@    $withRep = $_POST[ "withRep" ];
$withRep = (($withRep != "on")?"":"checked");

$sql = "SELECT DISTINCT title, lattbl.field_ibn_latitude_value AS lat, lngtbl.field_ibn_longitude_value AS lng\n" .
        ", nid, field_ibn_event_date_value AS s_date, field_ibn_event_date_value2 AS e_date, field_ibn_event_org_value AS org,\n" .
        "field_ibn_event_descr_value AS descr, field_ibn_event_contact_value AS contact, field_ibn_event_addr_value AS addr,\n" .
        "field_ibn_event_email_value AS email, file_managed.uri AS rep, field_ibn_event_rep_description AS repdescr,\n" .
        "field_ibn_event_repl_value AS repl, country_name,\n" .
        "field_ibn_event_location_value AS loc, field_ibn_web_value AS web\n" .
        "FROM ( ( ( ( ( ( ( ( ( ( ( ( ( ( ( ( ( node INNER JOIN field_data_field_ibn_latitude AS lattbl ON nid = lattbl.entity_id )\n" .
        "    INNER JOIN field_data_field_ibn_longitude AS lngtbl ON nid = lngtbl.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_country AS ecou ON nid = ecou.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_type AS etype ON nid = etype.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_location AS eloc ON nid = eloc.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_rep AS erep ON nid = erep.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_announce AS eann ON nid = eann.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_repl AS erepl ON nid = erepl.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_date AS edate ON nid = edate.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_org AS eorg ON nid = eorg.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_descr AS edescr ON nid = edescr.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_contact AS econtact ON nid = econtact.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_addr AS eaddr ON nid = eaddr.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_email AS eemail ON nid = eemail.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_web AS tlbweb ON nid = tlbweb.entity_id )\n" .
        "    LEFT JOIN file_managed ON erep.field_ibn_event_rep_fid = file_managed.fid )\n" .
        "    LEFT JOIN (SELECT title AS country_name, nid AS pkey FROM node WHERE type = 'country_profiles' ) AS ecou " .
        "ON field_ibn_country_ebenum = ecou.pkey\n" .
        "WHERE ( type = 'ibn_event' ) AND ( node.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 )\n";
$sql = "SELECT DISTINCT title, lattbl.field_ibn_latitude_value AS lat, lngtbl.field_ibn_longitude_value AS lng
, nid, field_ibn_event_date_value AS s_date, field_ibn_event_date_value2 AS e_date, field_ibn_event_org_value AS org,
field_ibn_event_descr_value AS descr, field_ibn_event_contact_value AS contact, field_ibn_event_addr_value AS addr,
field_ibn_event_email_value AS email, fm1.uri AS rep, field_ibn_event_rep_description AS repdescr, fm2.uri AS announce,
field_ibn_event_announce_description AS anndescr, field_ibn_event_repl_value AS repl, country_name,
field_ibn_event_location_value AS loc, field_ibn_web_value AS web
FROM node INNER JOIN field_data_field_ibn_latitude AS lattbl ON nid = lattbl.entity_id
    INNER JOIN field_data_field_ibn_longitude AS lngtbl ON nid = lngtbl.entity_id
    LEFT JOIN field_data_field_ibn_country AS ecou ON nid = ecou.entity_id
    LEFT JOIN field_data_field_ibn_event_type AS etype ON nid = etype.entity_id
    LEFT JOIN field_data_field_ibn_event_location AS eloc ON nid = eloc.entity_id
    LEFT JOIN field_data_field_ibn_event_rep AS erep ON nid = erep.entity_id
    LEFT JOIN field_data_field_ibn_event_announce AS eann ON nid = eann.entity_id
    LEFT JOIN field_data_field_ibn_event_repl AS erepl ON nid = erepl.entity_id
    LEFT JOIN field_data_field_ibn_event_date AS edate ON nid = edate.entity_id
    LEFT JOIN field_data_field_ibn_event_org AS eorg ON nid = eorg.entity_id
    LEFT JOIN field_data_field_ibn_event_descr AS edescr ON nid = edescr.entity_id
    LEFT JOIN field_data_field_ibn_event_contact AS econtact ON nid = econtact.entity_id
    LEFT JOIN field_data_field_ibn_event_addr AS eaddr ON nid = eaddr.entity_id
    LEFT JOIN field_data_field_ibn_event_email AS eemail ON nid = eemail.entity_id
    LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id
    LEFT JOIN field_data_field_ibn_web AS tlbweb ON nid = tlbweb.entity_id
    LEFT JOIN file_managed AS fm1 ON erep.field_ibn_event_rep_fid = fm1.fid
    LEFT JOIN file_managed AS fm2 ON eann.field_ibn_event_announce_fid = fm2.fid
    LEFT JOIN (SELECT title AS country_name, nid AS pkey FROM node WHERE type = 'country_profiles' ) AS ecou ON field_ibn_country_ebenum = ecou.pkey
WHERE ( type = 'ibn_event' ) AND ( node.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 )\n";


$condIBNEventSql = "#1#AND ( field_ibn_country_ebenum IN ( @COUKEY@ ) )\n" .
            "#2#AND ( field_ibn_event_type_value IN ( @TYPELIST@ ) )\n" .
            "#3#AND ( ( field_ibn_event_rep_fid IS NOT NULL ) OR ( field_ibn_event_repl_value IS NOT NULL ) )\n" .
            "ORDER BY country_name, s_date, title";

$sqlTypes = "SELECT field_ibn_event_type_value AS type FROM field_data_field_ibn_event_type WHERE entity_id = @NID@";

if ( ( $couKey > 0 ) && is_int( $couKey ) ) $condIBNEventSql = str_replace( "#1#", "", str_replace( "@COUKEY@", $couKey, $condIBNEventSql ) );
if ( ( $regKey > 0 ) && is_int( $regKey ) ) $condIBNEventSql = str_replace( "#1#", "", str_replace( "@COUKEY@", "SELECT nid
FROM node INNER JOIN field_data_field_country_reg ON nid = entity_id
WHERE field_country_reg_ebenum = $regKey", $condIBNEventSql ) );
if ( $typeCondStr != "" ) $condIBNEventSql = str_replace( "#2#", "", str_replace( "@TYPELIST@", $typeCondStr, $condIBNEventSql ) );
if ( $withRep == "checked" ) $condIBNEventSql = str_replace( "#3#", "", $condIBNEventSql );
$jsMarkers = ""; $eventCount = 0;
if ( $formid == 1 ) {
    $res = db_query( $sql . $condIBNEventSql );
    $eventArray = Array();
    $i = 0;
    foreach ( $res as $row ) {
        $jsMarkers .= "[ \"" . $row->title. "\", " . $row->lat . ", " . $row->lng . " ],\n";
        $res1 = db_query( str_replace( "@NID@", $row->nid, $sqlTypes ) );
        $eventTypes = "";
        foreach ( $res1 as $row1 ) {
            $eventTypes .= $row1->type . ", ";
        }
        $eventTypes = substr( $eventTypes, 0, strlen( $eventTypes ) - 2 );
        $web = checkURL( $row->web );
        if ( $web != "" ) $web = "<a href=\"$web\" target=\"_blank\">$web</a>";
        $mail = $row->email;
        if ( $mail != "" ) $mail = "<a href=\"mailto:" . $row->email . "\">" . $row->email . "</a>";
        $eventArray[ $i ] = Array( "title" => $row->title,
                "country" => $row->country_name,
                "types" => $eventTypes,
                "sdate" => $row->s_date, "edate" => $row->e_date,
                "loc" => $row->loc,
                "org" => $row->org,
                "descr" => $row->descr,
                "contact" => $row->contact,
                "addr" => $row->addr,
                "web" => $web,
                "email" => $mail, //$row->email,
                "repf" => $row->rep,
                "repl" => $row->repl,
                "repdescr" => $row->repdescr,
                "ann" => $row->announce,
                "anndescr" => $row->anndescr ); $i++;
    }
    $eventCount = count( $eventArray );
    if ( $jsMarkers )
        $jsMarkers = substr( $jsMarkers, 0, strlen( $jsMarkers ) - 2 );
}
$jsMarkers = "[\n" . $jsMarkers . "\n]\n";
?>
<script type="text/javascript">
var map;
function initialize() {
    var myOptions = {
        zoom        : 3,
        maxZoom     : 10,
        minZoom     : 2,
        center      : new google.maps.LatLng( 55, 30 ),
        mapTypeId   : google.maps.MapTypeId.TERRAIN
    };
    var markersData = <?php echo $jsMarkers; ?>;
    var markersObj = Array();
    map = new google.maps.Map( document.getElementById('map_canvas'), myOptions);
	
	var eventMark = new google.maps.MarkerImage('/'+Drupal.settings.pathToTheme.pathToTheme+'/img/gmap_mark.png',
	new google.maps.Size(40,40),
	new google.maps.Point(0,0),
	new google.maps.Point(20,40)
	);
	var eventShadow = new google.maps.MarkerImage('/'+Drupal.settings.pathToTheme.pathToTheme+'/img/gmap_mark_shadow.png',
	new google.maps.Size(70,42),
	new google.maps.Point(0,0),
	new google.maps.Point(22, 40)
	);

    for ( i = 0; i < markersData.length; i++ )
        markersObj[ i ] = new google.maps.Marker({
                            position    : new google.maps.LatLng( markersData[ i ][ 1 ], markersData[ i ][ 2 ] ),
                            map         : map,
							icon		: eventMark,
							shadow		: eventShadow,
                            title       : markersData[ i ][ 0 ]
                            });
   
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php //<h1>Ёб н?!</h1> ?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

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

  <div class="content" <?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    /*$sqlr = "SELECT -1 AS pkey, '- All-' AS name\n" .
            "UNION\n" .
            "SELECT DISTINCT pkey, name\n" .
            "FROM ( map_country_regions INNER JOIN field_data_field_country_reg AS coureg ON field_country_reg_ebenum = pkey )\n" .
            "    INNER JOIN field_data_field_ibn_country ON coureg.entity_id = field_ibn_country_ebenum\n" .
            "ORDER BY name";*/
    $sqlr = "SELECT -1 AS pkey, '- All-' AS name\n" .
            "UNION\n" .
            "SELECT DISTINCT pkey, name\n" .
            "FROM ( ( ( ( map_country_regions INNER JOIN field_data_field_country_reg AS coureg ON field_country_reg_ebenum = pkey )\n" .
            "    INNER JOIN field_data_field_ibn_country AS ibnc ON coureg.entity_id = field_ibn_country_ebenum )\n" .
            "    INNER JOIN node ON ibnc.entity_id = nid )\n" .
            "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON ibnc.entity_id = earc.entity_id )\n" .
            "WHERE ( status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 )\n" .
            "ORDER BY name";

    $sqlc = "SELECT DISTINCT node.title, node.nid\n " .
            "FROM ( ( ( node INNER JOIN field_data_field_ibn_country AS ibnc ON ibnc.field_ibn_country_ebenum = node.nid )\n " .
            "    INNER JOIN node AS ibne ON ibne.nid = ibnc.entity_id )\n " .
            "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON ibnc.entity_id = earc.entity_id )\n" .
            "    #INNER JOIN field_data_field_country_reg ON field_data_field_country_reg.entity_id = node.nid\n " .
            "WHERE ( node.type = 'country_profiles' ) AND ( ibne.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 ) " .
            "#AND ( field_country_reg_ebenum = @RKEY@ )\n " .
            "ORDER BY node.title";
    $res = db_query( $sqlr );
    $jsCountriesByRegions = "";
    $jsRegOption = "";
    foreach ( $res as $row ) {
        $jsRegOption .= "        <option value=\"" . $row->pkey . "\" " . (($regKey == $row->pkey)?"selected":"") . ">" . $row->name . "</option>";
        //echo  . "<br />";
        $resc = db_query( str_replace( "@RKEY@", $row->pkey, str_replace( (($row->pkey == -1)?"ZZ":"#"), "", $sqlc ) ) );
        //if ( $row->pkey == -1 ) continue;
        $i = 0;
        $jsCouReg = "[ \"- All -\", -1 ],\n";
        foreach ( $resc as $rowc ) {
            $i++;
            $jsCouReg .= "[ \"" . $rowc->title . "\", " . $rowc->nid . " ],\n";
        }
        //if ( $jsCouReg == "" )
            //$jsCouReg = "NULL";
        //    $jsCouReg = "[ [ \"- All -\", -1 ] ] ";
        //else {
        
        $jsCouReg = substr( $jsCouReg, 0, strlen( $jsCouReg ) - 2 );
        $jsCouReg = "[ ". $jsCouReg . " ] ";
        //}
        $jsCountriesByRegions .= "\"" . $row->pkey . "\" : " . $jsCouReg . ",\n";
        //echo  $i. "<br />";
    }
    $jsCountriesByRegions = "{ " . substr( $jsCountriesByRegions, 0, strlen( $jsCountriesByRegions ) - 2 ) . " }";
    ?>
    <?php
    //echo "<textarea style=\"height : 200px; width : 100%\">";
    //echo $jsCountriesByRegions;
    //print_r( $_POST );
    //echo "</textarea>";
    ?>
    <form method="post" action="" >
    <input type="hidden" value="1" name="formid" />
    <table><tr>
    <td>Select Region: </td>
    <td style="padding-left : 16px; width : 600px;"><select style="width : 550px;" id="regSelect" name="regSelect" onchange="regOnChange();">
<?php echo $jsRegOption; ?>
    </select>
    </td></tr><tr>


    <td>Select Country: </td>
    <td id="couSelectCont" style="padding-left : 16px;">
    </td>
    </tr>
    <tr>
    <td>Select Type: </td>
    <td>
<?php 
    $typeWidth = "200px;"; 
    $typeSample = "<label style=\"font-weight : normal;\"><input type=\"checkbox\" name=@NAME@ @ON@ />&nbsp;@TITLE@</label>";
    $rowTypeSample = "<tr>\n" .
        "<td style=\"width : $typeWidth \">@C1@</td>\n" .
        "<td style=\"width : $typeWidth \">@C2@</td>\n" .
        "<td style=\"width : $typeWidth \">@C3@</td>\n" .
        "</tr>\n";
    $typesCount = count( $types );
    $j = 1; $out = ""; $str = $rowTypeSample;
    for ( $i = 0; $i < $typesCount; $i++ ) {
        $str1 = $typeSample;
        $str1 = str_replace( "@NAME@", $types[ $i ][ "name" ], str_replace( "@ON@", $types[ $i ][ "on" ], str_replace( "@TITLE@", $types[ $i ][ "title" ], $str1 ) ) );
        $str = str_replace( "@C$j@", $str1, $str );
        $j++;
        if ( $j == 4 ) {
            $out .= $str;
            if ( $i != ( $typesCount - 1 ) )
                $str = $rowTypeSample;
            else
                $str = "";
            $j = 1;
        }
    }
    for ( $i = 2; $i < 4; $i++ ) {
        $str = str_replace( "@C$i@", "", $str );
    }
    $out .= $str;
    echo "<table style=\"margin-left : 10px; \">";
    echo $out;
    echo "</table>";
?>
    </td>
    </tr>
    </table>


    <script type="text/javascript">
    var regSelect = document.getElementById( "regSelect" );
    var couSelect;
    var couSelectCont = document.getElementById( "couSelectCont" );
    var couByReg = <?php echo $jsCountriesByRegions; ?>;
    var couKey = <?php echo $couKey; ?>;

    function regOnChange() {
        //alert( "regOnChange " + regSelect.value );
        //couSelectCont.innerHTML = "regOnChange " + regSelect.value;
        couArray = couByReg[ regSelect.value ];
        str = "<select style=\"width : 550px;\" name=\"couSelect\">\n";
        for ( i = 0; i < couArray.length; i++ )
            str += "<option value=" + couArray[ i ][ 1 ] + " " +
                ((couKey == couArray[ i ][ 1 ])?"selected":"") +
                ">" + couArray[ i ][ 0 ] + "</option>\n";
        str+= "</select>\n";
        couSelectCont.innerHTML = str;
        couKey = -1;
    }
    regOnChange();
    </script>


<label style="font-weight : normal;"><input type="checkbox" name="withRep" <?php echo $withRep; ?> />IBN reports only</label><br />
    <input class="ebbtn" type="submit" value="Search" />
    </form>
    <br />
    <br />
    <div id="map_canvas" style="height:450px; margin-left : 30px; width : 850px;"></div>
    <br />
    <br />
    <?php
    switch ( $eventCount ){
        case 0 :
            if ( $formid == 1 )
                echo "<p>Nothing was found for your search.</p>";
            break;
        case 1 :
            echo "<p>The following event was found for your search:</p>";
            break;
        default :
            echo "<p>The following $eventCount events were found for your search:</p>";
    }
    echo "<br />";

    $eventSample = "@COUNTRY@" .
    "<table>\n" .
    "@TITLE@\n" .
    "@TYPES@\n" .
    "@DATE@\n" .
    "@LOC@\n" .
    "@ORG@\n" .
    "@DESCR@\n" .
    "@CONTACT@\n" .
    "@ADDR@\n" .
    "@WEB@\n" .
    "@EMAIL@\n" .
    "@ANN@\n" .
    "@REP@\n" .
    "</table>\n" .
    "<div style=\"height : 12px; width : 100%;\"></div>\n" .
    "<div class=\"separator1\"></div>\n" .
    "<div style=\"height : 12px; width : 100%;\"></div>\n";

    function eSampleRepl( $title, $pat, $val, $str ) {
        $eventRowSample = "<tr><td style=\"font-weight : bold; width : 115px;\">@TITLE@</td><td>@VAL@</td></tr>";
        return str_replace( $pat, (($val == "")?"":str_replace( "@VAL@", $val, str_replace( "@TITLE@",  $title, $eventRowSample ) )), $str );
    }

    $out = ""; $country_name = "";
    for ( $i = 0; $i < $eventCount; $i++ ) {
        $str = $eventSample;
        $row = $eventArray[ $i ];
        if ( $country_name != $row[ "country" ] ) {
            $country_name = $row[ "country" ];
            $str1 = "<h3>$country_name</h3>";
        }
        else
            $str1 = "";

        $str = str_replace( "@COUNTRY@", $str1, $str );
        //$str = str_replace( "@VAL@", $row[ "title" ], $str );
        $str = eSampleRepl( "Event name:", "@TITLE@", $row[ "title" ], $str );

        $str = eSampleRepl( "Event type:", "@TYPES@", $row[ "types" ], $str );

        $sd = strtotime( $row[ "sdate" ] ); $sd = (($sd != -1)?date( "jS M Y", $sd ):"");
        $ed = strtotime( $row[ "edate" ] ); $ed = (($ed != -1)?date( "jS M Y", $ed ):"");
        $ed = (($sd == $ed)?"":" to " . $ed);
        $sd = (($ed != "")?"from " . $sd:$sd);
        $str = eSampleRepl( "Date:", "@DATE@", $sd . $ed, $str );

        $str = eSampleRepl( "Location:", "@LOC@", $row[ "loc" ], $str );

        $str = eSampleRepl( "Organiser:", "@ORG@", $row[ "org" ], $str );

        $str = eSampleRepl( "Description:", "@DESCR@", $row[ "descr" ], $str );

        $str = eSampleRepl( "Contact name:", "@CONTACT@", $row[ "contact" ], $str );

        $str = eSampleRepl( "Contact address:", "@ADDR@", $row[ "addr" ], $str );

        $str = eSampleRepl( "Web address:", "@WEB@", $row[ "web" ], $str );

        $str = eSampleRepl( "Contact e-mail:", "@EMAIL@", $row[ "email" ], $str );

        $rep = (( $row[ "repf" ] != "" )?file_create_url( $row[ "repf" ] ) . "\" target=\"_blank\">" . (($row[ "repdescr" ] != "")?$row[ "repdescr" ]:"[Read on]") . "":(($row[ "repl" ] != "")?checkURL( $row[ "repl" ] ) . "\" target=\"_blank\">[Read on]":""));
        $rep = (( $rep!= "")?"<a href=\"" . $rep . "</a>":"");
        $str = eSampleRepl( "Report:", "@REP@", $rep, $str );

        $ann = (( $row[ "ann" ] != "" )?file_create_url( $row[ "ann" ] ) . "\" target=\"_blank\">" . (($row[ "anndescr" ] != "")?$row[ "anndescr" ]:"[Read on]"):"");
        $ann = (( $ann!= "")?"<a href=\"" . $ann . "</a>":"");
        $str = eSampleRepl( "Announce:", "@ANN@", $ann, $str );

        $out .= $str;
    }
    //echo "<textarea style=\"height : 200px; width : 100%\">";
    //echo $out;
    //echo "</textarea>";
    echo $out;
    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
