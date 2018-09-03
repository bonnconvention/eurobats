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
SELECT title AS name, nid AS pkey, uri
FROM ( node LEFT JOIN field_data_field_country_fp AS fp ON nid = fp.entity_id )
    LEFT JOIN file_managed ON file_managed.fid = fp.field_country_fp_fid
WHERE type = "country_profiles"
ORDER BY name
*/

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//!  INSERT INTO file_usage VALUES( 156, "file", "node", 181, 1 )
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
@ $mode = $_GET[ "mode" ];
$isScol = $page && ( $mode != "print");
$sml = "";
$scl = "";
if ( $isScol ) {
    $sml = " style=\"margin-left : 5px;\"";
    $scl = " style=\"border-left : 1px solid #3e78b2; padding-left : 20px; overflow : hidden;\"";
}

?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes . $sml;?>>

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

<?php if ( $isScol ) { ?>
  <div style="border-right : 1px solid #3e78b2; float : left; height : 100%; margin-left : 10px; margin-right : -1px; margin-top:10px; width : 200px;">
    <?php
    $sql = "SELECT title AS name, nid AS pkey, uri
            FROM ( node LEFT JOIN field_data_field_country_fp AS fp ON nid = fp.entity_id )
                LEFT JOIN file_managed ON file_managed.fid = fp.field_country_fp_fid
                LEFT JOIN field_data_field_countryp_pr AS tlbpr ON nid = tlbpr.entity_id
            WHERE ( type = 'country_profiles' )
                  AND ( field_countryp_pr_value IN ( 'Parties', 'Range states' ) )
                  AND ( node.status = 1 )
                  AND ( nid <> " . $node->nid . " )
            ORDER BY name";
    $res = db_query( $sql );
    $clS = "<a style=\"\" href=\"@HREF@\">@NAME@</a><br />";
    foreach ($res as $row) {
        $href = "/" . drupal_get_path_alias( "node/" . $row->pkey );
        echo str_replace( "@NAME@", $row->name, str_replace( "@HREF@", $href, $clS ) );
    }

    ?>
  </div>
<?php }
    else
        include_once( "inc/php/libcommon.php" );
        ?>

  <div class="content"<?php print $content_attributes . $scl; ?>>
  <div style="width : 100%; text-align : right;"><h3 style="float : left"><?php echo $title; ?></h3>
  <?php if ( $isScol ) { ?>
    <?php //<div style="width : 100%; text-align : right;"><a href="?mode=print">Print</a></div> ?>
    <a target="_blank" href="?mode=print"><img src="/<?php echo $directory; ?>/img/print.png" title="Print-friendly version" alt="Print-friendly version" /></a>
    <?php } ?>
    </div>
    
    <div style="clear : both;"></div>
    
    <div style=" padding-bottom : 10px;">
        <i>The boundaries and names used on this map do not imply official endorsement or acceptance by the United Nations. 
        <br /> Map data &copy; 2012 Google</i>
    </div>
    
    <?php
      // We hide the comments and links now so that we can render them later.
    $mi_url = "";
    @ $stdObj = $content['field_country_mi'][ "#object" ];
    if ( isset( $stdObj ) ) {
        $lang = $stdObj->language;
        $mi_url = file_create_url( $stdObj->field_country_mi[ $lang ][ 0 ][ "uri" ] );
    }

    //echo "<textarea>";
    //echo $mi_url;
    //echo "</textarea>";
    
    //echo "<div style=\"height : 30px; width : 100%\"><h3>$title<h3></div>";
    
    if ( $mi_url != "" )
    echo "<div style=\"float : right; margin-bottom : 10px; margin-left : 15px;\"><img style=\"border : 3px solid #003366;\" src=\"$mi_url\" /></div>";


    hide($content['comments']);
    hide($content['links']);
    hide($content['field_country_mi']);
    hide($content['field_protected_areas']);
    hide($content['field_signatory']);
    hide($content['field_deposit_date']);
    hide($content['field_stc_mem']);

    //echo "<div style=\"float : left;\">";
    $psd = strtotime( escapeString( db_query( "SELECT field_deposit_date_value FROM field_data_field_deposit_date WHERE entity_id = $nid" )->fetchField() ) );
    $psd = ((($psd != -1) && ($psd != "") )?date( "Y", $psd ):"");
    //print $nid . "<br />";
    if ( $psd != "" ) {
        print  "<strong>Party since: </strong><span class=\"date-display-single\" content=\"$psd\" datatype=\"xsd:dateTime\" property=\"dc:date\">$psd</span>";

        @ $stdObj = $content['field_stc_mem'][ "#object" ];
        if ( isset( $stdObj ) ) {
            $lang = $stdObj->language;
            print "<div class=\"field field-name-field-deposit-date field-type-datetime field-label-above\"></div>";
            print  "<strong>" . $content['field_stc_mem'][ "#title" ] . ": </strong>" .
                (((0 + $stdObj->field_stc_mem[ $lang ][ 0 ][ "value" ]) == 1)?"Yes":"No");
        }
        //print render($content['field_stc_mem']);
    }
    print render( $content );
    $sql = "SELECT field_signatory_value AS val
            FROM field_data_field_signatory
            WHERE entity_id = $nid
            ORDER BY delta";
    $res = db_query( $sql ); $out = "";
    foreach( $res as $row ) {
        $out .= $row->val . ", ";
    }
    $out = rtrim( $out, "\n\ ," );
    if ( $out != "" ) {
        echo "<div class=\"field field-name-field-deposit-date field-type-datetime field-label-above\"></div>
            <strong>Party to: </strong>
            
                &nbsp;&nbsp;$out
            ";
    }
    print render( $content['field_protected_areas'] );
    //echo "</div>";

    //echo "<div style=\"clear : both;\"></div>";

    $sql = "SELECT tlbcou.title AS name, tlbfp.uri, field_natrep_group_value AS bkey, field_natrep_year_value AS syear,
                        CONCAT( field_natrep_year_value, IF( ISNULL( field_natrep_byear_value ), '', field_natrep_byear_value ) )  AS byear,
                        tlbpdf.uri AS bfile, CONCAT( '(', field_natrep_group_value, ')' ) AS bdescr,
                        tlbcou.nid AS pkey, CONCAT( '(', tlbnrl.field_natrep_lang_value, ')' ) AS lng
                    FROM (SELECT * FROM node WHERE type = 'nat_rep' ) AS tlbnr LEFT JOIN field_data_field_natrep_country AS tlbnrckey ON tlbnr.nid = tlbnrckey.entity_id
                        INNER JOIN node AS tlbcou ON tlbcou.nid = tlbnrckey.field_natrep_country_ebenum
                        INNER JOIN field_data_field_countryp_pr AS tlbcoupr ON tlbcou.nid = tlbcoupr.entity_id
                        LEFT JOIN field_data_field_country_fp AS tlbfpkey ON tlbcou.nid = tlbfpkey.entity_id
                        LEFT JOIN file_managed AS tlbfp ON tlbfp.fid = tlbfpkey.field_country_fp_fid
                        LEFT JOIN field_data_field_natrep_group AS tlbnrg ON tlbnr.nid = tlbnrg.entity_id
                        LEFT JOIN field_data_field_natrep_year AS tlbnry ON tlbnr.nid = tlbnry.entity_id
                        LEFT JOIN field_data_field_natrep_byear AS tlbnrby ON tlbnr.nid = tlbnrby.entity_id
                        LEFT JOIN field_data_field_natrep_pdf AS tlbnrpdfkey ON tlbnr.nid = tlbnrpdfkey.entity_id
                        LEFT JOIN file_managed AS tlbpdf ON tlbpdf.fid = tlbnrpdfkey.field_natrep_pdf_fid
                        LEFT JOIN field_data_field_natrep_lang AS tlbnrl ON tlbnr.nid = tlbnrl.entity_id
                    WHERE ( tlbcou.nid = $node->nid )
                    ORDER BY name, bkey, lng, syear, byear";
    $res = db_query( $sql );
    $sample1 = "<a href=\"@HREF@\">@YEAR@</a>, ";

    $out = ""; $block = -1; $block1 = -1;
    foreach ( $res as $row ) {
        if ( $block1 == -1 )
            $block1 = $row->lng;
        if ( $block != $row->bkey ) {
            //if ( $block != -1 ) $out .= "<br />";
            if ( $block != -1 ) {
                $out = substr( $out, 0, strlen( $out ) - 2 );
                $out .= "; ";
            }
            $out .=  ( ($row->bdescr != "")?$row->bdescr . ":&nbsp;&nbsp;":"");
            $block = $row->bkey;
        }
        if( $block1 != $row->lng ) {
            $out = substr( $out, 0, strlen( $out ) - 2 );
            $out .= " $block1; ";
            $block1 = $row->lng;

        }
        $href = file_create_url( $row->bfile );
        $out .= str_replace( "@YEAR@", $row->byear, str_replace( "@HREF@", $href, $sample1 ) );

    }

    if ( $out != "" ) {
        $out = substr( $out, 0, strlen( $out ) - 2 );
        if( $block1 != "" ) $out .= " $block1; ";
        echo "<div class=\"field field-name-field-deposit-date field-type-datetime field-label-above\"></div><strong>National Reports: </strong>&nbsp;&nbsp;";
        echo $out . "<br />";
    }

    $sql = "SELECT DISTINCT title, nid " .
        "FROM field_data_field_bs_country INNER JOIN node ON ( nid = entity_id ) " .
        "WHERE field_bs_country_ebenum = " . $node->nid . " " .
        "ORDER BY title ";
    $res = db_query( $sql );
    $sample1 = "<a href=\"@HREF@\">@NAME@</a>, ";

    $out = "";
    foreach ( $res as $row ) {
        $bsurl = "/" . drupal_get_path_alias( "node/" . $row->nid );
        $out .= str_replace( "@HREF@", $bsurl, str_replace( "@NAME@", $row->title, $sample1 ) );
    }
    if ( $out != "" ) {
        $out = substr( $out, 0, strlen( $out ) - 2 );
        echo "<div style=\"height : 7px; width : 100%;\"></div><strong>Occurring Species:</strong><i> ";
        echo $out . "</i><br />";
    }

    $sql = "SELECT entity_id " .
    "FROM field_data_field_bco_country " .
    "WHERE field_bco_country_ebenum = " . $node->nid;
    $res = db_query( $sql );

    $out = "";
    foreach ( $res as $row ) {
        $e_id = $row->entity_id;
        break;
    }
