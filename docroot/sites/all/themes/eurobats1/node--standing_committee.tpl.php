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

$is_pic_block = ( db_query( "SELECT field_pic_block_value FROM field_data_field_pic_block WHERE entity_id = $nid" )->fetchField() != "" );
$mdoc_title = "Meeting Documents";
$mpic_title = "Pictures";
@ $stdObj       = $content['field_doc_title']['#object'];
$doc_tlt = "";
if ( isset($stdObj) ) {
    $doc_tlt = "<br />" . $stdObj->field_doc_title[ $stdObj->language ][ 0 ][ 'safe_value' ];
}



$fldlist1 = "field_doc_form_value AS rubric, uri, field_doc_file_stc_description AS description, field_doc_id_stc_value AS docid";
$fldlist2 = "COUNT( * )";
$sqlsample = "SELECT @FLDLST@
                FROM field_data_field_resources_stc AS resac INNER JOIN field_data_field_doc_file_stc AS docf ON field_resources_stc_value = docf.entity_id
                    LEFT JOIN field_data_field_doc_form AS fsdoctype ON docf.entity_id = fsdoctype.entity_id
                    LEFT JOIN file_managed ON field_doc_file_stc_fid = file_managed.fid
                    LEFT JOIN field_data_field_doc_id_stc AS docid ON docf.entity_id = docid.entity_id
                WHERE ( resac.entity_id = $nid ) AND ( field_doc_form_value @COND@ )
                ORDER BY field_doc_form_value, resac.delta";
                /*ORDER BY field_doc_form_value, field_doc_id_ac_value, field_doc_file_ac_description";*/

$sample1 = "<table style=\" margin-left : 20px; padding-bottom : 15px;\"><td>@C0@</td><td style=\"padding-left : 60px;\">@C1@</td><td style=\"padding-left : 60px;\">@C2@</td></table>";
$fldsamples = Array( "<a href=\"@HREF@\" target=\"_blank\">Record of the Meeting</a>",
    "<a href=\"$node_url#1\">$mdoc_title</a>",
    "<a href=\"$node_url#2\">$mpic_title</a>" );

$sample5 = "<tr><td><a href=\"@HREF@\" target=\"_blank\"><img src=\"/sites/default/files/images/iconpdf.gif\" /></a>&nbsp;&nbsp;</td>
        <td><a href=\"@HREF@\" target=\"_blank\">@FNAME@</a>&nbsp;&nbsp;&nbsp;</td>
        <td><a href=\"@HREF@\" target=\"_blank\">@DESC@</a></td></tr>";
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page) { ?>

  <div style="float : left;"><h3<?php print $title_attributes; ?>><img src="/<?php print $directory; ?>/img/bat_mark.png" alt="" />&nbsp;&nbsp;</h3></div>
    <div style="float : left;">
        <h3<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title . $doc_tlt; ?></a></h3>
    </div>
    <div style="clear : both;">
<?php

        $i = 0; $out = $sample1;

        // Record of the Meeting
        $s1 = db_query( str_replace( "@COND@", "= 'Record'", str_replace( "@FLDLST@", "uri", $sqlsample ) ) )->fetchField();
        if ( $s1 != "" ) {
            $out = str_replace( "@C$i@", str_replace( "@HREF@", file_create_url( $s1 ), $fldsamples[ 0 ] ), $out );
            $i++;
        }

        // Meeting Documents;
        if ( db_query( str_replace( "@COND@", "<> 'Record'", str_replace( "@FLDLST@", $fldlist2, $sqlsample ) ) )->fetchField() > 0 ) {
            $out = str_replace( "@C$i@", str_replace( "@HREF@", file_create_url( $s1 ), $fldsamples[ 1 ] ), $out );
            $i++;
        }
        // Pictures
        if ( $is_pic_block ) {
            $out = str_replace( "@C$i@", str_replace( "@HREF@", file_create_url( $s1 ), $fldsamples[ 2 ] ), $out );
            $i++;
        }
        for ( $i = 0; $i < count( $fldsamples ); $i++ )
            $out = str_replace( "@C$i@", "", $out );

        echo $out . "&nbsp;</div>";

    }
    //! ---------------------------------- END NOT page ---------------------------------- 
?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      //hide($content['comments']);
      //hide($content['links']);
      //print render($content);
    ?>
<?php if ($page) {
//! ---------------------------------- START page ---------------------------------- 
// /official_documents/advisory_committee/16th_meeting_advisory_committee#1
if ( $is_pic_block ) { ?>
<ul class="tabs primary">
    <li id="doc_swli" class="active">
        <a id="doc_swa" href="javascript:void( 0 )" class="active" onclick="switchBlock( 0 )"><?php echo $mdoc_title; ?></a>
    </li>
    <li id="pic_swli">
        <a id="pic_swa" href="javascript:void( 0 )" onclick="switchBlock( 1 )"><?php echo $mpic_title; ?></a>
    </li>
</ul>
<?php }//! ---------------------------------- doc block ----------------------------------  
?>
<div id="doc_block" style="display : block">
<?php
print render($content[ 'field_tlb_block' ] );
$res = db_query( str_replace( "@COND@", "!= 'Record'", str_replace( "@FLDLST@", $fldlist1, $sqlsample ) ) );
$out = "";
foreach ( $res as $row ) {
    $fname = $row->docid;//strrchr( $row->uri, "/" );
    //$fname = substr( $fname, 1, strlen( $fname ) );
    $out .= str_replace( "@FNAME@", $fname, str_replace( "@DESC@", $row->description, str_replace( "@HREF@", file_create_url( $row->uri ), $sample5 ) ) );
}
echo "<table>" . $out . "</table>";
?>
</div>
<?php //! ---------------------------------- pic block ----------------------------------  
if ( $is_pic_block ) { ?>
<div id="pic_block" style="display : none">
<?php print render( $content[ 'field_pic_block' ] ); ?>
</div>

<script type="text/javascript">
var switch_objs =
    [
        [ document.getElementById( "doc_swa" ), document.getElementById( "doc_swli" ), document.getElementById( "doc_block" ) ],
        [ document.getElementById( "pic_swa" ), document.getElementById( "pic_swli" ), document.getElementById( "pic_block" ) ]
    ];

function switchBlock( val )
{
    var i1 = 1, i2 = 0;
    if ( val == 1 ) {
        i1 = 0, i2 = 1;
    }
    switch_objs[ i1 ][ 0 ].className = "";
    switch_objs[ i1 ][ 1 ].className = "";
    switch_objs[ i1 ][ 2 ].style.display = "none";

    switch_objs[ i2 ][ 0 ].className = "active";
    switch_objs[ i2 ][ 1 ].className = "active";
    switch_objs[ i2 ][ 2 ].style.display = "block";
}


if ( window.location.hash == "#2" )
    switchBlock( 1 );
</script>
<?php
}
}
//! ---------------------------------- END page ---------------------------------- 
?>
  </div>

  <?php //print render($content['links']); ?>

  <?php //print render($content['comments']); ?>

</div>
