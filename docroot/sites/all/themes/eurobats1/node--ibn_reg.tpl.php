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
        "Event name: length can't be less than 3 symbols!",
        "Event type: at least type should be specified!",
        "Organization Name: length can't be less than 3 symbols!",
        "Select Region: ",
        "Select Country: ",
        "Location / Venue: ",
        "Date: ",
        "Description of your Event: ",
        "Contact E-mail for Event: ",
        "First Name: ",
        "Last Name: ",
        "E-mail: ",
        "Date: одно больше другого",

        "First Name: ",
        "Last Name: ",
        "Address: ",
        "Postal code: ",
        "City: ",
        "Country: ",
        "Number of posters: ",
        "Related Document Link: "
);

$mail_msg_reg = "<html>\n" .
                "<body>\n" .
                "The \"@TITLE@\" new event had been registered.<br />\n" .
                "Link to event page: <h3><a href=\"@HREF@\">@TITLE@</a></h3>\n" .
                "</body>\n" .
                "</html>\n";

$mail_msg_pos = "<html>\n" .
                "<body>\n" .
                "<h3>IBN Poster Order</h3>\n" .
                "<strong>First Name:</strong> @FNAME@ <strong>Last Name:</strong> @LNAME@<br />\n" .
                "<strong>Address:</strong> @ADDRESS@<br />\n" .
                "<strong>Postal code:</strong> @PCODE@<br />\n" .
                "<strong>City:</strong> @CITY@<br />\n" .
                "<strong>Country:</strong> @COUNTRY@<br />\n" .
                "<strong>A2:</strong> @A2@ <strong>A3:</strong> @A3@<br />\n" .
                "</body>\n" .
                "</html>\n";

$mail_header = "From: \"International Bat Night\" <eurobats@eurobats.org>\r\n" .
                        "Content-type: text/html; charset=\"utf-8\"\r\n";

$jsmsg_reg = "Thank you for the IBN Event Registration!";

$jsmsg_pos = "Thank you for the order of IBN Poster!";







$jsmsg_post = "";
$jsErrMsg = "";
for ( $i = 0; $i < count( $errMsg ); $i++ ) {
    $jsErrMsg .=  "\"" . escapeString( $errMsg[ $i ] ) . "\",\n";
}
$jsErrMsg = "[ " . substr( $jsErrMsg, 0, strlen( $jsErrMsg ) - 2 ) . " ]";
$sql = "(SELECT name AS title\n" .
        "FROM ibn_types)\n" .
        "UNION\n" .
        "(SELECT field_ibn_event_type_value AS title\n" .
        "FROM ( field_data_field_ibn_event_type AS etype INNER JOIN node ON nid = etype.entity_id )\n" .
        "LEFT JOIN field_data_field_ibn_event_arc AS earc ON nid = earc.entity_id\n" .
        "WHERE ( status = 1 ) AND ( IF( ISNULL( earc.field_ibn_event_arc_value ), 0, earc.field_ibn_event_arc_value ) = 1 ) )\n" .
        "ORDER BY title\n";


$res = db_query( $sql );
$eventTypes = Array(); $i = 0; $tName = "type"; $jsTypes = "";
foreach ( $res as $row ) {
    $tn = $tName . ($i + 1);
    //$jsTypes .= "document.getElementsByName( \"" . escapeString( $tn ) . "\" )[ 0 ], ";
    $jsTypes .= "document.getElementById( \"" . escapeString( $tn ) . "\" ), ";
    $eventTypes[ $i ][ 0 ] = $row->title;
    $eventTypes[ $i++ ][ 1 ] = $tn;
}
$jsTypes = "[ " . substr( $jsTypes, 0, strlen( $jsTypes ) - 2 ) . " ]";
//echo "<textarea style=\"width : 100%\">";
//echo date( "jS M Y", 1342722708 ) . "\n";
//print_r( $_POST );



@ $formid = 0 + $_POST[ "formid" ];
$err_add_event = ""; $err_add_event_count = 0;
$err_add_event_sample = "<p style=\"font-weight : bold;\">@TEXT@</p>";

function addFld( $fldName, $fldType, $key, $val, $delta = 0 ) {
    //! ==================================================================
    //return;
    //! ==================================================================
    $addVals = "";
    switch ( $fldType ) {
        case 0 :
            $val[ 1 ] = "";
            break;
        case 1 : // text
            $addVals = ", ''";
            $val[ 1 ] = "";
            break;
        case 2 : // Long text
            $addVals = ", 'plain_text'";
            $val[ 1 ] = "";
            break;
            //filtered_html
            //full_html
        case 5 : // date
            $addVals = ", :val1";
            break;
    }
    db_query( "INSERT INTO field_data_field_$fldName VALUES( 'node', 'ibn_event', 0, :nid, :nid, 'und', :delta, :val0$addVals )",
            Array( ":nid" => $key, ":delta" => $delta, ":val0" => $val[ 0 ], ":val1" => $val[ 1 ] ) );
    db_query( "INSERT INTO field_revision_field_$fldName VALUES( 'node', 'ibn_event', 0, :nid, :nid, 'und', :delta, :val0$addVals )",
            Array( ":nid" => $key, ":delta" => $delta, ":val0" => $val[ 0 ], ":val1" => $val[ 1 ] ) );
}

global $base_root;