//!------------------------------
//! IBN Event
/*SELECT DISTINCT title, lattbl.field_ibn_latitude_value AS lat, lngtbl.field_ibn_longitude_value AS lng
            , nid, field_ibn_event_date_value AS s_date, field_ibn_event_date_value2 AS e_date, field_ibn_event_org_value AS org,
            field_ibn_event_descr_value AS descr, field_ibn_event_contact_value AS contact, field_ibn_event_addr_value AS addr,
            field_ibn_event_email_value AS email, file_managed.uri AS rep, field_ibn_event_repl_value AS repl, country_name,
            field_ibn_event_location_value AS loc, field_ibn_web_value AS web
            FROM ( ( ( ( ( ( ( ( ( ( ( ( ( ( ( ( node INNER JOIN field_data_field_ibn_latitude AS lattbl ON nid = lattbl.entity_id )
                INNER JOIN field_data_field_ibn_longitude AS lngtbl ON nid = lngtbl.entity_id )
                LEFT JOIN field_data_field_ibn_country AS ecou ON nid = ecou.entity_id )
                LEFT JOIN field_data_field_ibn_event_type AS etype ON nid = etype.entity_id )
                LEFT JOIN field_data_field_ibn_event_location AS eloc ON nid = eloc.entity_id )
                LEFT JOIN field_data_field_ibn_event_rep AS erep ON nid = erep.entity_id )
                LEFT JOIN field_data_field_ibn_event_repl AS erepl ON nid = erepl.entity_id )
                LEFT JOIN field_data_field_ibn_event_date AS edate ON nid = edate.entity_id )
                LEFT JOIN field_data_field_ibn_event_org AS eorg ON nid = eorg.entity_id )
                LEFT JOIN field_data_field_ibn_event_descr AS edescr ON nid = edescr.entity_id )
                LEFT JOIN field_data_field_ibn_event_contact AS econtact ON nid = econtact.entity_id )
                LEFT JOIN field_data_field_ibn_event_addr AS eaddr ON nid = eaddr.entity_id )
                LEFT JOIN field_data_field_ibn_event_email AS eemail ON nid = eemail.entity_id )
                LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id )
                LEFT JOIN field_data_field_ibn_web AS tlbweb ON nid = tlbweb.entity_id )
                LEFT JOIN file_managed ON erep.field_ibn_event_rep_fid = file_managed.fid )
                LEFT JOIN (SELECT title AS country_name, nid AS pkey FROM node WHERE type = 'country_profiles' ) AS ecou ON field_ibn_country_ebenum = ecou.pkey
            WHERE ( type = 'ibn_event' ) AND ( node.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 )*/
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
            WHERE ( type = 'ibn_event' ) AND ( node.status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 0 )
                AND ( field_ibn_country_ebenum = $nid )
            ORDER BY s_date, title";
    $sqlTypes = "SELECT field_ibn_event_type_value AS type FROM field_data_field_ibn_event_type WHERE entity_id = @NID@";
    $res = db_query( $sql );
    $eventArray = Array();
    $reportArray = Array();
    $i = 0; $j = 0;
    foreach ( $res as $row ) {
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
        $rep = (( $row->rep != "" )?file_create_url( $row->rep ):(($row->repl != "")?$row->repl:""));
        if ( $rep != "" )
            if ( strpos( substr( $rep, 0, 7 ), "http://" ) === false ) $rep = "http://" . $rep;
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
                "ann" => $row->announce,
                "anndescr" => $row->anndescr ); $i++;
        if ( $rep != "" ) {
            //echo "<br /><br />#" . $row->rep . "#" . $row->repl . "#" . $rep . "#<br /><br />";
            $reportArray[ $j ] = Array( "title" => $row->title, "href" => $rep ); $j++;
        }
    }
    $eventCount = count( $eventArray );
    $reportCount = count( $reportArray );
    
    
    
    $eventSample = //"@COUNTRY@" .
    "<table>\n" .
    //"@TITLE@\n" .
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
    //"@REP@\n" .
    "</table>\n";// .
    //"<div style=\"height : 25px; width : 100%;\"></div>\n";

    function eSampleRepl( $title, $pat, $val, $str ) {
        $eventRowSample = "<tr><td style=\"font-weight : bold; width : 115px;\">@TITLE@</td><td>@VAL@</td></tr>";
        return str_replace( $pat, (($val == "")?"":str_replace( "@VAL@", $val, str_replace( "@TITLE@",  $title, $eventRowSample ) )), $str );
    }
    
    if ( $isScol )
        $sample1 = "<li style=\"cursor : pointer;\" onclick=\"onIBNEventClick( @I@ )\">";
    else
        $sample1 = "<li>";

    $out = ""; $jsEventCont = "";
    for ( $i = 0; $i < $eventCount; $i++ ) {
        $row = $eventArray[ $i ];
        $jsEventCont .= "document.getElementById( \"eventdivcont$i\" ),\n";
        $out .= str_replace( "@I@", $i, $sample1 ) . //"<li style=\"cursor : pointer;\" onclick=\"onIBNEventClick( $i )\">" .
                "<span style=\"font-style : italic; font-weight : bold;\">" .
                $row[ "title" ] . "</span>";
        
        $out .= "<div id=\"eventdivcont$i\" style=\"display : " . ($isScol?"none":"block") . "; padding-left : 20px;\">";
        
        $str = $eventSample;
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
        
        $ann = (( $row[ "ann" ] != "" )?file_create_url( $row[ "ann" ] ) . "\" target=\"_blank\">" . (($row[ "anndescr" ] != "")?$row[ "anndescr" ]:"[Read on]"):"");
        $ann = (( $ann!= "")?"<a href=\"" . $ann . "</a>":"");
        $str = eSampleRepl( "Announce:", "@ANN@", $ann, $str );

        $out .= $str;
        
        
        //$out .= "asdasd";
        
        $out .= "</div></li>";
    }
    if ( $out != "" ) {
        echo "<div style=\"height : 7px; width : 100%\"></div><strong>IBN Events:</strong> ";
        echo "<ul>" . $out . "</ul>";  /*<div style=\"height : 7px; width : 100%\"></div>*/
       
?>
<script type="text/javascript">
var eventDivs = [ <?php echo rtrim( $jsEventCont, ",\n" ); ?> ];
function onIBNEventClick( i ) {
    if ( eventDivs[ i ].style.display == "none" )
        eventDivs[ i ].style.display = "block";
    else
        eventDivs[ i ].style.display = "none";
}
</script>
<?php
    }
