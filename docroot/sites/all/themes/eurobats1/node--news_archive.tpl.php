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
 
/*SELECT title, node.nid
#FROM field_data_field_archive INNER JOIN node ON
FROM (field_data_field_archive AS arh INNER JOIN field_data_field_publishing_date AS pdate ON (arh.entity_id = pdate.entity_id) AND (field_archive_value = 1))
INNER JOIN node ON arh.entity_id = node.nid
WHERE YEAR( field_publishing_date_value ) = 1999
ORDER BY field_publishing_date_value*/

@ $c = $_GET[ "c" ] + 0;
//if ( $c == 0 ) $c = 1;
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
News related to EUROBATS and other bat conservation activities.

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
  	
    	<img src="/<?php print $directory; ?>/img/bat_mark.png" alt="" />&nbsp;&nbsp; 
    	<strong<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></strong>
    
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


    <?php 
    $result = db_query( "SELECT DISTINCT YEAR( field_publishing_date_value ) AS nyear " .
        "FROM field_data_field_archive AS arh INNER JOIN field_data_field_publishing_date AS pdate ON arh.entity_id = pdate.entity_id " .
        "WHERE field_archive_value = 'Yes' " .
        "ORDER BY nyear DESC" );
    $colCount = 10;
    $tdS = "<td style=\"width : " . round( 100 / $colCount ). "%\">@TD@</td>";
    $aS = "<a href=\"?c=@KEY@\">@TD@</a>";
    $stS = "<strong>@TD@<strong>";

    $i = 0; $res = ""; $j = 1;$crKey = -1;
    foreach ( $result as $row ) {
        if ( $c == $j ) {
            $crKey = $row->nyear;
            $tdS1 = str_replace( "@TD@", $stS, $tdS );
        }
        else
            $tdS1 = str_replace( "@KEY@", $j, str_replace( "@TD@", $aS, $tdS ) );
        if ( $i == 0 ) $res .= "<tr>";

        $res .= str_replace( "@TD@", $row->nyear, $tdS1 );
        $i++;
        if ( $i == $colCount ) { $res .= "</tr>\n"; $i = 0; }
        $j++;
    }
    for ( $j = $i; $j < $colCount; $j++ ) {
        $res .= str_replace( "@TD@", "&nbsp;", $tdS );
    }
    echo "<table style=\"width :100%\">$res</table>";
    ?>

    <div class="separator1"></div>


    <?php
    
    /*$sql = "SELECT title, node.nid " .
        "FROM (field_data_field_archive AS arh INNER JOIN field_data_field_publishing_date AS pdate ON (arh.entity_id = pdate.entity_id) AND (field_archive_value = 1)) " .
        "INNER JOIN node ON arh.entity_id = node.nid " .
        "WHERE YEAR( field_publishing_date_value ) = $crKey " .
        "ORDER BY field_publishing_date_value ";*/

    if ( $crKey != -1 ) {
        echo "<h2 style=\"font-size : 20px; margin-bottom : 15px; margin-top : 15px;\"><b>$crKey</b></h2>\n";
        $sql = "SELECT title, field_news_url_value AS url, uri AS pdf, node.nid " .
            "FROM (((field_data_field_archive AS arh INNER JOIN field_data_field_publishing_date AS pdate ON (arh.entity_id = pdate.entity_id) AND (field_archive_value = 'Yes')) " .
            "INNER JOIN node ON arh.entity_id = node.nid) " .
            "LEFT JOIN field_data_field_news_url AS nurl ON arh.entity_id = nurl.entity_id) " .
            "LEFT JOIN field_data_field_news_pdf AS npdf ON arh.entity_id = npdf.entity_id " .
            "LEFT JOIN file_managed ON npdf.field_news_pdf_fid = file_managed.fid " .
            "WHERE YEAR( field_publishing_date_value ) = $crKey " .
            "ORDER BY field_publishing_date_value";
        $sql = "SELECT title, field_news_url_value AS url, uri AS pdf, node.nid, body_value
            FROM (((field_data_field_archive AS arh INNER JOIN field_data_field_publishing_date AS pdate ON (arh.entity_id = pdate.entity_id) AND (field_archive_value = 'Yes'))
                INNER JOIN node ON arh.entity_id = node.nid)
                LEFT JOIN field_data_field_news_url AS nurl ON arh.entity_id = nurl.entity_id)
                LEFT JOIN field_data_field_news_pdf AS npdf ON arh.entity_id = npdf.entity_id
                LEFT JOIN file_managed ON npdf.field_news_pdf_fid = file_managed.fid
                LEFT JOIN field_data_body tlbbod ON tlbbod.entity_id = arh.entity_id
            WHERE YEAR( field_publishing_date_value ) = $crKey
            ORDER BY field_publishing_date_value";
        $aS = "<div style=\"margin-top : 15px; margin-bottom : 15px;\">" . 
			    "<div style=\" float : left; height : 14px; padding-left : 10px; padding-right : 15px; width : 14px;\"><img src=\"/$directory/img/bat_mark.png\" alt=\"\" /></div>" .
			    "<div style=\" float : left; width : 871px;\"><a href=\"@HREF@>@TITLE@</a></div><br />\n" .
			    "</div>" .
			    "<div style=\"clear : both; \"></div>";
        $result = db_query( $sql );
        foreach ( $result as $row ) {
            if ( $row->body_value != "" )
                $news_url = "/" . drupal_get_path_alias( "node/" . $row->nid ) . "\"";
            else
                if ( $row->url )
                    $news_url = $row->url . "\" target=\"_blank\"";
                elseif ( $row->pdf )
                        $news_url = file_create_url( $row->pdf ) . "\" target=\"_blank\"";
                    else
                        $news_url = "/" . drupal_get_path_alias( "node/" . $row->nid ) . "\"";
            echo str_replace( "@TITLE@", $row->title, str_replace( "@HREF@", $news_url, $aS ) );
            //echo $row->title . "<br />";
        }
    }
    
    //echo "<textarea style=\"height : 200px; width : 100%;\">";
    //echo $res;
    //echo "</textarea>";
    ?>

  <?php //print render($content['links']); ?>

  <?php //print render($content['comments']); ?>

</div>
