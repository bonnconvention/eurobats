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
@ $mode = $_GET[ "mode" ];
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print $user_picture; ?>

  <?php print render($title_prefix); ?>
  

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
  
  <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']); ?>
  <div style=" margin-top : 10px;" >
  
  <?php if ( ( $mode != "print" ) ) {
        echo "<div style=\"float : right; margin-top : -35px;\"><a target=\"_blank\" href=\"?mode=print\"><img src=\"/<?php print $directory; ?>/img/print.png\" title=\"Print-friendly version\" alt=\"Print-friendly version\" /></a></div>";
        echo "<div style=\"clear : both;\"></div>";
      }
	  //print render($content);
	  ?>
      
    
  		<i><?php print render($content['field_author']); ?></i>
        <?php print render($content['field_bat_image']); ?>
       
    </div>  
    
    <div style="height : 20px;">
    	<?php print render($content['field_sp_authority']); ?>
    </div>
    <?php print render($content['field_sp_com_name']); ?>
    
    <div style="float : none;">
		<?php print render($content['field_bat_class']); ?>
        <?php print render($content['field_bat_order']); ?> 
        <?php print render($content['field_bat_family']); ?> 
    </div>
    <div style="width : 100%; height : 50px;">&nbsp;</div>
      	
    <?php  print render($content['field_species_details']); ?>
    <?php  print render($content['field_iucn_status']);  
    	   print render($content['field_population_trend']); 
		   print render($content['field_distribution_map']);
		   print render($content['field_geo_range']);
		   print render($content['field_critical_sites']);
		   print render($content['field_threats']);
		  ?> 
          
    <div> 
    	
    </div>
    
    
    
    
    
      
   <?php 
   
    //!------------------------------
    //! Summaries
    $sql = "SELECT DISTINCT title, nid
            FROM node INNER JOIN field_data_field_sum_rbs AS tlbrbs ON nid = tlbrbs.entity_id
            WHERE ( type = 'summary' ) AND ( status = 1 ) AND ( field_sum_rbs_ebenum = $nid )
            ORDER BY title";
    $out = "";
    $res = db_query( $sql );
    foreach ( $res as $row ) {
        $out .= "<li><a href=\"" . "/" . drupal_get_path_alias( "node/" . $row->nid ) . "\" target=\"_blank\">" . $row->title . "</a></li>";
    }
    if ( $out != "" ) {
        echo "<div style=\" height: 7px; width : 100%; \"></div><strong>Summaries of conducted projects:</strong> ";
        echo "<ul>" . $out . "</ul>";    /*<div style=\"height : 7px; width : 100%\"></div>*/
    }
//! -------------------------------- Publications --------------------
   $sql = "SELECT title, nid
            FROM
            ( (SELECT CONCAT( 'EUROBATS Publication Series, No ', field_epub_no_value ) AS title, tlbbs.entity_id AS nid, tlbbs.field_ebup_rbs_ebenum AS bskey
            FROM field_data_field_ebup_rbs AS tlbbs INNER JOIN field_data_field_epub_no AS tlbno ON tlbbs.entity_id = tlbno.entity_id)
            UNION
            (SELECT title, nid, tlbbs.field_eotherbup_rbs_ebenum AS bskey
            FROM field_data_field_eotherbup_rbs AS tlbbs INNER JOIN node ON tlbbs.entity_id = node.nid) ) AS t1
            WHERE bskey = $nid
            ORDER BY title";
    $sample1 = "<li><a href=\"@HREF@\" target=\"_blank\">@TEXT@</a></li>";
    $res = db_query( $sql );
    $out = "";
    foreach( $res as $row ) {
        $href = drupal_get_path_alias( "/node/" . $row->nid );
        $out .= str_replace( array( "@HREF@", "@TEXT@" ), array( $href, $row->title ), $sample1 );
    }
    if ( strlen( $out ) != 0 ) {
        //$out = substr( $out, 0, strlen( $out ) - 2 );
        echo "<div class=\"field-label bat-sp-label\"><strong>Publications: </strong></div><ul>" . $out . "</ul>";
    }
