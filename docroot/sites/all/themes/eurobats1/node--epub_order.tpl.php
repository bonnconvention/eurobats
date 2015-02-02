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

$errMsg = Array(
        "First Name: ",
        "Last Name: ",
        "Address: ",
        "Postal code: ",
        "City: ",
        "Country: ",
        "E-mail: ",
        "Telephone: ",
        "Nothing to order!"
);

$jsmsg_post_msg  = "Thank you for the order!";

$mail_msg = "<html>\n" .
            "<body>\n" .
            "<h3>Publication Order Form</h3>\n" .
            "<strong>First Name:</strong> @FNAME@ <strong>Last Name:</strong> @LNAME@<br />\n" .
            "<strong>Address:</strong> @ADDRESS@<br />\n" .
            "<strong>Postal code:</strong> @PCODE@<br />\n" .
            "<strong>City:</strong> @CITY@<br />\n" .
            "<strong>Country:</strong> @COUNTRY@<br />\n" .
            "<strong>e-mail:</strong> @MAIL@ <strong>Telephone:</strong> @TEL@<br />\n" .
            "@MESG@\n" .
            "<br />@ORDER@\n" .
            "</body>\n" .
            "</html>\n";

$mail_header = "From: \"Publication Order\" <eurobats@eurobats.org>\r\n" .
                        "Content-type: text/html; charset=\"utf-8\"\r\n";

$jsmsg_post = "";

$err_order = "";
$err_order_sample = "<p style=\"font-weight : bold;\">@TEXT@</p>";

$jsErrMsg = "";
for ( $i = 0; $i < count( $errMsg ); $i++ ) {
    $jsErrMsg .=  "\"" . escapeString( $errMsg[ $i ] ) . "\",\n";
}
$jsErrMsg = "[ " . substr( $jsErrMsg, 0, strlen( $jsErrMsg ) - 2 ) . " ]";

@ $formid = 0 + $_POST[ "formid" ];
if ( $formid == 1 ) {


@ $post_val0 = "" . $_POST[ "fName" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 0 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "lName" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 1 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "addr" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 2 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "pcode" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 3 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "city" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 4 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "country" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 5 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "email1" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 6 ], $err_order_sample );

@ $post_val0 = "" . $_POST[ "tel" ];
if ( strlen( $post_val0 ) < 3 ) $err_order .= str_replace( "@TEXT@", $errMsg[ 7 ], $err_order_sample );

$postKeys = array_keys( $_POST );
$pubsArray = Array();
//echo count( $pubsArray ). "\n";
for ( $i = 0; $i < count( $postKeys ); $i++ ) {
    $key = $postKeys[ $i ];
    if ( ( strpos( $key, "spub" ) !== false ) || ( strpos( $key, "lpub" ) !== false ) || ( strpos( $key, "opub" ) !== false ) ) {
        //echo $item."\n";
        $val = $_POST[ $key ];
        if ( $val != "" ) {
            $key1 = strrchr( $key, "_" );
            $key = str_replace( $key1, "", $key );
            $lang = strrchr( $key, "_" );
            $key = str_replace( $lang, "", $key );
            $key1 = substr( $key1, 1, strlen( $key1 ) - 1 );
            $lang = substr( $lang, 1, strlen( $lang ) - 1 );
            //$key1 = substr( )
            //echo "$val -- $key1, $lang, $key\n";
            if ( !isset( $pubsArray[ $key ] ) ) $pubsArray[ $key ] = Array();
            if ( !isset( $pubsArray[ $key ][ $key1 ] ) ) $pubsArray[ $key ][ $key1 ] = Array();
            $pubsArray[ $key ][ $key1 ][ $lang ] = $val;
        }
    }
}

if ( count( $pubsArray ) == 0 )
    $err_order .= str_replace( "@TEXT@", $errMsg[ 8 ], $err_order_sample );