if ( $formid == 1 ) {
    //function
    @ $post_val0 = "" . $_POST[ "event_name" ];
    if ( strlen( $post_val0 ) < 3 ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 0 ], $err_add_event_sample ); $err_add_event_count++; }

    $post_val0 = "";
    for( $i = 0; $i < count( $eventTypes ); $i++ )
        @ $post_val0 .= "" . $_POST[ $eventTypes[ $i ][ 1 ] ];
    if ( $post_val0 = "" ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 1 ], $err_add_event_sample ); $err_add_event_count++; }

    @ $post_val0 = "" . $_POST[ "orgName" ];
    if ( strlen( $post_val0 ) < 3 ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 2 ], $err_add_event_sample ); $err_add_event_count++; }

    @ $post_val0 = 0 + $_POST[ "regSelect" ];
    if ( $post_val0 == -1 ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 3 ], $err_add_event_sample ); $err_add_event_count++; }

    @ $post_val0 = 0 + $_POST[ "couSelect" ];
    @ $post_val1 = "" . $_POST[ "couType" ];
    if ( ( $post_val0 == -1 ) && ( strlen( $post_val1 ) < 3 ) ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 4 ], $err_add_event_sample );  $err_add_event_count++; }

    @ $post_val0 = "" . $_POST[ "locationv" ];
    if ( strlen( $post_val0 ) < 3 ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 5 ], $err_add_event_sample ); $err_add_event_count++; }

    @ $post_val0 = 0 + $_POST[ "date0y" ];
    @ $post_val1 = 0 + $_POST[ "date0m" ];
    @ $post_val2 = 0 + $_POST[ "date0d" ];
    if ( ( $post_val0 == -1 ) || ( $post_val1 == -1 ) || ( $post_val2 == -1 ) ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 6 ], $err_add_event_sample );  $err_add_event_count++; }

    @ $post_val0 = "" . $_POST[ "event_descr" ];
    if ( strlen( $post_val0 ) < 3 ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 7 ], $err_add_event_sample ); $err_add_event_count++; }

    @ $post_val0 = "" . $_POST[ "event_contact_email" ];
    if ( strlen( $post_val0 ) < 3 ) { $err_add_event .= str_replace( "@TEXT@", $errMsg[ 8 ], $err_add_event_sample ); $err_add_event_count++; }
    
    @ $post_val0 = "" . $_FILES[ "rdlink" ][ "name" ];
    //echo $post_val0 . " # " . $_SERVER[ 'REQUEST_URI' ] . " # " . $_SERVER[ 'SCRIPT_NAME' ] . "<br /><br /><br />";
    if ( $post_val0 != "" ) {
        $AllowFileExt = Array( 
            Array( ".txt", "text/plain" ),
            Array( ".pdf", "application/pdf" ),
            Array( ".doc", "application/msword" ),
            Array( ".docx", "application/vnd.openxmlformats-officedocument.wordprocessingml.document" ),
            Array( ".xls", "application/vnd.ms-excel" ),
            Array( ".xlsx", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ),
            Array( ".jpg", "image/jpeg" ),
            Array( ".jpeg", "image/jpeg" ),
            Array( ".jpe", "image/jpeg" ),
            Array( ".png", "image/png" ) );
        
        $extIndex = -1;
        for ( $i = 0; $i < count( $AllowFileExt ); $i++ ) {
            $ext = stristr( $post_val0, $AllowFileExt[ $i ][ 0 ] );
            if ( strlen( $ext ) == strlen( $AllowFileExt[ $i ][ 0 ] ) ) {
                $filemime = $AllowFileExt[ $i ][ 1 ];
                $extIndex = 1;
                break;
            }
        }
        if ( $extIndex == -1 ) {
            $err_add_event .= str_replace( "@TEXT@", $errMsg[ 20 ], $err_add_event_sample );
            $err_add_event_count++; 
        }

    }

    @ $post_val0 = "" . $_POST[ "fName" ];
    if ( strlen( $post_val0 ) < 3 ) $err_add_event .= str_replace( "@TEXT@", $errMsg[ 9 ], $err_add_event_sample );

    @ $post_val0 = "" . $_POST[ "lName" ];
    if ( strlen( $post_val0 ) < 3 ) $err_add_event .= str_replace( "@TEXT@", $errMsg[ 10 ], $err_add_event_sample );

    @ $post_val0 = "" . $_POST[ "email" ];
    if ( strlen( $post_val0 ) < 3 ) $err_add_event .= str_replace( "@TEXT@", $errMsg[ 11 ], $err_add_event_sample );



    //! ==================================================================
    //$err_add_event = "";
    //! ==================================================================

    if ( $err_add_event == "" ) {
        //ibn_reg_mail_reg
        /*SELECT field_ibn_reg_mail_reg_value AS mailr, field_ibn_reg_mail_poster_value AS mailp
        FROM node INNER JOIN field_data_field_ibn_reg_mail_reg AS mreg ON nid = mreg.entity_id
            INNER JOIN field_data_field_ibn_reg_mail_poster AS mposter ON nid = mposter.entity_id
        WHERE node.type = 'ibn_reg'*/
        $sql = "SELECT field_ibn_reg_mail_reg_value AS mailr
                FROM node INNER JOIN field_data_field_ibn_reg_mail_reg ON nid = entity_id
                WHERE node.type = 'ibn_reg'";
        $mail = db_query( $sql )->fetchField();
        $new_nid = db_query( "SELECT IF( ISNULL(MAX( nid ) + 1), 1, MAX( nid ) + 1 ) FROM node" )->fetchField();

        @ $post_val0 = "" . $_POST[ "event_name" ];
        if ( strlen( $post_val0 ) < 3 ) $err_add_event .= $errMsg . "\n";
        //! ==================================================================
        db_query( "INSERT INTO node VALUES( :nid, :nid, 'ibn_event', 'und', :val, 1, 0, :ts, :ts, 0, 0, 0, 0, 0 )",
                Array( ":nid" => $new_nid, ":val" => $post_val0, ":ts" => time() ));
        db_query( "INSERT INTO node_revision VALUES( :nid, :nid, 1, :val, '', :ts, 0, 0, 0, 0 )",
                Array( ":nid" => $new_nid, ":val" => $post_val0, ":ts" => time() ));
        
        //! ==================================================================
        //db_query( "INSERT INTO testtlb VALUES( :en, :nid )", Array( ":en" => $event_name, ":nid" => $new_nid ) );
        

        $mail_msg_reg = str_replace( "@HREF@", $base_root . "/" . drupal_get_path_alias( "node/" . $new_nid ), str_replace( "@TITLE@", $post_val0, $mail_msg_reg ) );
        //! ==================================================================
        mail( $mail, "International Bat Night Event Registration", $mail_msg_reg, $mail_header );
        //! ==================================================================

        $j = 0;
        for( $i = 0; $i < count( $eventTypes ); $i++ ) {
            @ $post_val0 = "" . $_POST[ $eventTypes[ $i ][ 1 ] ];
            if ( $post_val0 != "" ) {
                /*db_query( "INSERT INTO field_data_field_ibn_event_type VALUES( 'node', 'ibn_event', 0, :nid, :nid, 'und', :delta, :val )",
                        Array( ":nid" => $new_nid, ":delta" => $j, ":val" => $eventTypes[ $i ][ 0 ] ) );
                db_query( "INSERT INTO field_revision_field_ibn_event_type VALUES( 'node', 'ibn_event', 0, :nid, :nid, 'und', :delta, :val )",
                        Array( ":nid" => $new_nid, ":delta" => $j++, ":val" => $eventTypes[ $i ][ 0 ] ) );*/
                addFld( "ibn_event_type", 0, $new_nid, Array( $eventTypes[ $i ][ 0 ] ), $j++ );
            }
        }

        @ $post_val0 = $_POST[ "specify" ];
        addFld( "ibn_event_spec", 1, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = "" . $_POST[ "orgName" ];
        addFld( "ibn_event_org", 1, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = 0 + $_POST[ "regSelect" ];
        @ $post_val1 = 0 + $_POST[ "couSelect" ];
        @ $post_val2 = "" . $_POST[ "couType" ];
        if ( $post_val2 != "" ) {
            $reg_name = db_query( "SELECT name FROM map_country_regions WHERE pkey = :pkey", Array( ":pkey" => $post_val0 ) )->fetchField();
            addFld( "ibn_reg_cou", 1, $new_nid, Array( "Region : $reg_name\nCountry : $post_val2" ) );
        }
        else
            addFld( "ibn_country", 0, $new_nid, Array( $post_val1 ) );

        @ $post_val0 = "" . $_POST[ "locationv" ];
        addFld( "ibn_event_location", 1, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = 0 + $_POST[ "lat" ];
        addFld( "ibn_latitude", 0, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = 0 + $_POST[ "lng" ];
        addFld( "ibn_longitude", 0, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = 0 + $_POST[ "date0y" ];
        @ $post_val1 = 0 + $_POST[ "date0m" ];
        @ $post_val2 = 0 + $_POST[ "date0d" ];
        $post_val0 .= "-" . str_pad( $post_val1, 2, "0", STR_PAD_LEFT ) . "-" . str_pad( $post_val2, 2, "0", STR_PAD_LEFT );

        @ $post_val3 = 0 + $_POST[ "date1y" ];
        @ $post_val4 = 0 + $_POST[ "date1m" ];
        @ $post_val5 = 0 + $_POST[ "date1d" ];
        if ( ( $post_val3 != -1 ) && ( $post_val4 != -1 ) && ( $post_val5 != -1 ) )
            $post_val1 = $post_val3 . "-" . str_pad( $post_val4, 2, "0", STR_PAD_LEFT ) . "-" . str_pad( $post_val5, 2, "0", STR_PAD_LEFT );
        else
            $post_val1 = $post_val0;

        addFld( "ibn_event_date", 5, $new_nid, Array( $post_val0, $post_val1 ) );

        @ $post_val0 = "" . $_POST[ "event_descr" ];
        addFld( "ibn_event_descr", 2, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = "" . $_POST[ "event_contact_email" ];
        addFld( "ibn_event_email", 1, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = "" . $_POST[ "event_web1" ];
        addFld( "ibn_web", 1, $new_nid, Array( $post_val0 ) );

        //@ $post_val0 = "" . $_POST[ "event_web" ];
        //addFld( "ibn_event_repl", 1, $new_nid, Array( $post_val0 ) );
        //!rdlink
        @ $post_val0 = "" . $_FILES[ "rdlink" ][ "name" ];
        //echo $_FILES[ "rdlink" ][ "tmp_name" ];
        //echo $post_val0 . " # " . $_SERVER[ 'REQUEST_URI' ] . " # " . $_SERVER[ 'SCRIPT_NAME' ];
        if ( $post_val0 != "" ) {
            $targetFile = $_SERVER['DOCUMENT_ROOT'] . "/sites/default/files/documents/IBN_event_announce/" . $post_val0;
            $i = 0;
            while ( file_exists( $targetFile ) ) {
                $post_val0 = ($i++) . $post_val0;
                $targetFile = $_SERVER['DOCUMENT_ROOT'] . "/sites/default/files/documents/IBN_event_announce/" . $post_val0;
            }
            move_uploaded_file( $_FILES[ "rdlink" ][ "tmp_name" ], $targetFile );
            chmod( $targetFile, 0666 );
            //echo $targetFile;
            //echo $_SERVER['DOCUMENT_ROOT'] . " # " . $_SERVER[ 'REQUEST_URI' ] . " # " . $_SERVER[ 'SCRIPT_NAME' ];
            $new_fid = db_query( "SELECT IF( ISNULL(MAX( fid ) + 1), 1, MAX( fid ) + 1 ) FROM file_managed" )->fetchField();
            $sql = "INSERT INTO file_managed VALUES( $new_fid, 1, '" .
                mysql_escape_string( $post_val0 ) . "', '" .
                mysql_escape_string( "public://documents/IBN_event_announce/" . $post_val0 ) . "', '$filemime', " .
                filesize( $targetFile ) . ", 1, " . time() . " )";
            //echo 
            db_query( $sql );

            $sql = "INSERT INTO file_usage VALUES( $new_fid, 'file', 'node', $new_nid, 1 )";
            //echo 
            db_query( $sql );

            $sql = "INSERT INTO field_data_field_ibn_event_announce VALUES( 'node', 'ibn_event', 0, $new_nid, $new_nid, 'und', 0, $new_fid, 1, '' )";
            //echo 
            db_query( $sql );

            $sql = "INSERT INTO field_revision_field_ibn_event_announce VALUES( 'node', 'ibn_event', 0, $new_nid, $new_nid, 'und', 0, $new_fid, 1, '' )";
            //echo 
            db_query( $sql );

        }
        

        @ $post_val0 = "" . $_POST[ "fName" ];
        @ $post_val1 = "" . $_POST[ "lName" ];
        addFld( "ibn_event_contact", 1, $new_nid, Array( $post_val0 . " " . $post_val1 ) );

        @ $post_val0 = "" . $_POST[ "event_address" ];
        addFld( "ibn_event_addr", 1, $new_nid, Array( $post_val0 ) );

        @ $post_val0 = "" . $_POST[ "event_pcode" ];
        @ $post_val1 = "" . $_POST[ "event_city" ];
        @ $post_val2 = "" . $_POST[ "event_country" ];
        @ $post_val3 = "" . $_POST[ "email" ];
        @ $post_val4 = "" . $_POST[ "event_tel" ];
        addFld( "ibn_event_contact_info", 1, $new_nid, Array( "Postal code : " . $post_val0 . "\n" .
                "City : " . $post_val1 . "\n" .
                "Country : " . $post_val2 . "\n" .
                "E-mail : " . $post_val3 . "\n" .
                "Telephone : " . $post_val4
                ) );
        $jsmsg_post = $jsmsg_reg;
    }
}

if ( $formid == 2 ) {
    /*SELECT field_ibn_reg_mail_reg_value AS mailr, field_ibn_reg_mail_poster_value AS mailp
    FROM node INNER JOIN field_data_field_ibn_reg_mail_reg AS mreg ON nid = mreg.entity_id
        INNER JOIN field_data_field_ibn_reg_mail_poster AS mposter ON nid = mposter.entity_id
    WHERE node.type = 'ibn_reg'*/
    $sql = "SELECT field_ibn_reg_mail_poster_value AS mailr
            FROM node INNER JOIN field_data_field_ibn_reg_mail_poster ON nid = entity_id
            WHERE node.type = 'ibn_reg'";
    $mail = db_query( $sql )->fetchField();
    @ $post_val0 = "" . $_POST[ "fName1" ];
    @ $post_val1 = "" . $_POST[ "lName1" ];
    $mail_msg_pos = str_replace( "@FNAME@", $post_val0, str_replace( "@LNAME@", $post_val1, $mail_msg_pos ) );

    @ $post_val0 = "" . $_POST[ "address" ];
    @ $post_val1 = "" . $_POST[ "pcode" ];
    $mail_msg_pos = str_replace( "@ADDRESS@", $post_val0, str_replace( "@PCODE@", $post_val1, $mail_msg_pos ) );

    @ $post_val0 = "" . $_POST[ "city" ];
    @ $post_val1 = "" . $_POST[ "country" ];
    $mail_msg_pos = str_replace( "@CITY@", $post_val0, str_replace( "@COUNTRY@", $post_val1, $mail_msg_pos ) );

    @ $post_val0 = "" . $_POST[ "pa2" ];
    @ $post_val1 = "" . $_POST[ "pa3" ];
    $mail_msg_pos = str_replace( "@A2@", $post_val0, str_replace( "@A3@", $post_val1, $mail_msg_pos ) );

    mail( $mail, "International Bat Night Poster Order", $mail_msg_pos, $mail_header );
    $jsmsg_post = $jsmsg_pos;


}

//echo "</textarea>";
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
      if ( $formid != 10 ) {
      print render($content);
    ?>

<br />
<br />
<div id="errorMessages1">
<?php 
if ( $err_add_event != "" ) {
    echo "<strong>Errors : " . $err_add_event_count . "</strong>";
    echo $err_add_event;
    echo "<br />";
}
?>
</div>
<form method="post" enctype="multipart/form-data" action="" id="form1">
<input type="hidden" value="1" name="formid">
<table style="width : 100%; table-layout : fixed;">
    <tr>
        <td class="col1_1">Event name: <span style="color : #FF0000 ">*</span></td>
        <td ><input name="event_name" type="text" class="ibn_reg1"/></td>
    </tr>

    <tr>
        <td class="col1_1">Event type: <span style="color : #FF0000 ">*</span></td>
        <td >
            <?php
                    $typeWidth = "33%"; 
                    //@NAME@
                    $typeSample = "<label class=\"ibn_reg\"><input id=\"@NAME@\" type=\"checkbox\" name=\"@NAME@\" @ONCLICK@/>&nbsp;@TITLE@</label>";
                    $rowTypeSample = "<tr>\n" .
                        "<td style=\"width : $typeWidth \">@C1@</td>\n" .
                        "<td style=\"width : $typeWidth \">@C2@</td>\n" .
                        "<td style=\"width : $typeWidth \">@C3@</td>\n" .
                        "</tr>\n";

                    $j = 1; $out = ""; $str = $rowTypeSample; 
                    for( $i = 0; $i < count( $eventTypes ); $i++ ) {
                        $str1 = $typeSample;
                        $str1 = str_replace( "@NAME@", $eventTypes[ $i ][ 1 ], str_replace( "@TITLE@", $eventTypes[ $i ][ 0 ], $str1 ) );
                        if ( $eventTypes[ $i ][ 0 ] == "Other" )
                            $str1 = str_replace( "@ONCLICK@", "onclick=\"otherOnClick( this )\"", $str1 );
                        else
                            $str1 = str_replace( "@ONCLICK@", "", $str1 );
                        $str = str_replace( "@C$j@", $str1, $str );
                        $j++;
                        if ( $j == 4 ) {
                            $out .= $str;
                            $str = $rowTypeSample;
                            $j = 1;
                        }
                    }
                    if ( $j == 1 ) $str = "";
                    for ( $i = 2; $i < 4; $i++ ) {
                        $str = str_replace( "@C$i@", "", $str );
                    }
                    $out .= $str;

                    //echo "<textarea style=\"width : 100%\">";
                    //echo $out;
                    //echo $jsTypes;
                    //echo "</textarea>";
                    echo "<table id=\"typesTable\" style=\"margin-left:-6px; width : 100%\">";
                    echo $out;
                    echo "</table>";
            ?>

        </td>
    </tr>
    <tr>
        <td class="col1_1">&nbsp;</td>
        <td style=" text-align : right; " ><div id="specifyDisplay" style="display : none;">Specify: <input type="text" style=" width : 459px;" name="specify" /></div></td>
<script type="text/javascript">
var spDisp = document.getElementById( "specifyDisplay" );
// ------------------------------------------------------------------------
//  
// ------------------------------------------------------------------------
function otherOnClick( obj ) {
    spDisp.style.display = ((obj.checked)?"block":"none");
}
</script>
    </tr>
    <tr>
        <td class="col1_1">Organization Name: <span style="color : #FF0000 ">*</span></td>
        <td ><input type="text" class="ibn_reg1" name="orgName" /></td>
    </tr>
    <tr>
    <?php 
        $sqlr = "SELECT -1 AS pkey, '- Select region -' AS name\n" .
                "UNION\n" .
                "SELECT DISTINCT pkey, name\n" .
                "FROM map_country_regions\n" .
                "ORDER BY name";
        $sqlc = "SELECT DISTINCT node.title, node.nid\n" .
                "FROM node\n" .
                "    #INNER JOIN field_data_field_country_reg ON field_data_field_country_reg.entity_id = node.nid\n" .
                "WHERE ( node.type = 'country_profiles' )\n" .
                "#AND ( field_country_reg_ebenum = @RKEY@ )\n" .
                "ORDER BY node.title";
        $res = db_query( $sqlr );
        $jsCountriesByRegions = "";
        $jsRegOption = "";
        foreach ( $res as $row ) {
            $jsRegOption .= "        <option value=\"" . $row->pkey . "\">" . $row->name . "</option>";
            $resc = db_query( str_replace( "@RKEY@", $row->pkey, str_replace( (($row->pkey == -1)?"ZZ":"#"), "", $sqlc ) ) );
            $i = 0;
            $jsCouReg = "[ \"- Select country -\", -1 ],\n";
            foreach ( $resc as $rowc ) {
                $i++;
                $jsCouReg .= "[ \"" . $rowc->title . "\", " . $rowc->nid . " ],\n";
            }
            $jsCouReg = substr( $jsCouReg, 0, strlen( $jsCouReg ) - 2 );
            $jsCouReg = "[ ". $jsCouReg . " ] ";
            $jsCountriesByRegions .= "\"" . $row->pkey . "\" : " . $jsCouReg . ",\n";
        }
        $jsCountriesByRegions = "{ " . substr( $jsCountriesByRegions, 0, strlen( $jsCountriesByRegions ) - 2 ) . " }";
    ?>
        <td class="col1_1">Select Region: <span style="color : #FF0000 ">*</span></td>
        <td >
            <select style="width : 799px;" id="regSelect" name="regSelect" onchange="regOnChange();">
                <?php echo $jsRegOption; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="col1_1">Country: <span style="color : #FF0000 ">*</span></td>
        <td>select : <span id="couSelectCont"></span> &nbsp;or please specify field on the right: &nbsp;<input type="text" style="float : right; width : 229px;" name="couType"/></td>
    </tr>
    <tr>
        <td class="col1_1">Location / Venue: <span style="color : #FF0000 ">*</span></td>
        <td ><input type="text" class="ibn_reg1" name="locationv" /></td>
    </tr>
</table>
<script type="text/javascript">
    var regSelect = document.getElementById( "regSelect" );
    var couSelect;
    var couSelectCont = document.getElementById( "couSelectCont" );
    var couByReg = <?php echo $jsCountriesByRegions; ?>;

    function regOnChange() {
        couArray = couByReg[ regSelect.value ];
        str = "<select style=\"width : 300px;\" name=\"couSelect\">\n";
        for ( i = 0; i < couArray.length; i++ )
            str += "<option value=" + couArray[ i ][ 1 ] + " " +
                ">" + couArray[ i ][ 0 ] + "</option>\n";
        str+= "</select>\n";
        couSelectCont.innerHTML = str;
        couKey = -1;
    }
    regOnChange();
    </script>
<div style="margin : 7px 0;">
	<i>Please, enter Google Map coordinates of your Event, if possible. Click <b><a href="../../sites/default/files/Geocoding.php" rel="lightmodal[|width:500px; height:575px; scrolling: auto;]">here </a></b> for short instruction (how to get coordinates of the Event place). </i> 
</div>
<table style=" width : 100% ">
    <tr>
        <td class="col1_1">Latitude: </td>
        <td ><input type="text" style=" width : 50%;" name="lat" /></td>
    </tr>
    <tr>
        <td class="col1_1">Longitude: </td>
        <td ><input type="text" style=" width : 50%;" name="lng" /></td>
    </tr>
    <tr>
        <?php
            $yer = 0 + date( "Y" );
            //echo $yer;
            $optYers = "<option value=\"-1\" selected> - Year - </option>\n";
            for ( $i = 0; $i < 4; $i++ ) {
                $yer1 = $yer + $i;
                $optYers .= "<option value=\"$yer1\">$yer1</option>\n";
            }
            $optMon = "<option value=\"-1\" selected> - Month - </option>\n";;
            for ( $i = 1; $i < 13; $i++ ) {
                $mon1 = date( "M", strtotime( "$yer-$i-01" ));
                $optMon .= "<option value=\"$i\">$mon1</option>\n";
            }
        ?>
        <td class="col1_1">Date: <span style="color : #FF0000 ">*</span></td>
        <td >
            <i>From:</i>
            <select id="date0y" name="date0y" onchange="onChangeDate( 0 )">
            <?php echo $optYers; ?>
            </select>
            <select id="date0m" name="date0m" onchange="onChangeDate( 0 )">
            <?php echo $optMon; ?>
            </select>
            <span id="date0d">
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp; 
            <i>To:</i>
            <select id="date1y" name="date1y" onchange="onChangeDate( 1 )">
            <?php echo $optYers; ?>
            </select>
            <select id="date1m" name="date1m" onchange="onChangeDate( 1 )">
            <?php echo $optMon; ?>
            </select>
            <span id="date1d">
            </span>
            <script type="text/javascript">
            var dtCtrls =   [
                                [ document.getElementById( "date0y" ), document.getElementById( "date0m" ), document.getElementById( "date0d" ) ],
                                [ document.getElementById( "date1y" ), document.getElementById( "date1m" ), document.getElementById( "date1d" ) ]
                            ];
            function onChangeDate( i ) {
                //alert( i + " # " + t + " # " + val );
                var year    = dtCtrls[ i ][ 0 ].value;
                var month   = dtCtrls[ i ][ 1 ].value - 1;
                var dayCount = new Date( year, month + 1, 0 ).getDate(); //32 - new Date( year, month, 32 ).getDate();
                var obj = document.getElementById( "date" + i + "ds" );
                var day =((obj == null)?-1:obj.value);
                var str = "<option value=\"-1\"> - Day - </option>\n";
                for ( j = 1; j <= dayCount; j++ ) {
                    str += "<option value=\"" + j + "\" " + ((day == j)?"selected":"") + ">" + j + "</option>\n";
                }
                dtCtrls[ i ][ 2 ].innerHTML = "<select id=\"date" + i + "ds\" name=\"date" + i + "d\">\n" + str + "</select>\n";
            }
            onChangeDate( 0 );
            onChangeDate( 1 );
            //alert( document.getElementById( "date0d" ).innerHTML );
            </script>
        </td>
    </tr>
    <tr>
        <td class="col1_1">Description of your Event: <span style="color : #FF0000 ">*</span></td>
        <td ><textarea rows="5" class="ibn_reg" name="event_descr"> </textarea></td>
    </tr>
</table>

<table style=" width : 100% ">
	<tr>
    	<td class="col1_2">Contact E-mail for Event: <span style="color : #FF0000 ">*</span></td>
        <td><input type="text" class="ibn_reg" name="event_contact_email"/></td>
    </tr>
    <tr>
    	<td class="col1_2">Web Address for Event: </td>
        <td><input type="text" class="ibn_reg" name="event_web1"/></td>
    </tr>
    <?php /* <tr>
        <td class="col1_2">Related Document external Link : </td>
        <td><input type="text" class="ibn_reg" name="event_web"/></td>
    </tr> */ ?>
    <tr>
    	<td class="col1_2">Event Announcement (type of files supported: <i>txt, pdf, doc, docx, xls, xlsx, jpg, jpeg, jpe, png</i>): </td>
        <td><input type="file" name="rdlink" /></td>
    </tr>
</table>
<br />
<p><b>Contact Information for Event</b></p>
<!--
<table style=" width : 100%; ">
	<tr>
    	<td class="col1_3">First Name: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp; <input type="text" style=" width : 355px;" name="fName" /></td>
        <td class="col1_3">Last Name: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp; <input type="text" style=" align : right; width : 370px;" name="lName" /></td>
        
    </tr>
    <tr>
    	<td colspan="2" valign="middle">Address:  &nbsp;&nbsp; <input type="text" style=" float : right; width : 839px;" name="event_address" /></td>
                
    </tr>
    <tr>
    	<td class="col1_3">Postal code:  &nbsp;&nbsp; <input type="text" style=" margin-left : 4px; width : 355px;" name="event_pcode"/></td>
        <td class="col1_3" >City:  &nbsp;&nbsp; <input type="text" style=" align : right; width : 85%;" name="event_city" /></td>
        
    </tr>
    <tr>
    	<td class="col1_3">Country:   <input type="text" style=" margin-left : 40px; width : 355px;" name="event_country" /></td>
        <td class="col1_3" >&nbsp; </td>
        
    </tr>
    <tr>
    	<td class="col1_3">E-mail: <span style="color : #FF0000 ">*</span> <input type="text" style=" margin-left : 39px; width : 355px;" name="email"/></td>
        <td class="col1_3" >Telephone:  &nbsp;&nbsp; <input type="text" style=" align : right; width : 370px;" name="event_tel"/></td>
        
    </tr>
    

</table>
-->
<br />

<table style=" width : 100%; table-layout : fixed;">
    <tr>
        <td style="width : 90px;">First Name: <span style="color : #FF0000 ">*</span></td><td><input type="text" style=" width : 355px;" name="fName" /></td>
        <td style="width : 90px;">&nbsp;&nbsp;Last Name: <span style="color : #FF0000 ">*</span></td><td style=" text-align : right;"><input type="text" style=" width : 370px;" name="lName" /></td>
    </tr>
    <tr>
        <td>Address:</td><td colspan="3" style="padding-right : 4px;"><input type="text" class="event_addr" name="event_address" /></td>
    </tr>
    <tr>
        <td>Postal code:</td><td><input type="text" style=" margin-left : 0px; width : 355px;" name="event_pcode"/></td>
        <td>&nbsp;&nbsp;City: </td><td style=" text-align : right;"><input type="text" style=" align : right; width : 370px;" name="event_city" /></td>
    </tr>
    <tr>
        <td>Country:</td><td colspan="3"><input type="text" style=" width : 355px;" name="event_country" /></td>
    </tr>
    <tr>
        <td>E-mail: <span style="color : #FF0000 ">*</span></td><td><input type="text" style=" width : 355px;" name="email" /></td>
        <td>&nbsp;&nbsp;Telephone: </td><td style=" text-align : right;"><input type="text" style=" width : 370px;" name="event_tel"/></td>
    </tr>
</table>
<br />

<input class="ebbtn" type="submit" value="Submit" />
</form>
<script type="text/javascript">
function submit1OnClick() {
    alert( "submit1OnClick" );
    return( false );
}

function form1OnSubmit() {
    alert( "form1OnSubmit" );
    return( false );
}
// ------------------------------------------------------------------------
//  
// ------------------------------------------------------------------------
function getBodyScrollTop() {
    if ( document.documentElement.scrollTop > document.body.scrollTop )
        return document.documentElement.scrollTop
    else
        return document.body.scrollTop
}

// ------------------------------------------------------------------------
//  
// ------------------------------------------------------------------------
function bodyScrollTop( Top ) {
    if ( document.documentElement.scrollTop > document.body.scrollTop )
        document.documentElement.scrollTop = Top;
    else
        document.body.scrollTop = Top;
}

function checkEmail( email ) {
    var n = email.indexOf( "@" );
    return (( n > 0 ) && ( n < email.length - 4 ));
}

function checkDate( y0, m0, d0, y1, m1, d1 ) {
    var date0 = -1;
    var date1 = -1;
    if ( ( y0 != -1 ) && ( m0 != -1 ) && ( d0 != -1 ) )
        date0 = new Date( y0, m0 - 1, d0 );
    if ( date0 == -1 )
        return 1
    if ( ( y1 != -1 ) && ( m1 != -1 ) && ( d1 != -1 ) ) {
        date1 = new Date( y1, m1 - 1, d1 );
        if ( date1 < date0 )
            return 2;
    }
    return 0;
}
var errMsg = <?php echo $jsErrMsg; ?>;
var form1 = document.getElementById( "form1" );
form1.onsubmit = function () {
    //! ==================================================================
    //return true;
    //! ==================================================================
    var dy = 100;
    var obj, obj1, obj2;
    var tCB = <?php echo $jsTypes; ?>;
    var f;

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

    processError( (form1.event_name.value.length < 3 ), [ form1.event_name ], "form1.event_name", errMsg[ 0 ] );

    //alert( tCB[ i ].checked );
    f = false;
    for ( i = 0; i < tCB.length; i++ ) {
        f = tCB[ i ].checked;
        if ( f ) break;
    }
    //return false;
    processError( !f, [ tCB[ 0 ], document.getElementById( "typesTable" ) ], "document.getElementById( 'typesTable' )", errMsg[ 1 ] );
    processError( (form1.orgName.value.length < 3 ), [ form1.orgName ], "form1.orgName", errMsg[ 2 ] );
    processError( (form1.regSelect.value == -1 ), [ form1.regSelect ], "form1.regSelect", errMsg[ 3 ] );
    processError( ( ( form1.couSelect.value == -1 ) && ( form1.couType.value.length < 3 ) ), [ form1.couSelect, form1.couType ], "form1.couSelect", errMsg[ 4 ] );
    processError( ( form1.locationv.value.length < 3 ), [ form1.locationv ], "form1.locationv", errMsg[ 5 ] );
    // -----------  Date -----------
    var ds = checkDate( form1.date0y.value, form1.date0m.value, form1.date0d.value, form1.date1y.value, form1.date1m.value, form1.date1d.value );
    if ( ds == 1 ) ds = 6;
    if ( ds == 2 ) ds = 12;
    processError( ( ds != 0 ), [ form1.date0y, form1.date0m, form1.date0d, form1.date1y, form1.date1m, form1.date1d ], "form1.date0y", errMsg[ ds ] );

    processError( (form1.event_descr.value.length < 3 ), [ form1.event_descr ], "form1.event_descr", errMsg[ 7 ] );
    processError( !checkEmail( form1.event_contact_email.value ), [ form1.event_contact_email ], "form1.event_contact_email", errMsg[ 8 ] );
    processError( (form1.fName.value.length < 3 ), [ form1.fName ], "form1.fName", errMsg[ 9 ] );
    processError( (form1.lName.value.length < 3 ), [ form1.lName ], "form1.lName", errMsg[ 10 ] );
    processError( !checkEmail( form1.email.value ), [ form1.email ], "form1.email", errMsg[ 11 ] );


    
    if ( errors > 0 ) {
        errDiv.innerHTML = "<p style=\"font-weight : bold;\">Errors : " + errors + "</p>" + errDiv.innerHTML;
        firstObj.focus();
        bodyScrollTop( getTop( errDiv ) - 100 );
        return false;
    }
    return true;
    
}
</script>
<div class="separator"></div>
<div id="errorMessages2"></div>
<h3> IBN Poster Order </h3>
<form method="post" action="" id="form2">
<input type="hidden" value="2" name="formid">
<table style=" width : 100%; ">
	<tr>
    	<td class="col1_3">First Name: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp; <input type="text" style=" width : 75%;" name="fName1" /></td>
        <td class="col1_3">Last Name: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp; <input type="text" style=" float : right; width : 78%;" name="lName1" /></td>
        
    </tr>
    <tr>
        <td colspan="2">Address (<i>no P.O. Box, please!</i>): <span style="color : #FF0000 ">*</span> &nbsp; <input type="text" style=" float : right; width : 75%;" name="address" /></td>

    </tr>
    <tr>
        <td class="col1_3">Postal code: <span style="color : #FF0000 ">*</span> &nbsp; <input type="text" style=" width : 75%;" name="pcode" /></td>
        <td class="col1_3" >City: <span style="color : #FF0000 ">*</span> &nbsp; <input type="text" style=" float : right; width : 85%;" name="city" /></td>
        
    </tr>
    <tr>
    	<td class="col1_3">Country: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" style=" width : 60%;" name="country" /></td>
        <td class="col1_3" >&nbsp; </td>
        
    </tr>
    <tr>
    	<td colspan="2">Number of Posters: <span style="color : #FF0000 ">*</span> &nbsp;&nbsp;&nbsp;&nbsp;A2 <input type="text" style=" width : 5%;" name="pa2" value="" /> 
        <span style=" margin-left : 45px; ">A3</span> <input type="text" style=" width : 5%;" name="pa3" value="" />
        </td>
                
    </tr>
    

</table>
<input class="ebbtn" type="submit" value="Submit"/>
</form>
<script type="text/javascript">
var form2 = document.getElementById( "form2" );
form2.onsubmit = function () {
    var dy = 100;
    var errSample = "<p style=\"cursor : pointer; font-weight : bold;\" onclick=\"bodyScrollTop( getTop( @OBJ@ ) - "+dy+" );\">@TEXT@</p>"
    var errDiv = document.getElementById( "errorMessages2" );
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

    processError( ( form2.fName1.value.length < 3 ), [ form2.fName1 ], "form2.fName1", errMsg[ 13 ] );
    processError( ( form2.lName1.value.length < 3 ), [ form2.lName1 ], "form2.lName1", errMsg[ 14 ] );
    processError( ( form2.address.value.length < 3 ), [ form2.address ], "form2.fName1", errMsg[ 15 ] );
    processError( ( form2.pcode.value.length < 3 ), [ form2.pcode ], "form2.fName1", errMsg[ 16 ] );
    processError( ( form2.city.value.length < 3 ), [ form2.city ], "form2.fName1", errMsg[ 17 ] );
    processError( ( form2.country.value.length < 3 ), [ form2.country ], "form2.fName1", errMsg[ 18 ] );
    var pa2 = parseInt( form2.pa2.value );
    var pa3 = parseInt( form2.pa3.value );
    pa2 = (isNaN( pa2 )?0:pa2);
    pa3 = (isNaN( pa3 )?0:pa3);
    //alert( pa2 + " # " + pa3 );
    processError( ( ( pa2 == 0 ) && ( pa3 == 0 ) ), [ form2.pa2, form2.pa3 ], "form2.pa2", errMsg[ 19 ] );

    if ( errors > 0 ) {
        errDiv.innerHTML = "<p style=\"font-weight : bold;\">Errors : " + errors + "</p>" + errDiv.innerHTML;
        firstObj.focus();
        bodyScrollTop( getTop( errDiv ) - 100 );
        return false;
    }
    return true;
}
</script>
<?php } ?>

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
