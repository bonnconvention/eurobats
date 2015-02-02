<?php
function out_bco( $nid, $type ) 
{
    $sql = "SELECT field_bco_name_value AS name, uri, lwidth, field_bco_www_value AS url " .
            "FROM ( ( field_data_field_bco_details AS bcod INNER JOIN field_data_field_bco_name AS bcon ON bcod.field_bco_details_value = bcon.entity_id ) " .
            "    LEFT JOIN ( " .
            "        SELECT uri, entity_id, field_bco_logo_width AS lwidth " .
            "        FROM field_data_field_bco_logo INNER JOIN file_managed ON field_bco_logo_fid = fid " .
            "    ) AS bcol ON bcod.field_bco_details_value = bcol.entity_id ) " .
            "    LEFT JOIN field_data_field_bco_www AS bcow ON bcod.field_bco_details_value = bcow.entity_id " .
            "WHERE bcod.entity_id = $nid " .
            "ORDER BY bcod.delta";
    $res = db_query( $sql );

    $m_left = 0;
    $m_right = 20;
    $sample1 = "<div style=\"float : left; margin-left : " . $m_left . "px; margin-right : " . $m_right .
                "px; width : @DWIDTH@px;\"><a href=\"@HREF@\" target=\"_blank\"><img src=\"@LOGO@\" /><br />@NAME@</a></div>\n";
    $sample2 = "<li><a href=\"@HREF@\" target=\"_blank\">@NAME@</a></li>";
    $sample = "";

    switch ( $type ) {
        case 1 :
            $sample = $sample1;
            echo "<br />";
            break;
        case 2 :
            $sample = $sample2;
            echo "<ul>";
            break;
    }
    //echo "<div style=\"background-color : #ff0000; float:left;height : 200px;\"><ul>";
    
    $i = 0;
    foreach ( $res as $row ) {
        $logo = file_create_url( $row->uri );
        $d_width = $row->lwidth + $m_left + $m_right;
        echo str_replace( "@DWIDTH@", $d_width, str_replace( "@HREF@", $row->url, str_replace( "@NAME@", $row->name, str_replace( "@LOGO@", $logo, $sample ) ) ) );
        $i++;
        if ( ( $i == 6 ) && ( $type == 1 ) ) {
            echo "<div style=\"clear : both; padding-top : 20px;\"></div>";
            $i = 0;
        }
    }
    //echo "</ul></div>";
    switch ( $type ) {
        case 1 :
            break;
        case 2 :
            echo "</ul>";
            break;
    }
    
}
?>