//!!!!!!!!!!!!!!!!!!!!!!!!!!
//$err_order = "";
//!!!!!!!!!!!!!!!!!!!!!!!!!!
if ( $err_order == "" ) {
    $msgtext = "";
    if ( isset( $pubsArray[ "spub" ] ) ) {
        $msgtext .= "<h3>EUROBATS Publication Series</h3>\n";
        $keys = array_keys( $pubsArray[ "spub" ] );
        for ( $i = 0; $i < count( $keys ); $i++ ) {
            $no = db_query( "SELECT field_epub_no_value AS no FROM field_data_field_epub_no WHERE entity_id = " . $keys[ $i ] )->fetchField();
            $keys1 = array_keys( $pubsArray[ "spub" ][ $keys[ $i ] ]  );
            for ( $j = 0; $j < count( $keys1 ); $j++ ) {
                $msgtext .= "No. $no " . $keys1[ $j ] . " - " . $pubsArray[ "spub" ][ $keys[ $i ] ][ $keys1[ $j ] ] . "pcs.<br />\n";
            }
        }
        $msgtext .= "<br />\n";
    }
    // -------------------------------------------------------------------------
    if ( isset( $pubsArray[ "lpub" ] ) ) {
        $msgtext .= "<h3>EUROBATS leaflets</h3>\n";
        $keys = array_keys( $pubsArray[ "lpub" ] );
        for ( $i = 0; $i < count( $keys ); $i++ ) {
            $no = db_query( "SELECT title, nid FROM node WHERE nid = " . $keys[ $i ] )->fetchField();
            $keys1 = array_keys( $pubsArray[ "lpub" ][ $keys[ $i ] ]  );
            for ( $j = 0; $j < count( $keys1 ); $j++ ) {
                $msgtext .= "$no " . $keys1[ $j ] . " - " . $pubsArray[ "lpub" ][ $keys[ $i ] ][ $keys1[ $j ] ] . "pcs.<br />\n";
            }
        }
        /*if ( isset( $pubsArray[ "lpub" ][ "bat" ] ) ) {
            $no = "Bat leaflet";
            $keys1 = array_keys( $pubsArray[ "lpub" ][ "bat" ]  );
            for ( $j = 0; $j < count( $keys1 ); $j++ ) {
                $msgtext .= "$no " . $keys1[ $j ] . " - " . $pubsArray[ "lpub" ][ "bat" ][ $keys1[ $j ] ] . "pcs.<br />\n";
            }
        }
        if ( isset( $pubsArray[ "lpub" ][ "for" ] ) ) {
            $no = "Bat and forestry leaflet";
            $keys1 = array_keys( $pubsArray[ "lpub" ][ "for" ]  );
            for ( $j = 0; $j < count( $keys1 ); $j++ ) {
                $msgtext .= "$no " . $keys1[ $j ] . " - " . $pubsArray[ "lpub" ][ "for" ][ $keys1[ $j ] ] . "pcs.<br />\n";
            }
        }*/
        $msgtext .= "<br />\n";
    }
    // -------------------------------------------------------------------------
    if ( isset( $pubsArray[ "opub" ] ) ) {
        $msgtext .= "<h3>Other Available Publications</h3>\n";
        $keys = array_keys( $pubsArray[ "opub" ] );
        for ( $i = 0; $i < count( $keys ); $i++ ) {
            $no = db_query( "SELECT title AS no FROM node WHERE nid = " . $keys[ $i ] )->fetchField();
            $keys1 = array_keys( $pubsArray[ "opub" ][ $keys[ $i ] ]  );
            for ( $j = 0; $j < count( $keys1 ); $j++ ) {
                $msgtext .= "$no " . $keys1[ $j ] . " - " . $pubsArray[ "opub" ][ $keys[ $i ] ][ $keys1[ $j ] ] . "pcs.<br />\n";
            }
        }
        $msgtext .= "<br />\n";
    }
    //echo $msgtext;

    $sql = "SELECT field_epuborder_tmail_value AS mailr
            FROM node INNER JOIN field_data_field_epuborder_tmail ON nid = entity_id
            WHERE node.type = 'epub_order'";
    $mail = db_query( $sql )->fetchField();
    @ $post_val0 = "" . $_POST[ "fName" ];
    @ $post_val1 = "" . $_POST[ "lName" ];
    $mail_msg = str_replace( "@FNAME@", $post_val0, str_replace( "@LNAME@", $post_val1, $mail_msg ) );

    @ $post_val0 = "" . $_POST[ "addr" ];
    @ $post_val1 = "" . $_POST[ "pcode" ];
    $mail_msg = str_replace( "@ADDRESS@", $post_val0, str_replace( "@PCODE@", $post_val1, $mail_msg ) );

    @ $post_val0 = "" . $_POST[ "city" ];
    @ $post_val1 = "" . $_POST[ "country" ];
    $mail_msg = str_replace( "@CITY@", $post_val0, str_replace( "@COUNTRY@", $post_val1, $mail_msg ) );

    @ $post_val0 = "" . $_POST[ "email1" ];
    @ $post_val1 = "" . $_POST[ "tel" ];
    $mail_msg = str_replace( "@MAIL@", $post_val0, str_replace( "@TEL@", $post_val1, $mail_msg ) );

    $mail_msg = str_replace( "@ORDER@", $msgtext, $mail_msg );
    
    @ $post_val0 = "" . $_POST[ "mesg" ];
    if ( $post_val0 != "" )
        $mail_msg = str_replace( "@MESG@", "<strong>Message: </strong>" . $post_val0 . "<br />", $mail_msg );
    else
        $mail_msg = str_replace( "@MESG@", "", $mail_msg );
    //echo  $mail . "<br />" . $mail_msg;
    mail( $mail, "Publications Order", $mail_msg, $mail_header );
    $jsmsg_post = $jsmsg_post_msg;

    //echo $mail_msg;

}

