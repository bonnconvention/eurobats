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
    $sample1 = "<div style=\"height : 13px; width : 100%;\"></div><h2><strong><a href=\"@HREF@\" >@COU@</a></strong></h2>";
    $sample2 = "<div style=\"margin-left : 10px;\">

				<div style=\"float : left; margin-top : 10px;\">
				<img alt=\"\" src=\"/<?php print $directory; ?>/img/bat_mark.png\" >&nbsp;&nbsp;
				</div>
				<div style=\"float : left; width : 860px;\">
                <h3 datatype=\"\" property=\"dc:title\">
                <a href=\"@HREF@?mode=lightframe\" rel=\"lightframe[Summaries|width:800px; height:600px;][@TITLE@]\">
                @TITLE@
                </a>
                </h3>
				</div>
				<div style=\"clear : both; height : 2px;\">&nbsp;</div>

                <div class=\"content\" style=\"margin-left : 23px;\">
                <div class=\"field field-name-body field-type-text-with-summary field-label-hidden\">
                <div class=\"field-items\">
                <div class=\"field-item even\" property=\"content:encoded\">
                @SUMMARY@
                </div>
                </div>
                </div>
                </div>

                <ul class=\"links inline\" style=\"margin-left : 17px;\">
                <li class=\"node-readmore first last\">
                <a title=\"@TITLE@\" rel=\"lightframe[Summaries|width:800px; height:600px;][@TITLE@]\" href=\"@HREF@?mode=lightframe\">
                <strong>[Read on]</strong>
                <span class=\"element-invisible\"> about @TITLE@</span>
                </a>
                </li>
                </ul>
                </div><br />

                ";

    $sql = "SELECT DISTINCT tlbcou.title AS name, tlbcou.nid AS pkey
            FROM field_data_field_sum_cou AS tlbsumckey INNER JOIN node AS tlbcou ON tlbsumckey.field_sum_cou_ebenum = tlbcou.nid
            ORDER BY name";
    $res = db_query( $sql );
    foreach ( $res as $row ) {
        $href = "/" . drupal_get_path_alias( "node/" . $row->pkey );
        $out = str_replace( "@COU@",  $row->name, str_replace( "@HREF@", $href, $sample1 ) );
        
        $sql1 = "SELECT title, body_summary, tlbsum.nid
                FROM node AS tlbsum LEFT JOIN field_data_body AS tlbsumb ON tlbsum.nid = tlbsumb.entity_id
                    INNER JOIN field_data_field_sum_cou AS tlbsumckey ON tlbsum.nid = tlbsumckey.entity_id
                WHERE ( type = 'summary' ) AND ( tlbsumckey.field_sum_cou_ebenum = " . $row->pkey . " )
                ORDER BY title";
        $res1 = db_query( $sql1 );
        foreach ( $res1 as $row1 ) {
            $href = "/" . drupal_get_path_alias( "node/" . $row1->nid );
            $out .= str_replace( "@TITLE@", $row1->title, str_replace( "@HREF@", $href, str_replace( "@SUMMARY@", $row1->body_summary, $sample2 ) ) );
        }
        echo $out;
    }

    ?>
  </div>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
