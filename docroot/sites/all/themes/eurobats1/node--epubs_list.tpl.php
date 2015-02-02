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
    $sql = "SELECT field_epub_no_value AS no, field_epub_articles_value AS art, uri AS cov
            FROM node INNER JOIN field_data_field_epub_no AS tlbno ON nid = tlbno.entity_id
                LEFT JOIN field_data_field_epub_articles AS tlbart ON nid = tlbart.entity_id
                INNER JOIN field_data_field_ebup_cover AS tlbcov ON nid = tlbcov.entity_id
                INNER JOIN file_managed ON field_ebup_cover_fid = fid
            WHERE ( node.type = 'epubs' ) AND ( node.status = 1 )
            ORDER BY no DESC";
    $sample1 = "<div style=\"float : left; width : 120px; \"><img src=\"@SRC@\" alt=\"\" /></div>\n";
    $sample11 = "<div style=\"float : left; width : 120px; \">&nbsp;</div>\n";
    $sample2 = "<div style=\"float : left; margin-left : 5px; width : 40px;\"><strong>No. @NO@:</strong></div><div style=\"float : left; width : 700px;\">@ART@</div>\n";
    $sample3 = "<div style=\"clear : both;\"></div>\n";
    $sample4 = "<div style=\"height : 30px; width : 100%;\">&nbsp;</div>";
    $res = db_query( $sql );
    $i = 0; $out = "<br />";
    foreach ( $res as $row ) {

        $out1 = str_replace( "@NO@", $row->no, str_replace( "@ART@", $row->art, $sample2 ) );
		$out1 = str_replace( "@SRC@", file_create_url( $row->cov ), $sample1 ) . $out1 . $sample11;
    /*    if ( ( $i % 2 ) == 0 )
            $out1 = str_replace( "@SRC@", file_create_url( $row->cov ), $sample1 ) . $out1 . $sample11;
        else
            $out1 = $sample11 . $out1 . str_replace( "@SRC@", file_create_url( $row->cov ), $sample1 ); */
        if ( $i != 0 )
            $out .= $sample4;
        $out .= $out1 . $sample3;
        $i++;
    }
    //print "<textarea>";
    print $out;
    //print "</textarea>"
    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