//echo "<textarea style=\"height : 200px; width : 100%\">";
//print_r( $pubsArray );
//echo "</textarea>";
//echo  $mail . "<br />" . $mail_msg;
}
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
    ?>
<br />
<span style="color:#ff0000;"><strong>* mandatory fields</strong></span>
<br />
<div id="errorMessages1">
<?php echo $err_order; ?>
</div>
<br />
    <p><b>Contact Details</b></p>
<form method="post" action="" id="form1">
<input type="hidden" value="1" name="formid">



<table style=" width : 100%; table-layout : fixed;">
    <tr>
        <td style="width : 90px;">First Name: <span style="color : #FF0000 ">*</span></td><td><input type="text" style=" width : 355px;" name="fName" /></td>
        <td style="width : 90px;">&nbsp;&nbsp;Last Name: <span style="color : #FF0000 ">*</span></td><td style=" text-align : right;"><input type="text" style=" width : 370px;" name="lName" /></td>
    </tr>
    <tr>
        <td>Address: <span style="color : #FF0000 ">*</span></td><td colspan="3" style="padding-right : 4px;"><input type="text" class="event_addr" name="addr" /></td>
    </tr>
    <tr>
        <td>Postal code: <span style="color : #FF0000 ">*</span></td><td><input type="text" style=" margin-left : 0px; width : 355px;" name="pcode"/></td>
        <td>&nbsp;&nbsp;City: <span style="color : #FF0000 ">*</span></td><td style=" text-align : right;"><input type="text" style=" align : right; width : 370px;" name="city" /></td>
    </tr>
    <tr>
        <td>Country: <span style="color : #FF0000 ">*</span></td><td colspan="3"><input type="text" style=" width : 355px;" name="country" /></td>
    </tr>
    <tr>
        <td>E-mail: <span style="color : #FF0000 ">*</span></td><td><input type="text" style=" width : 355px;" name="email1" /></td>
        <td>&nbsp;&nbsp;Telephone: <span style="color : #FF0000 ">*</span> </td><td style=" text-align : right;"><input type="text" style=" width : 370px;" name="tel"/></td>
    </tr>
    <tr>
        <td style="vertical-align : top;">Message: </td><td colspan="3" style="padding-right : 4px;">
        <textarea rows="3" style="width : 840px;" name="mesg"> </textarea>
        </td>
    </tr>
</table>

<!--

<table style=" width : 100%; ">
    <tr>
        <td class="col1_3">First Name: <span style="color : #FF0000 ">*</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style=" width : 75%;" name="fName" /></td>
        <td class="col1_3">Last Name: <span style="color : #FF0000 ">*</span>&nbsp;&nbsp;<input type="text" style=" float : right; width : 78%;" name="lName" /></td>
        
    </tr>
    <tr>
        <td colspan="2">Address: <span style="color : #FF0000 ">*</span>&nbsp;&nbsp;<input type="text" style=" float : right; width : 89%;" name="addr" /></td>
                
    </tr>
    <tr>
        <td class="col1_3">Postal code: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp;<input type="text" style=" width : 75%;" name="pcode"/></td>
        <td class="col1_3" >City: <span style="color : #FF0000 ">*</span>&nbsp;&nbsp;<input type="text" style=" float : right; width : 85%;" name="city" /></td>
        
    </tr>
    <tr>
        <td class="col1_3">Country:  <span style="color : #FF0000 ">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" style=" width : 60%;" name="country" /></td>
        <td class="col1_3" >&nbsp; </td>
        
    </tr>
    <tr>
        <td class="col1_3">E-mail: <span style="color : #FF0000 ">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style=" width : 75%;" name="email1"/></td>
        <td class="col1_3" >Telephone:  <span style="color : #FF0000 ">*</span>&nbsp;&nbsp; <input type="text" style=" float : right; width : 80%;" name="tel"/></td>
        
    </tr>
    

