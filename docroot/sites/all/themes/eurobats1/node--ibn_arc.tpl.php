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

$sql = "SELECT DISTINCT field_ibn_event_type_value AS title
        FROM ( field_data_field_ibn_event_type AS etype INNER JOIN node ON nid = etype.entity_id )
           LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id
        WHERE ( status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 1 )
        ORDER BY title";

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

@    $yerKey = 0 + $_POST[ "yerSelect" ];
@    $regKey = 0 + $_POST[ "regSelect" ];
@    $couKey = 0 + $_POST[ "couSelect" ];

$sql = "SELECT DISTINCT title, nid, file_managed.uri AS rep, field_ibn_event_repl_value AS repl, country_name\n" .
        "FROM ( ( ( ( ( ( ( node LEFT JOIN field_data_field_ibn_country AS ecou ON nid = ecou.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_type AS etype ON nid = etype.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_rep AS erep ON nid = erep.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_repl AS erepl ON nid = erepl.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_date AS edate ON nid = edate.entity_id )\n" .
        "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id )\n" .
        "    LEFT JOIN file_managed ON erep.field_ibn_event_rep_fid = file_managed.fid )\n" .
        "    LEFT JOIN (SELECT title AS country_name, nid AS pkey FROM node WHERE type = 'country_profiles' ) AS ecou ON field_ibn_country_ebenum = ecou.pkey\n" .
        "WHERE ( type = 'ibn_event' ) AND ( node.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 1 )\n";

//$condIBNEventSql = "#1#AND ( field_ibn_country_ebenum = @COUKEY@ )\n" .
$condIBNEventSql = "#1#AND ( field_ibn_country_ebenum IN ( @COUKEY@ ) )\n" .
            "#2#AND ( field_ibn_event_type_value IN ( @TYPELIST@ ) )\n" .
            //"#3#AND ( ( field_ibn_event_rep_fid IS NOT NULL ) OR ( field_ibn_event_repl_value IS NOT NULL ) )\n" .
            "#3#AND ( ( YEAR(field_ibn_event_date_value) = @YEARKEY@ ) OR ( YEAR(field_ibn_event_date_value2) = @YEARKEY@ ) )\n" .
            "ORDER BY country_name, title";