//! -------------------------------- National reports --------------------
    $sql = "SELECT title, nid
            FROM node
            WHERE ( type = 'country_profiles' ) AND ( status = 1 )
            ORDER BY title";
    $sqlsample = "SELECT tlbcou.title AS name, tlbfp.uri, field_natrep_group_value AS bkey, field_natrep_year_value AS syear,
            CONCAT( field_natrep_year_value, IF( ISNULL( field_natrep_byear_value ), '', field_natrep_byear_value ) )  AS byear,
            tlbpdf.uri AS bfile, CONCAT( '(', field_natrep_group_value, ')' ) AS bdescr,
            tlbcou.nid AS pkey, CONCAT( '(', tlbnrl.field_natrep_lang_value, ')' ) AS lng
        ,tlbnrbs.field_natrep_pbs_ebenum
        FROM (SELECT * FROM node WHERE type = 'nat_rep' ) AS tlbnr LEFT JOIN field_data_field_natrep_country AS tlbnrckey ON tlbnr.nid = tlbnrckey.entity_id
            INNER JOIN node AS tlbcou ON tlbcou.nid = tlbnrckey.field_natrep_country_ebenum
            INNER JOIN field_data_field_countryp_pr AS tlbcoupr ON tlbcou.nid = tlbcoupr.entity_id
            LEFT JOIN field_data_field_country_fp AS tlbfpkey ON tlbcou.nid = tlbfpkey.entity_id
            LEFT JOIN file_managed AS tlbfp ON tlbfp.fid = tlbfpkey.field_country_fp_fid
            LEFT JOIN field_data_field_natrep_group AS tlbnrg ON tlbnr.nid = tlbnrg.entity_id
            LEFT JOIN field_data_field_natrep_year AS tlbnry ON tlbnr.nid = tlbnry.entity_id
            LEFT JOIN field_data_field_natrep_byear AS tlbnrby ON tlbnr.nid = tlbnrby.entity_id
            LEFT JOIN field_data_field_natrep_pdf AS tlbnrpdfkey ON tlbnr.nid = tlbnrpdfkey.entity_id
            LEFT JOIN file_managed AS tlbpdf ON tlbpdf.fid = tlbnrpdfkey.field_natrep_pdf_fid
            LEFT JOIN field_data_field_natrep_lang AS tlbnrl ON tlbnr.nid = tlbnrl.entity_id
            LEFT JOIN field_data_field_natrep_pbs AS tlbnrbs ON tlbnr.nid = tlbnrbs.entity_id
        WHERE ( tlbcou.nid = @COUKEY@ ) AND ( tlbnrbs.field_natrep_pbs_ebenum = $nid )
        ORDER BY name, bkey, lng, syear, byear";
    $sample1 = "<a href=\"@HREF@\" target=\"_blank\">@YEAR@</a>, ";
    $res1 = db_query( $sql );
    $rhead = true;
    foreach( $res1 as $row1 ) {
        $out = "";
        $sql1 = str_replace( "@COUKEY@", $row1->nid, $sqlsample );
        $res = db_query( $sql1 );
        $block = -1; $block1 = -1;
        foreach( $res as $row ) {
            if ( $block1 == -1 )
                $block1 = $row->lng;
            if ( $block != $row->bkey ) {
                //if ( $block != -1 ) $out .= "<br />";
                if ( $block != -1 ) {
                    $out = substr( $out, 0, strlen( $out ) - 2 );
                    $out .= "; ";
                }
                $out .=  ( ($row->bdescr != "")?$row->bdescr . ":&nbsp;&nbsp;":"");
                $block = $row->bkey;
            }
            if( $block1 != $row->lng ) {
                $out = substr( $out, 0, strlen( $out ) - 2 );
                $out .= " $block1; ";
                $block1 = $row->lng;
    
            }
            $href = file_create_url( $row->bfile );
            $out .= str_replace( "@YEAR@", $row->byear, str_replace( "@HREF@", $href, $sample1 ) );
        }
        $out = rtrim( $out, "\n\ ," );
        if ( $out != "" ) {
            if( $block1 != "" ) $out .= " $block1<br />";
            if ( $rhead ) {
                echo "<div class=\"field-label bat-sp-label\"><strong>National Reports: </strong></div>";
                $rhead = false;
            }
            echo "<strong style=\"font-style : italic;\">" . $row1->title . "</strong>&nbsp;" . $out . "<br />";
        }
    }
    
    ?>
  </div>

  <?php if (!$page): ?>
    <i<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>">&nbsp;&nbsp;<?php print $title; ?></a></i>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php /*print render($content['links']); */?>

  <?php print render($content['comments']); ?>

</div>