</table>

-->

<div id="prepubdiv" style="width : 100%">&nbsp;</div>
<?php
    $sample1 = "@LANG@&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"@NAME@\" name=\"@NAME@\" value=\"\" style=\"width : 20px; margin : 2px;\"/><br />";
    $sample1_1 = "<tr><td style=\"padding : 0px;\">@LANG@</td><td style=\"padding : 0px;\">&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"@NAME@\" name=\"@NAME@\" value=\"\" style=\"width : 20px;\"/></td></tr>";

    $sample2 = "<div style=\"float : left; text-align : left; width : 150px;\"><img src=\"@IMG@\" alt=\"\" /><br />@LANG@</div>";
    $sample3 = "<div style=\"float : left; text-align : left; width : 50%\"><img src=\"@IMG@\" alt=\"\" style=\"float : left; margin-right : 20px;\"/><p style=\"width : 70%;\">@TEXT@</p><br/>@LANG@</div>";
    $sample4 = "<div style=\"clear : both; height : 20px;\">&nbsp;</div>";
    
    $sample20 = "<div style=\"clear : both;\"></div><div class=\"separator\">&nbsp;</div>";
    $names_array = Array(); $name_index = 0;

    $name_prefix = "spub";
    $sql = "SELECT field_epub_no_value AS no, uri AS cov, nid
            FROM node INNER JOIN field_data_field_epub_no AS tlbno ON nid = tlbno.entity_id
                INNER JOIN field_data_field_ebup_cover AS tlbcov ON nid = tlbcov.entity_id
                INNER JOIN file_managed ON field_ebup_cover_fid = fid
            WHERE ( node.type = 'epubs' ) AND ( node.status = 1 )
            ORDER BY no DESC";
    $res = db_query( $sql );
    $out = ""; $i = 0;
    foreach( $res as $row ) {
        $sql1 = "SELECT field_epub_lang_value AS lng
                FROM field_data_field_epub_lang
                WHERE entity_id = " . $row->nid ."
                ORDER BY lng";
        $sql1 = "SELECT UPPER( tlblng.lngcode ) AS lng
                FROM field_data_field_epub_lng AS tlbcol LEFT JOIN field_data_field_ebub_lng_lang AS tlblngid ON tlbcol.field_epub_lng_value = tlblngid.entity_id
                    LEFT JOIN lngiso639 AS tlblng ON tlblngid.field_ebub_lng_lang_ebenum = tlblng.pkey
                    LEFT JOIN field_data_field_ebub_lng_hardcopy AS tlbhc ON tlbcol.field_epub_lng_value = tlbhc.entity_id
                WHERE ( tlbcol.entity_id = " . $row->nid ." ) AND ( field_ebub_lng_hardcopy_value = 'Yes' )
                ORDER BY tlbcol.delta";
        $res1 = db_query( $sql1 );
        $lng = "";
        foreach( $res1 as $row1 ) {
            $names_array[ $name_index ] = $name_prefix . "_" . $row1->lng . "_" . $row->nid;//$name_index;
            $lng .= str_replace( "@LANG@", $row1->lng, str_replace( "@NAME@", $names_array[ $name_index ], $sample1_1 ) );
            $name_index++;
        }
        if ( $lng != "" ) {
            $lng = "<table>$lng</table>";
            $out .= str_replace( "@IMG@", file_create_url( $row->cov ), str_replace( "@LANG@", $lng, $sample2 ) );
            $i++;
        }
        if ( $i == 6 ) {
            $i = 0;
            $out .= $sample4;
        }
    }
    print $sample20;
    print "<br /><h3>EUROBATS Publication Series</h3><br />";
    print $out;
    print $sample20;