if ( ( $couKey > 0 ) && is_int( $couKey ) ) $condIBNEventSql = str_replace( "#1#", "", str_replace( "@COUKEY@", $couKey, $condIBNEventSql ) );
if ( ( $regKey > 0 ) && is_int( $regKey ) ) $condIBNEventSql = str_replace( "#1#", "", str_replace( "@COUKEY@", "SELECT nid
FROM node INNER JOIN field_data_field_country_reg ON nid = entity_id
WHERE field_country_reg_ebenum = $regKey", $condIBNEventSql ) );
if ( $typeCondStr != "" ) $condIBNEventSql = str_replace( "#2#", "", str_replace( "@TYPELIST@", $typeCondStr, $condIBNEventSql ) );
if ( ( $yerKey > 0 ) && is_int( $yerKey ) ) $condIBNEventSql = str_replace( "#3#", "", str_replace( "@YEARKEY@", $yerKey, $condIBNEventSql ) );

$res = db_query( $sql . $condIBNEventSql );
$eventArray = Array();
$jsMarkers = ""; $i = 0;
if ( ( $yerKey != 0 ) && ( $couKey != 0 ) )
foreach ( $res as $row ) {
    //$jsMarkers .= "[ \"" . $row->title. "\", " . $row->lat . ", " . $row->lng . " ],\n";
    //$res1 = db_query( str_replace( "@NID@", $row->nid, $sqlTypes ) );

    //$eventTypes = substr( $eventTypes, 0, strlen( $eventTypes ) - 2 );
    $eventArray[ $i ] = Array( "title" => $row->title,
            "country" => $row->country_name,
            "repf" => $row->rep,
            "repl" => $row->repl ); $i++;
}
$eventCount = count( $eventArray );

?>
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

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    // ------------------- Years
    $sql = "SELECT DISTINCT YEAR(field_ibn_event_date_value) AS year\n" .
            "FROM ( field_data_field_ibn_event_date AS edate INNER JOIN node ON nid = edate.entity_id )\n" .
            "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id\n" .
            "WHERE ( status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 1 )\n" .
            "ORDER BY year";
    $res = db_query( $sql );
    $optYear = "<option value=\"-1\">- All -</option>\n";
    foreach ( $res as $row ) {
    
        $optYear .= "<option value=\"" . $row->year . "\" " . (($yerKey == $row->year)?"selected":"") . ">" . $row->year . "</option>\n";
    }
    // ------------------- Country by regions
    $sqlr = "SELECT -1 AS pkey, '- All-' AS name\n" .
            "UNION\n" .
            "SELECT DISTINCT pkey, name\n" .
            "FROM ( ( ( ( map_country_regions INNER JOIN field_data_field_country_reg AS coureg ON field_country_reg_ebenum = pkey )\n" .
            "    INNER JOIN field_data_field_ibn_country AS ibnc ON coureg.entity_id = field_ibn_country_ebenum )\n" .
            "    INNER JOIN node ON ibnc.entity_id = nid )\n" .
            "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON ibnc.entity_id = earc.entity_id )\n" .
            "WHERE ( status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 1 )\n" .
            "ORDER BY name";

    $sqlc = "SELECT DISTINCT node.title, node.nid\n " .
            "FROM ( ( ( node INNER JOIN field_data_field_ibn_country AS ibnc ON ibnc.field_ibn_country_ebenum = node.nid )\n " .
            "    INNER JOIN node AS ibne ON ibne.nid = ibnc.entity_id )\n " .
            "    LEFT JOIN field_data_field_ibn_event_arc AS earc ON ibnc.entity_id = earc.entity_id )\n" .
            "    #INNER JOIN field_data_field_country_reg ON field_data_field_country_reg.entity_id = node.nid\n " .
            "WHERE ( node.type = 'country_profiles' ) AND ( ibne.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 1 ) " .
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

        $jsCouReg = substr( $jsCouReg, 0, strlen( $jsCouReg ) - 2 );
        $jsCouReg = "[ ". $jsCouReg . " ] ";
        //}
        $jsCountriesByRegions .= "\"" . $row->pkey . "\" : " . $jsCouReg . ",\n";
        //echo  $i. "<br />";
    }
    $jsCountriesByRegions = "{ " . substr( $jsCountriesByRegions, 0, strlen( $jsCountriesByRegions ) - 2 ) . " }";
    // -------------------
    ?>
    <form method="post" action="" >
    <table>
        <tr>
            <td style="width : 100px;">Select Year: </td>
            <td style="padding-left : 20px; width : 600px;">
                <select style="width : 500px;" id="yerSelect" name="yerSelect" >
                <?php echo $optYear; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Select Region: </td>
            <td style=" padding-left : 20px; width : 600px;">
                <select style="width : 500px;" id="regSelect" name="regSelect" onchange="regOnChange();">
                <?php echo $jsRegOption; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Select Country: </td>
            <td id="couSelectCont" style=" padding-left : 20px; width : 600px;">
                
            </td>
        </tr>
        <tr>
            <td>Select Type: </td>
            <td style="padding-left : 14px;">
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
    echo "<table>";
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
        str = "<select style=\"width : 500px;\" name=\"couSelect\">\n";
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
    <input class="ebbtn" type="submit" value="Search" />
    </form>
    <br />
    <br />
<?php
    switch ( $eventCount ){
        case 0 :
            if ( ( $yerKey != 0 ) && ( $couKey != 0 ) )
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
    "</table>\n" .
    "<div class=\"separator\"></div>\n";

    function eSampleRepl( $title, $pat, $val, $str ) {
        $eventRowSample = "<tr><td style=\"font-weight : bold; width : 20px;\">@TITLE@</td><td>@VAL@</td></tr>";
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

        $rep = (( $row[ "repf" ] != "" )?file_create_url( $row[ "repf" ] ):(($row[ "repl" ] != "")?$row[ "repl" ]:""));
        $rep = (( $rep!= "")?"<a href=\"" . $rep . "\" target=\"_blank\">" . $row[ "title" ] . "</a>":$row[ "title" ]);
        $str = eSampleRepl( "", "@TITLE@", $rep, $str );

        $out .= $str;
    }
    echo $out;
    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
