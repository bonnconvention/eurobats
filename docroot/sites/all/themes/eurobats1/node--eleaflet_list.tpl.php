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
$sample0 = "<div style=\"float : left; text-align : center; width : 80px;\">&nbsp</div>";
$sample1 = "<div style=\"float : left; text-align : center; width : 200px;\"><a href=\"@HREF@\" target=\"_blank\"><img src=\"@IMG@\" alt=\"\" /></a><br /><a href=\"@HREF@\">@LANG@</a></div>";
$sample2 = "<div style=\"clear : both;\">&nbsp;</div>";
$lang = Array( "EN" => "English", "FR" => "French", "DE" => "German", "IRL" => "Irish", "IT" => "Italian", "DK" => "Danish" );
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
    print render($content['field_eleaflet_head']);
    print "<br />";
    //print render($content);
    $sql1 = "SELECT tlbcovuri.uri AS covuri, lngiso639.lngname AS lang, tlbpdfuri.uri AS pdfuri, field_eleaflet_hardcopy_value
                FROM field_data_field_eleaflets AS tlbcoll LEFT JOIN field_data_field_eleaflet_cover AS tlbcov ON tlbcoll.field_eleaflets_value = tlbcov.entity_id
                        LEFT JOIN file_managed AS tlbcovuri ON tlbcov.field_eleaflet_cover_fid = tlbcovuri.fid
                    LEFT JOIN field_data_field_eleaflet_lng AS tlblng1 ON tlbcoll.field_eleaflets_value = tlblng1.entity_id
                    LEFT JOIN lngiso639 ON tlblng1.field_eleaflet_lng_ebenum = lngiso639.pkey
                    LEFT JOIN field_data_field_eleaflet_pdf AS tlbpdf ON tlbcoll.field_eleaflets_value = tlbpdf.entity_id
                        LEFT JOIN file_managed AS tlbpdfuri ON tlbpdf.field_eleaflet_pdf_fid = tlbpdfuri.fid
                    LEFT JOIN field_data_field_eleaflet_hardcopy AS tlbhc ON tlbcoll.field_eleaflets_value = tlbhc.entity_id
                WHERE tlbcoll.entity_id = @ID@
                ORDER BY tlbcoll.delta";
                //LEFT JOIN field_data_field_eleaflet_lang AS tlblng ON tlbcoll.field_eleaflets_value = tlblng.entity_id
    $sql = "SELECT nid, field_eleaflets_descr_ll_value AS descr
            FROM node LEFT JOIN field_data_field_eleaflets_descr_ll AS tlbdll ON nid = tlbdll.entity_id
                LEFT JOIN field_data_field_eleaflets_orderno AS tlbon ON nid = tlbon.entity_id
            WHERE type = 'eleaflet'
            ORDER BY field_eleaflets_orderno_value";
    $res = db_query( $sql );
    foreach( $res as $row ) {
        $res1 = db_query( str_replace( "@ID@", $row->nid, $sql1 ) );
        $out = $sample0; $i = 0;
        foreach( $res1 as $row1 ) {
            //$out .= str_replace( "@HREF@", file_create_url( $row1->pdfuri ), str_replace( "@IMG@", file_create_url( $row1->covuri ), str_replace( "@LANG@", $lang[ $row->lng ], $sample1 ) ) );
            $out .= str_replace( Array( "@HREF@", "@IMG@", "@LANG@" ),
                                    Array( file_create_url( $row1->pdfuri ), file_create_url( $row1->covuri ), $row1->lang ),
                                    $sample1 );
            if ( $i == 3 ) {
                $out .= $sample2 . "<br />" . $sample0;
                $i = -1;
            }
            $i++;
        }
        if ( $out != $sample0 ) {
            echo $row->descr;
            print $out;
            print $sample2;
            print "<br />";
        }
    }

//!=======================================================================================
//! OLD =========================
//!=======================================================================================

    /* print render($content['field_eleaflets_batsum']);
      print "<br />";
      
    $sql = "SELECT field_eleaflets_bat_value, tlbfcov.uri, field_eleaflet_lang_value AS lng, tlbfpdf.uri AS pdf
            FROM node LEFT JOIN field_data_field_eleaflets_bat AS tlbbatl ON nid = tlbbatl.entity_id
                LEFT JOIN field_data_field_eleaflet_cover AS tlbcov ON field_eleaflets_bat_value = tlbcov.entity_id
                LEFT JOIN file_managed AS tlbfcov ON tlbcov.field_eleaflet_cover_fid = tlbfcov.fid
                LEFT JOIN field_data_field_eleaflet_lang AS tlblang ON field_eleaflets_bat_value = tlblang.entity_id
                LEFT JOIN field_data_field_eleaflet_pdf AS tlbpdf ON field_eleaflets_bat_value = tlbpdf.entity_id
                LEFT JOIN file_managed AS tlbfpdf ON tlbpdf.field_eleaflet_pdf_fid = tlbfpdf.fid
            WHERE type='eleaflet_list'
            ORDER BY lng";
    $res = db_query( $sql );
    $out = $sample0; $i = 0;
    foreach( $res as $row ) {
        //$out .= str_replace( "@IMG@", file_create_url( $row->uri ), str_replace( "@LANG@", $lang[ $row->lng ], $sample1 ) );
        $out .= str_replace( "@HREF@", file_create_url( $row->pdf ), str_replace( "@IMG@", file_create_url( $row->uri ), str_replace( "@LANG@", $lang[ $row->lng ], $sample1 ) ) );
        if ( $i == 3 ) {
            $out .= $sample2 . "<br />" . $sample0;
            $i = -1;
        }
	 $i++;
    }
    print $out;
    print $sample2;

    print "<br />";
    print render($content['field_eleaflets_forsum']);
    print "<br />";

    $sql = "SELECT field_eleaflets_for_value, tlbfcov.uri, field_eleaflet_lang_value AS lng, tlbfpdf.uri AS pdf
            FROM node LEFT JOIN field_data_field_eleaflets_for AS tlbforl ON nid = tlbforl.entity_id
                LEFT JOIN field_data_field_eleaflet_cover AS tlbcov ON field_eleaflets_for_value = tlbcov.entity_id
                LEFT JOIN file_managed AS tlbfcov ON tlbcov.field_eleaflet_cover_fid = tlbfcov.fid
                LEFT JOIN field_data_field_eleaflet_lang AS tlblang ON field_eleaflets_for_value = tlblang.entity_id
                LEFT JOIN field_data_field_eleaflet_pdf AS tlbpdf ON field_eleaflets_for_value = tlbpdf.entity_id
                LEFT JOIN file_managed AS tlbfpdf ON tlbpdf.field_eleaflet_pdf_fid = tlbfpdf.fid
            WHERE type='eleaflet_list'
            ORDER BY lng";
    $res = db_query( $sql );
    $out = $sample0; $i = 0;
    foreach( $res as $row ) {
        $out .= str_replace( "@HREF@", file_create_url( $row->pdf ), str_replace( "@IMG@", file_create_url( $row->uri ), str_replace( "@LANG@", $lang[ $row->lng ], $sample1 ) ) );
        if ( $i == 3 ) {
            $out .= $sample2 . "<br />" . $sample0;
            $i = -1;
        }
        $i++;
    }
    print $out;
    print $sample2;*/
//!=============================================================
//!=============================================================

    print "<br />";
    print render($content['field_eleaflets_footer']);
    print "<br />";
    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