/*
*  Leaflet
*
*/
    $name_prefix = "lpub"; $out = "";
    $sql1 = "SELECT tlbcovuri.uri AS covuri, lngiso639.lngname AS lang, UPPER( lngiso639.lngcode ) AS lngcode, tlbpdfuri.uri AS pdfuri, field_eleaflet_hardcopy_value, tlbcoll.field_eleaflets_value AS pkey
                FROM field_data_field_eleaflets AS tlbcoll LEFT JOIN field_data_field_eleaflet_cover AS tlbcov ON tlbcoll.field_eleaflets_value = tlbcov.entity_id
                        LEFT JOIN file_managed AS tlbcovuri ON tlbcov.field_eleaflet_cover_fid = tlbcovuri.fid
                    LEFT JOIN field_data_field_eleaflet_lng AS tlblng1 ON tlbcoll.field_eleaflets_value = tlblng1.entity_id
                    LEFT JOIN lngiso639 ON tlblng1.field_eleaflet_lng_ebenum = lngiso639.pkey
                    LEFT JOIN field_data_field_eleaflet_pdf AS tlbpdf ON tlbcoll.field_eleaflets_value = tlbpdf.entity_id
                        LEFT JOIN file_managed AS tlbpdfuri ON tlbpdf.field_eleaflet_pdf_fid = tlbpdfuri.fid
                    LEFT JOIN field_data_field_eleaflet_hardcopy AS tlbhc ON tlbcoll.field_eleaflets_value = tlbhc.entity_id
                WHERE ( field_eleaflet_hardcopy_value = 'Yes' ) AND ( tlbcoll.entity_id = @ID@ )
                ORDER BY tlbcoll.delta";
    $sql = "SELECT nid, field_eleaflets_descr_of_value AS descr
            FROM node LEFT JOIN field_data_field_eleaflets_descr_of AS tlbdll ON nid = tlbdll.entity_id
                LEFT JOIN field_data_field_eleaflets_orderno AS tlbon ON nid = tlbon.entity_id
            WHERE type = 'eleaflet'
            ORDER BY field_eleaflets_orderno_value";
    $res = db_query( $sql ); $i = 0;
    foreach( $res as $row ) {
        $res1 = db_query( str_replace( "@ID@", $row->nid, $sql1 ) );
        $lng = "";
        $out1 = "";
        foreach( $res1 as $row1 ) {
            if ( $out1 == "" )
                $out1 = str_replace( "@IMG@", file_create_url( $row1->covuri ), $sample3 );
            $names_array[ $name_index ] = $name_prefix . "_" . $row1->lngcode . "_" . $row->nid;//$name_index;
            $lng .= str_replace( "@LANG@", $row1->lngcode, str_replace( "@NAME@", $names_array[ $name_index ], $sample1_1 ) );
            $name_index++;
        }
        if ( $lng != "" ) {
            $lng = "<table>$lng</table>";
            $out .= str_replace( "@LANG@", $lng, str_replace( "@TEXT@", $row->descr, $out1 ) );
            $i++;
        }
        if ( $i == 2 ) {
            $i = 0;
            $out .= $sample4;
        }
    }
    print "<br /><h3>EUROBATS Leaflets</h3><br />";
    print $out;
    print $sample20;