//! IBN Reports
    $out = "";
    for ( $i = 0; $i < $reportCount; $i++ ) {
        $row = $reportArray[ $i ];
        $out .= "<li><a href=\"" . $row[ "href" ] . "\" target=\"_blank\">" . $row[ "title" ] . "</a></li>";
    }
    if ( $out != "" ) {
        echo "<div style=\"height : 7px; width : 100%\"></div><strong>IBN Reports:</strong> ";
        echo "<ul>" . $out . "</ul>";   /*<div style=\"height : 7px; width : 100%\"></div>*/
    }
//!------------------------------
//! Summaries
    $sql = "SELECT DISTINCT title, nid
            FROM node INNER JOIN field_data_field_sum_cou AS tlbcou ON nid = tlbcou.entity_id
            WHERE ( type = 'summary' ) AND ( status = 1 ) AND ( field_sum_cou_ebenum = $nid )
            ORDER BY title";
    $out = "";
    $res = db_query( $sql );
    foreach ( $res as $row ) {
        $out .= "<li><a href=\"" . "/" . drupal_get_path_alias( "node/" . $row->nid ) . "\" >" . $row->title . "</a></li>";
        //$out .= "<li><a href=\"" . "/" . drupal_get_path_alias( "node/" . $row->nid ) . "?mode=lightframe\" rel=\"lightframe[Summaries|width:800px; height:600px;][" . $row->title . "]\" >" . $row->title . "</a></li>";
    }
    if ( $out != "" ) {
        echo "<div style=\" height: 7px; width : 100%; \"></div><strong>Summaries of conducted projects:</strong> ";
        echo "<ul>" . $out . "</ul>";    /*<div style=\"height : 7px; width : 100%\"></div>*/
    }
//!------------------------------
//! Консервные заводы
    $out = "";
    include( "inc/php/bco.php");
    if ( isset( $e_id ) ) {
        ob_start();
        out_bco( $e_id, 2 );
        $out = ob_get_clean();
    }

    if ( $out != "" ) {
        echo "<div style=\" height: 7px; width : 100%; \"></div><strong>Bat Conservation Organization(s):</strong> ";
        echo $out . "";// . "<br />";
    }
    echo "<br />";
    
    /* SELECT title
FROM node INNER JOIN field_data_field_ibn_country ON nid = entity_id
#WHERE field_ibn_country_ebenum = 179*/


    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
