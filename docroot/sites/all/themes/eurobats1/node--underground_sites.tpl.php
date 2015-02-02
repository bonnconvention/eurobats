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
$stdObj = $content['field_introduction']['#object'];
$lang = $stdObj->language;
$introductionSites = $stdObj->field_introduction[ $lang ][ 0 ][ 'safe_value' ];
@ $listSites = $stdObj->field_list_of_sites[ $lang ][ 0 ][ 'safe_value' ];
@ $batSpecies = $stdObj->field_bat_species[ $lang ][ 0 ][ 'safe_value' ];
@ $mostBatSpeciesSites = $stdObj->field_sites_most_bat_species[ $lang ][ 0 ][ 'safe_value' ];
@ $siteListsPerCountry = $stdObj->field_lists_per_country[ $lang ][ 0 ][ 'safe_value' ];

?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
    
    <?php print $user_picture; ?>
    
    <?php print render($title_prefix); ?>
    <?php /* if (!$page): ?>
    <h3<?php print $title_attributes; ?>><?php print $title; ?></a></h3>
<ul>
    <li><a href="<?php print $node_url; ?>" onclick="setCookie( 'mreps_docs_tab', '0' );"><?php print $doc_title; ?></a></li>
    <?php if ($pic_block): ?>
    <li><a href="<?php print $node_url; ?>" onclick="setCookie( 'mreps_docs_tab', '1' );">Meeting pictures</a></li>
    <?php endif; ?>
    
</ul>
<?php endif; */?>

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
         ?>
    
    
    <?php if ($page): ?>
    <div>
    	<ul>
        <li><a href="javascript:void(0)" onclick="show( 0 );"><b>Introduction to underground sites </b></a></li>
        <li><a href="javascript:void(0)" onclick="show( 1 );"><b>List of internationally important underground sites </b></a></li>
        <li><a href="javascript:void(0)" onclick="show( 2 );"><b>Bat species </b></a></li>
        <li><a href="javascript:void(0)" onclick="show( 3 );"><b>Underground sites with most bat species </b></a></li>
        <li><a href="javascript:void(0)" onclick="show( 4 );"><b>Site lists per country </b></a></li>
        </ul>
        <div class="separator"></div>
        <div id="introductionSites" style="display : block;">
           <?php print render($content['field_introduction']); ?>
        </div>
        <div id="listSites" style="display : none;">
           <?php print render($content['field_list_of_sites']); ?>
        </div>
        <div id="batSpecies" style="display : none;">
           <?php print render($content['field_bat_species']); ?>
        </div>
        
        <div id="mostBatSpeciesSites" style="display : none;">
           <?php print render($content['field_sites_most_bat_species']); ?>
        </div>
        
        <div id="siteListsPerCountry" style="display : none;">
           <?php print render($content['field_lists_per_country']); ?>
        </div>
        
    </div>
    
    
    <script type="text/javascript">
        var Bodies = Array( document.getElementById("introductionSites"), document.getElementById("listSites"), document.getElementById("batSpecies"), document.getElementById("mostBatSpeciesSites"), document.getElementById("siteListsPerCountry"));

        function show( Index ) {
            var obj;
            for ( i = 0; i < Bodies.length; i++ ) {
                Bodies[ i ].style.display = "none";
            }
            Bodies[ Index ].style.display = "block";
        }


    </script>
    
    <?php endif; ?>
</div>



<?php //print render($content['links']); ?>

<?php print render($content['comments']); ?>
<div class="separator"></div>

</div>
