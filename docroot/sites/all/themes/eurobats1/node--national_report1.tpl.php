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
/* SELECT nat_rep.isparties, map_countries.alpha2, map_countries.name, nat_rep1.*, nat_rep.bdescr
FROM map_countries RIGHT JOIN (nat_rep INNER JOIN nat_rep1 ON nat_rep.pkey = nat_rep1.bkey) ON nat_rep.ckey = map_countries.pkey
#FROM nat_rep1 RIGHT JOIN ( map_countries RIGHT JOIN nat_rep ON nat_rep.ckey = map_countries.pkey ) ON nat_rep.pkey = nat_rep1.bkey
ORDER BY nat_rep.isparties DESC, map_countries.name, nat_rep.forder, nat_rep1.byear */

@ $show = $_POST[ "show" ];
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
      hide($content['field_nr_footer']);
      print render($content);
      ///official_documents/national_reports


@    $paYK = 0 + $_POST[ "year1" ];
@    $paCK = 0 + $_POST[ "country1" ];
@    $rsYK = 0 + $_POST[ "year2" ];
@    $rsCK = 0 + $_POST[ "country2" ];

        $sql = "SELECT DISTINCT tlbcou.title AS name, tlbcou.nid AS pkey,
                IF(field_countryp_pr_value = 'Parties', 0, IF(field_countryp_pr_value = 'Range states', 1, 2 ) ) AS parties
                FROM (SELECT * FROM node WHERE type = 'nat_rep' ) AS tlbnr LEFT JOIN field_data_field_natrep_country AS tlbnrckey ON tlbnr.nid = tlbnrckey.entity_id
                    INNER JOIN node AS tlbcou ON tlbcou.nid = tlbnrckey.field_natrep_country_ebenum
                    INNER JOIN field_data_field_countryp_pr AS tlbcoupr ON tlbcou.nid = tlbcoupr.entity_id
                ORDER BY name";

        $res = db_query( $sql );

        $paCountries = "";
        $rsCountries = "";
        $paCAll = (($paCK == 0)?"selected":"");
        $paCNone = (( $paCK == -1 )?"selected":"");
        $rsCAll = (($rsCK == 0)?"selected":"");
        $rsCNone = (( $rsCK == -1 )?"selected":"");
        foreach ($res as $row)
            switch ( $row->parties + 0 ) {
                case 0 : 
                    $paCountries .= "<option value=\"" . $row->pkey . "\" " . (($row->pkey == $paCK)?"selected":""). ">" . $row->name . "</option>\n";
                    break;
                case 1 :
                    $rsCountries .= "<option value=\"" . $row->pkey . "\" " . (($row->pkey == $rsCK)?"selected":""). ">" . $row->name . "</option>";
                    break;
            }

        $sql = "SELECT DISTINCT field_natrep_year_value AS syear,
                IF(field_countryp_pr_value = 'Parties', 0, IF(field_countryp_pr_value = 'Range states', 1, 2 ) ) AS parties
                FROM (SELECT * FROM node WHERE type = 'nat_rep' ) AS tlbnr LEFT JOIN field_data_field_natrep_country AS tlbnrckey ON tlbnr.nid = tlbnrckey.entity_id
                    INNER JOIN node AS tlbcou ON tlbcou.nid = tlbnrckey.field_natrep_country_ebenum
                    INNER JOIN field_data_field_countryp_pr AS tlbcoupr ON tlbcou.nid = tlbcoupr.entity_id    
                    LEFT JOIN field_data_field_country_fp AS tlbfpkey ON tlbcou.nid = tlbfpkey.entity_id
                    LEFT JOIN field_data_field_natrep_year AS tlbnry ON tlbnr.nid = tlbnry.entity_id
                ORDER BY syear";
        $res = db_query( $sql );

        $paYears = "";
        $rsYears = "";
        $paYAll = (($paYK == 0)?"selected":"");
        $paYNone = (( $paYK == -1 )?"selected":"");
        $rsYAll = (($rsYK == 0)?"selected":"");
        $rsYNone = (( $rsYK == -1 )?"selected":"");
        foreach ($res as $row)
            switch ( $row->parties + 0 ) {
                case 0 : 
                    $paYears .= "<option value=\"" . $row->syear . "\" " . (($row->syear == $paYK)?"selected":""). ">" . $row->syear . "</option>\n";
                    break;
                case 1 :
                    $rsYears .= "<option value=\"" . $row->syear . "\" " . (($row->syear == $rsYK)?"selected":""). ">" . $row->syear . "</option>";
                    break;
            }
    ?>

    <form method="post" action="" >
    <input name="show" type="hidden" value="1" />
    <table style="width : 100%"><tr><td style="width : 50%">
    <strong>Report of Parties</strong>

    <table style="width : 100%">
        <tr><td style="text-align : right; width : 60px;">Year&nbsp;&nbsp;</td><td>
        <select name="year1" style="width : 90%; ">
            <option value="0" <?php echo $paYAll; ?>>- All -</option>
            <option value="-1" <?php echo $paYNone; ?>>- None -</option>
            <?php echo $paYears; ?>
        </select>
        </td></tr>
        <tr><td style="text-align : right; width : 60px;">Country&nbsp;&nbsp;</td><td>
        <select name="country1" style="width : 90%; ">
            <option value="0" <?php echo $paCAll; ?>>- All -</option>
            <option value="-1" <?php echo $paCNone; ?>>- None -</option>
            <?php echo $paCountries; ?>
        </select>
        </td></tr>
    </table>
    
    </td>
    
    <td style="width : 50%">
    <strong>Report of Non-Party Range States</strong>
    <table style="width : 100%">
        <tr><td style="text-align : right; width : 60px;">Year&nbsp;&nbsp;</td><td>
        <select name="year2" style="width : 90%; ">
            <option value="0" <?php echo $rsYAll; ?>>- All -</option>
            <option value="-1" <?php echo $rsYNone; ?>>- None -</option>
            <?php echo $rsYears; ?>
        </select>
        </td></tr>
        <tr><td style="text-align : right; width : 60px;">Country&nbsp;&nbsp;</td><td>
        <select name="country2" style="width : 90%; ">
            <option value="0" <?php echo $rsCAll; ?>>- All -</option>
            <option value="-1" <?php echo $rsCNone; ?>>- None -</option>
            <?php echo $rsCountries; ?>
        </select>
        </td></tr>
    </table>
    
    </td>
    </tr>
    <tr><td colspan="2" style="text-align : right"><input class="ebbtn" type="submit" value="Search" /></td></tr>
    </table>
    </form>
    <?php
    ?>
    
    
    
    <?php

    function outNatRep( $is_parties, $year, $country ) {
        $sqlsample = "SELECT tlbcou.title AS name, tlbfp.uri, field_natrep_group_value AS bkey, field_natrep_year_value AS syear,
                        CONCAT( field_natrep_year_value, IF( ISNULL( field_natrep_byear_value ), '', field_natrep_byear_value ) )  AS byear,
                        tlbpdf.uri AS bfile, CONCAT( '(', field_natrep_group_value, ')' ) AS bdescr,
                        tlbcou.nid AS pkey, CONCAT( '(', tlbnrl.field_natrep_lang_value, ')' ) AS lng
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
                    WHERE ( field_countryp_pr_value  = '@COND@' )
                    @YEARCOND@
                    @COUNTRYCOND@
                    ORDER BY name, bkey, lng, syear, byear";

        $sqlsampley = "AND ( field_natrep_year_value = @YEAR@ )";
        $sqlsamplec = "AND ( tlbcou.nid = @CKEY@ )";


        if ( $year == 0 )
            $sqlsampley = "";
        else
            $sqlsampley = str_replace( "@YEAR@", $year, $sqlsampley );

        if ( $country == 0 )
            $sqlsamplec = "";
        else
            $sqlsamplec = str_replace( "@CKEY@", $country, $sqlsamplec );


        $sql = str_replace( "@COND@", ($is_parties?"Parties":"Range states"), str_replace( "@COUNTRYCOND@", $sqlsamplec, str_replace( "@YEARCOND@", $sqlsampley, $sqlsample ) ) );
        $res = db_query( $sql );
        $sample1 = "<tr>\n<td style=\"width : 450px; vertical-align : top;\">@C0@</td>\n<td style=\"width : 450px; vertical-align : top;\">@C1@</td>\n</tr>\n";
        //$sample2 = "<tr>\n<td colspan=\"2\" style=\"width : 900px; vertical-align : top;\">Not found</td>\n</tr>\n";
        $sample3 = "<table><tr><td><a href=\"@HREF@\"><img src=\"@CC@\" alt=\"\" /></a>&nbsp;&nbsp;&nbsp;</td><td><a href=\"@HREF@\"><strong>@NAME@</strong></a></td></tr></table>";

        $sample4 = "<a href=\"@HREF@\" target=\"_blank\">@YEAR@</a>&nbsp; ";

        $sample5 = "<div style=\"float : left; height : 100px; padding-right : 70px; width : 400px;\">@CO@<div style=\"padding-left : 40px;\">@YE@</div></div>";

        $country = ""; $str1 = ""; $out = ""; $block = -1; $block1 = ""; $str2 = "";
        foreach ( $res as $row ) {
            if ( $country != $row->name ) {
                $block = -1;
                if( $block1 != "" ) $str1 .= "$block1<br />";
                $block1 = $row->lng;
                if ( $str1 . $str2 != "" )
                    //$out .= str_replace( "@COYI@", $str1, $sample5 );
                    //$out .= str_replace( "@COYI@", $str2 . "<div style=\"padding-left : 50px;\">" . $str1 . "</div>", $sample5 );
                    //$out .= str_replace( "@COYI@", $str2 . $str1, $sample5 );
                    $out .= str_replace( Array( "@CO@", "@YE@" ) , Array( $str2, $str1 ), $sample5 );
                $courl = "/" . drupal_get_path_alias( "node/" . $row->pkey );
                //$str1 = str_replace( "@CC@", file_create_url( $row->uri ), str_replace( "@HREF@", $courl, str_replace( "@NAME@", strtoupper( $row->name ), $sample3 ) ) );
                $str2 = str_replace( "@CC@", file_create_url( $row->uri ), str_replace( "@HREF@", $courl, str_replace( "@NAME@", strtoupper( $row->name ), $sample3 ) ) );
                $str1 = " ";
                $country = $row->name;
            }
            if ( $block != $row->bkey ) {
                if ( $block != -1 ) $str1 .= "<br />";
                //$str1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                $str1 .=  ( ($row->bdescr != "")?$row->bdescr . "&nbsp;&nbsp;&nbsp;&nbsp;":"");
                $block = $row->bkey;
            }
            if( $block1 != $row->lng ) {
                $str1 .= "$block1<br />";
                //$str1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                $block1 = $row->lng;
            }
            $str1 .= str_replace( "@YEAR@", $row->byear, str_replace( "@HREF@", file_create_url( $row->bfile ), $sample4 ) );
        }

        if ( $str1 != "" ) {
            if( $block1 != "" ) $str1 .= "$block1<br />";
            //$out .= str_replace( "@COYI@", $str2 . $str1, $sample5 );
            //$out .= str_replace( "@COYI@", $str2 . "<div style=\"padding-left : 50px;\">" . $str1 . "</div>", $sample5 );
            $out .= str_replace( Array( "@CO@", "@YE@" ) , Array( $str2, $str1 ), $sample5 );
        }

        if ( $out == "" ) $out = "No items to display";
        echo $out;

    } // ------------------------------------------------------------------------------


    if ( $show == 1 ) {
        echo "<br />
        <h3>Reports of Parties</h3>
        <div class=\"separator\"></div>";

        outNatRep( true, $paYK, $paCK );

        echo "<div style=\"clear : both;\"></div>\n<br /><h3>Reports of Non-Party Range States</h3>\n<div class=\"separator\"></div>\n";

        outNatRep( false, $rsYK, $rsCK );
    }
    ?>
    <div style="clear : both;"></div>

  </div>
  <br />
  <?php print render($content['field_nr_footer']); ?>

  <?php print render($content['links']); ?>

  <?php print render($content['comments']); ?>

</div>
