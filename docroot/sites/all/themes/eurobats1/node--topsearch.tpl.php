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
SELECT field_doc_form_value, field_doc_file_description, filename 
#FROM ( field_data_field_doc_topic AS crfld LEFT JOIN field_data_field_resources AS fsfld ON crfld.entity_id = fsfld.entity_id )
FROM ( field_data_field_doc_topic AS crfld 
INNER JOIN field_data_field_doc_file AS fsdocfld ON crfld.entity_id = fsdocfld.entity_id )
LEFT JOIN field_data_field_doc_form AS fsdoctype ON crfld.entity_id = fsdoctype.entity_id
LEFT JOIN file_managed ON fsdocfld.field_doc_file_fid = file_managed.fid
WHERE field_doc_topic_value = ''
ORDER BY field_doc_form_value
*/

@ $c = $_GET[ "c" ] + 0;
//if ( $c == 0 ) $c = 1;
$crFldName      = "field_doc_topic";
$fsFldName      = "field_resources";
$fsDocFldName   = "field_doc_file";
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
<?php
    $result = db_query( "SELECT DISTINCT ".$crFldName."_value AS value FROM field_data_$crFldName  ORDER BY ".$crFldName."_value" );

    $tdS = "<td style=\"width : 50%\"><img src=\"/<?php print $directory; ?>/img/bat_mark.png\" alt=\"\" style=\" margin-right : 10px; \"/>@TD@</td>";
    $aS = "<a href=\"?c=@KEY@\">@TD@</a>";
    $stS = "<strong>@TD@<strong>";

    $i = 1; $res = ""; $crKey = -1; $tdStyle = "style=\"width : 50%\"";
    foreach ( $result as $row ) {
        if ( $c == $i ) {
            $crKey = $row->value;
            $tdS1 = str_replace( "@TD@", $stS, $tdS );
        }
        else
            $tdS1 = str_replace( "@KEY@", $i, str_replace( "@TD@", $aS, $tdS ) );

        if ( $i % 2 != 0 ) $res .= "<tr>";

        //$res .= "<td $tdStyle><a href=\"?c=$i\">" . $row->value . "</a></td>";
        $res .= str_replace( "@TD@", $row->value, $tdS1 );

        if ( $i % 2 == 0 ) $res .= "</tr>\n";
        $i++;
    }
    if ( $i % 2 == 0 ) $res .= "<td $tdStyle></td></tr>";
    echo "<table style=\"width :100%; margin-top : 15px; margin-bottom : 20px; \">$res</table>";
?>
<div class="separator1"></div><br />



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
      //print render($content);
        $sql = "SELECT field_doc_form_value AS rubric, uri, field_doc_file_description AS description " .
        "FROM ( field_data_field_doc_topic AS crfld " .
        "INNER JOIN field_data_field_doc_file AS fsdocfld ON crfld.entity_id = fsdocfld.entity_id ) " .
        "LEFT JOIN field_data_field_doc_form AS fsdoctype ON crfld.entity_id = fsdoctype.entity_id " .
        "LEFT JOIN file_managed ON fsdocfld.field_doc_file_fid = file_managed.fid " .
        "WHERE field_doc_topic_value = '$crKey' " .
        "ORDER BY field_doc_form_value";
        $sql = "SELECT field_doc_form_value AS rubric, uri, docdescr AS description, node.title, doc_title.field_doc_title_value AS doc_title
                FROM (
                SELECT doct.entity_id, field_doc_topic_value AS crfld, field_doc_file_ac_fid AS docfid, field_doc_file_ac_description AS docdescr, res.entity_id AS nid
                FROM field_data_field_doc_topic AS doct INNER JOIN field_data_field_doc_file_ac AS docf ON doct.entity_id = docf.entity_id
                    INNER JOIN field_data_field_resources_ac AS res ON res.field_resources_ac_value = doct.entity_id
                UNION
                SELECT doct.entity_id, field_doc_topic_value AS crfld, field_doc_file_mop_fid AS docfid, field_doc_file_mop_description AS docdescr, res.entity_id AS nid
                FROM field_data_field_doc_topic AS doct INNER JOIN field_data_field_doc_file_mop AS docf ON doct.entity_id = docf.entity_id
                    INNER JOIN field_data_field_resources_mop AS res ON res.field_resources_mop_value = doct.entity_id
                UNION
                SELECT doct.entity_id, field_doc_topic_value AS crfld, field_doc_file_stc_fid AS docfid, field_doc_file_stc_description AS docdescr, res.entity_id AS nid
                FROM field_data_field_doc_topic AS doct INNER JOIN field_data_field_doc_file_stc AS docf ON doct.entity_id = docf.entity_id 
                    INNER JOIN field_data_field_resources_stc AS res ON res.field_resources_stc_value = doct.entity_id
                ) AS doc_topic
                    LEFT JOIN field_data_field_doc_form AS fsdoctype ON doc_topic.entity_id = fsdoctype.entity_id
                    LEFT JOIN file_managed ON docfid = file_managed.fid
                    LEFT JOIN node ON doc_topic.nid = node.nid
                    LEFT JOIN field_data_field_doc_title AS doc_title ON node.nid = doc_title.entity_id
                WHERE crfld = '$crKey'
                ORDER BY field_doc_form_value";
        if ( $crKey != -1 ) {
            echo "<strong>Results for: </strong><i>$crKey</i><br />";
            $result = db_query( $sql ); $rubric = "";
            foreach ( $result as $row ) {
                if ( $row->rubric != $rubric ) {
                    $rubric = $row->rubric;
                    echo "<br /><p><u>$rubric</u></p>";
                }
                $descr = "";
    
                $descr = $row->description;             // Описание
                if ( $descr != "" ) $descr .=  " - ";   // Описание
    
                $descr .= "<strong>" . $row->title . "</strong>"; // Название
    
                if( $row->doc_title != "" ) $descr .= ", " . $row->doc_title; // Место ивремя
    
                if ( $descr == "" ) $descr = "No description";
                echo "<p><a href=\"" .file_create_url( $row->uri ) . "\" target=\"_blank\">$descr</a></p>";
                //<img src=\"/sites/default/files/images/iconpdf.gif\">
            }
        }
    ?>
  </div>

  <?php //print render($content['links']); ?>

  <?php //print render($content['comments']); ?>

</div>