//!=======================================================================================
//! OLD =========================
//!=======================================================================================
    /*$name_prefix = "lpub"; $out = "";
    $sql = "SELECT field_eleaflets_bat_value, uri, field_eleaflet_lang_value AS lng
            FROM node LEFT JOIN field_data_field_eleaflets_bat AS tlbbatl ON nid = tlbbatl.entity_id
                LEFT JOIN field_data_field_eleaflet_cover AS tlbcov ON field_eleaflets_bat_value = tlbcov.entity_id
                LEFT JOIN file_managed ON tlbcov.field_eleaflet_cover_fid = fid
                LEFT JOIN field_data_field_eleaflet_lang AS tlblang ON field_eleaflets_bat_value = tlblang.entity_id
            WHERE ( type='eleaflet_list' ) AND ( field_eleaflet_lang_value IN ( 'EN', 'FR', 'DE' ))
            ORDER BY lng";
    $res = db_query( $sql );
    $lng = "";
    $out1 = "";
    foreach( $res as $row ) {
        if ( $out1 == "" )
            $out1 = str_replace( "@IMG@", file_create_url( $row->uri ), $sample3 );
        $names_array[ $name_index ] = $name_prefix . "_" . $row->lng . "_bat";//$name_index;
        $lng .= str_replace( "@LANG@", $row->lng, str_replace( "@NAME@", $names_array[ $name_index ], $sample1 ) );
        $name_index++;
    }
    //$out .= str_replace( "@LANG@", $lng, $out1 );
    $out .= str_replace( "@LANG@", $lng, str_replace( "@TEXT@", "Leaflet on the EUROBATS Agreement and bats in general", $out1 ) );
    
    $sql = "SELECT field_eleaflets_for_value, uri, field_eleaflet_lang_value AS lng
            FROM node LEFT JOIN field_data_field_eleaflets_for AS tlbforl ON nid = tlbforl.entity_id
                LEFT JOIN field_data_field_eleaflet_cover AS tlbcov ON field_eleaflets_for_value = tlbcov.entity_id
                LEFT JOIN file_managed ON tlbcov.field_eleaflet_cover_fid = fid
                LEFT JOIN field_data_field_eleaflet_lang AS tlblang ON field_eleaflets_for_value = tlblang.entity_id
            WHERE ( type='eleaflet_list' ) AND ( field_eleaflet_lang_value IN ( 'EN', 'FR', 'DE' ))
            ORDER BY lng";
    $res = db_query( $sql );
    $lng = "";
    $out1 = "";
    foreach( $res as $row ) {
        if ( $out1 == "" )
            $out1 = str_replace( "@IMG@", file_create_url( $row->uri ), $sample3 );
        //$names_array[ $name_index ] = $name_prefix . $name_index;
        $names_array[ $name_index ] = $name_prefix . "_" . $row->lng . "_for";
        $lng .= str_replace( "@LANG@", $row->lng, str_replace( "@NAME@", $names_array[ $name_index ], $sample1 ) );
        $name_index++;
    }
    $out .= str_replace( "@LANG@", $lng, str_replace( "@TEXT@", "EUROBATS leaflet on \"Bat and Forestry", $out1 ) );

    print "<br /><h3>EUROBATS Leaflets</h3><br />";
    print $out;
    print $sample20;*/

/*
*  Other Publication
*
*/

    $name_prefix = "opub";
    $sql = "SELECT title, uri AS cov, nid
            FROM node INNER JOIN field_data_field_eotherpub_cover AS tlbcov ON nid = tlbcov.entity_id
                INNER JOIN file_managed ON field_eotherpub_cover_fid = fid
            WHERE ( node.type = 'eotherpubs' ) AND ( node.status = 1 )";
    $res = db_query( $sql );
    $out = ""; $i = 0;
    foreach( $res as $row ) {
        $sql1 = "SELECT field_epub_lang_value AS lng
                FROM field_data_field_epub_lang
                WHERE entity_id = " . $row->nid ."
                ORDER BY lng";
        $sql1 = "SELECT UPPER( tlblng.lngcode ) AS lng
                FROM field_data_field_epub_lng AS tlbcol LEFT JOIN field_data_field_ebub_lng_lang AS tlblngid ON tlbcol.field_epub_lng_value = tlblngid.entity_id
                    LEFT JOIN lngiso639 AS tlblng ON tlblngid.field_ebub_lng_lang_ebenum = tlblng.pkey
                    LEFT JOIN field_data_field_ebub_lng_hardcopy AS tlbhc ON tlbcol.field_epub_lng_value = tlbhc.entity_id
                WHERE ( tlbcol.entity_id = " . $row->nid ." ) AND ( field_ebub_lng_hardcopy_value = 'Yes' )
                ORDER BY tlbcol.delta";
        $res1 = db_query( $sql1 );
        $lng = "";
        foreach( $res1 as $row1 ) {
            //$names_array[ $name_index ] = $name_prefix . $name_index;
            $names_array[ $name_index ] = $name_prefix . "_" . $row1->lng . "_" . $row->nid;
            $lng .= str_replace( "@LANG@", $row1->lng, str_replace( "@NAME@", $names_array[ $name_index ], $sample1 ) );
            $name_index++;
        }
        if ( $lng != "" ) {
            $out .= str_replace( "@IMG@", file_create_url( $row->cov ), str_replace( "@TEXT@", $row->title, str_replace( "@LANG@", $lng, $sample3 ) ) );
            $i++;
        }
        if ( $i == 2 ) {
            $i = 0;
            $out .= $sample4;
        }
    }


    print "<br /><h3>Other Available Publications</h3><br />";
    print $out;
    print $sample20;



