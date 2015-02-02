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
include_once( "inc/php/libcommon.php" );
@ $stdObj       = $content['field_event_url']['#object'];
if ( !isset($stdObj) )
    @ $stdObj   = $content['field_event_file']['#object'];
if ( !isset($stdObj) )
    @ $stdObj   = $content['body']['#object'];

if ( isset($stdObj) ) {
    $lang = $stdObj->language;
    @ $event_url     = checkURL( $stdObj->field_event_url[ $lang ][ 0 ][ 'safe_value' ] );
    @ $event_file    = $stdObj->field_event_file[ $lang ][ 0 ][ 'uri' ];
    if ( $event_file != "" ) $event_file = file_create_url( $event_file );
    $news_body      = $stdObj->body;//[ $lang ][ 0 ][ 'safe_value' ];
    @$set_node_addr  = ( $news_body[ $lang ][ 0 ][ 'value' ] != "" );

}
else {
    $event_url  = null;
    $event_file = null;
}
$hide_read_more = true;
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
  <?php
        //hide($content['field_event_url']);
        //hide($content['field_event_file']);

        $hide_read_more = false;
        $n_url = $node_url;
        if ( !$set_node_addr )
            if( $event_url ) {
                    $hide_read_more = true;
                    $n_url = $event_url . "\" target=\"_blank";
                }
                elseif( $event_file ) {
                        $hide_read_more = true;
                        $n_url = $event_file . "\" target=\"_blank";
                    }
                    else {
                        $hide_read_more = false;
                        $n_url = $node_url;
                    }
    ?>
    <h3<?php print $title_attributes; ?>><a href="<?php print $n_url; ?>"><img src="/<?php print $directory; ?>/img/bat_mark.png" alt="" />&nbsp;&nbsp;<?php print $title; ?></a></h3>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content" style="margin-left : <?php echo ($page)?0:22; ?>px;"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      hide($content['field_event_url']);
      hide($content['field_event_file']);
      print render($content);

    if ( $page ) {
        if ( $event_url != "" ) {
        ?>
            <div style="padding-top : 7px;" class="field field-name-field-event-url field-type-text field-label-above">
                <div class="field-label" style=" margin : 3px 0 7px 0; ">Event URL: </div>
                <div class="field-items">
                    <div class="field-item even"><a href="<?php echo $event_url; ?>" target="_blank"><?php echo $event_url; ?></a></div>
                </div>
            </div>
        <?php
        }
        if ( $event_file != "" ) {
            $descr = $stdObj->field_event_file[ $lang ][ 0 ][ 'description' ];
            if ( $descr != "" )
                $pdf_tlt = $descr;
            else
                $pdf_tlt = substr( strrchr( $event_file, "/" ), 1 );
        ?>
        <div style="padding-top : 7px;" class="field field-name-field-event-file field-type-file field-label-above">
            <div class="field-label" style=" margin : 3px 0 7px 0; ">Related PDF: </div>
            <div class="field-items">
                <div class="field-item even">
                    <span class="file">
                        <a type="application/pdf" href="<?php echo $event_file; ?>" target="_blank">
                            <img class="file-icon" src="/sites/default/files/images/iconpdf.gif" title="application/pdf" alt="">
                            <?php echo $pdf_tlt; ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <?php
        }
    }
    
    

    if( !$hide_read_more ) {
        print "<div style=\"height : 2px; overflow : hidden; width : 100%;\"></div>";
        print "<a href=\"/" . drupal_get_path_alias( "node/" . $nid ) . "\"><strong>[Read on]</strong></a>";
    }
    ?>
  </div>


  <?php print render($content['comments']); ?>
<div class="separator"></div>

</div>