$jsInputNames = "";
$jsInputNames1 = "";
for ( $i = 0; $i < count( $names_array ); $i++ ) {
    $jsInputNames   .=  "\"" . escapeString( $names_array[ $i ] ) . "\",\n";
    $jsInputNames1  .=  "form1." . escapeString( $names_array[ $i ] ) . ",\n";
}
$jsInputNames = "[ " . substr( $jsInputNames, 0, strlen( $jsInputNames ) - 2 ) . " ]";
$jsInputNames1 = "[ " . substr( $jsInputNames1, 0, strlen( $jsInputNames1 ) - 2 ) . " ]";



?>
<div style="clear : both; height : 50px;">&nbsp;</div>
<input class="ebbtn" type="submit" value="Submit" />
</form>
<br />
<script type="text/javascript">
// ------------------------------------------------------------------------
//  
// ------------------------------------------------------------------------
function bodyScrollTop( Top ) {
    if ( document.documentElement.scrollTop > document.body.scrollTop )
        document.documentElement.scrollTop = Top;
    else
        document.body.scrollTop = Top;
}

// ------------------------------------------------------------------------
//  
// ------------------------------------------------------------------------
function checkEmail( email ) {
    var n = email.indexOf( "@" );
    return (( n > 0 ) && ( n < email.length - 4 ));
}

var form1 = document.getElementById( "form1" );
// ------------------------------------------------------------------------
//  
// ------------------------------------------------------------------------
form1.onsubmit = function () {
    var inames = <?php echo $jsInputNames; ?>;
    var inames1 = <?php echo $jsInputNames1; ?>;
    var errMsg = <?php echo $jsErrMsg; ?>;


    var dy = 100;
    var errSample = "<p style=\"cursor : pointer; font-weight : bold;\" onclick=\"bodyScrollTop( getTop( @OBJ@ ) - "+dy+" );\">@TEXT@</p>"
    var errDiv = document.getElementById( "errorMessages1" );
    var errors = 0;
    var firstObj = null;
    var ncolor = "#ffffff";
    var ecolor = "#ffc0c0";

    errDiv.innerHTML = "";


    function processError( cond, objs, objStr, errMsg ) {
        for ( i = 0; i < objs.length; i++ ) objs[ i ].style.backgroundColor = ncolor;
        if ( cond ) {
            if( firstObj == null ) firstObj = objs[ 0 ];
            for ( i = 0; i < objs.length; i++ ) objs[ i ].style.backgroundColor = ecolor;
            errDiv.innerHTML += errSample.replace( "@OBJ@", objStr ).replace( "@TEXT@", errMsg );
            errors++;
        }
    }

    processError( ( form1.fName.value.length < 3 ), [ form1.fName ], "form1.fName", errMsg[ 0 ] );
    processError( ( form1.lName.value.length < 3 ), [ form1.lName ], "form1.lName", errMsg[ 1 ] );
    processError( ( form1.addr.value.length < 3 ), [ form1.addr ], "form1.addr", errMsg[ 2 ] );
    processError( ( form1.pcode.value.length < 3 ), [ form1.pcode ], "form1.pcode", errMsg[ 3 ] );
    processError( ( form1.city.value.length < 3 ), [ form1.city ], "form1.city", errMsg[ 4 ] );
    processError( ( form1.country.value.length < 3 ), [ form1.country ], "form1.country", errMsg[ 5 ] );
    processError( ( !checkEmail( form1.email1.value ) ), [ form1.email1 ], "form1.email1", errMsg[ 6 ] );
    processError( ( form1.tel.value.length < 3 ), [ form1.tel ], "form1.tel", errMsg[ 7 ] );

    ocount = 0;
    for ( i = 0; i < inames1.length; i++ ) {
        v = parseInt( inames1[ i ].value );
        ocount += (isNaN( v )?0:v);
    }
    processError( ( ocount <= 0 ), inames1, "prepubdiv", errMsg[ 8 ] );
    

    if ( errors > 0 ) {
        errDiv.innerHTML = "<p style=\"font-weight : bold;\">Errors : " + errors + "</p>" + errDiv.innerHTML;
        firstObj.focus();
        bodyScrollTop( getTop( errDiv ) - 100 );
        return false;
    }
    return true;
}
</script>

<?php
if ( $jsmsg_post != "" ) {
    ?>
    <script type="text/javascript">
    window.onload = function () { alert( "<?php echo $jsmsg_post; ?>" ); }
    </script>
    <?php
}
?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
