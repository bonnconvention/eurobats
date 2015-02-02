<?php
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */


$event_url = null;
if( count($row->field_field_event_url) > 0 )
    $event_url = $row->field_field_event_url[ 0 ][ "raw" ][ "value" ];

$event_file = null;
if( count($row->field_field_event_file) > 0 )
    $event_file = file_create_url( $row->field_field_event_file[ 0 ][ "raw" ][ "uri" ] );
$event_title = $row->node_title;

$event_body = "";
$event_summary = "";
if( count($row->field_body) > 0 ) {
    $event_body = $row->field_body[ 0 ][ "raw" ][ "value" ];
    $event_summary = $row->field_body[ 0 ][ "raw" ][ "summary" ];
}

//echo "<textarea>";
//print( count($row->field_body) );
//print_r( $row->field_body );
//print_r( $row );
//echo "</textarea>";

?>


<?php foreach ($fields as $id => $field): ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>


   <?php
        $sample = "<span class=\"field-content\"><a href=\"@HREF@><b>$event_title</b></a></span>$event_summary";
        if ( $event_body != "" )
            //$cont = $field->content . $event_summary;
            $cont = str_replace( "@HREF@", "/" . drupal_get_path_alias( "node/" . $row->nid ) . "\"", $sample );
        else
       if( $event_url ) 
            $cont = str_replace( "@HREF@", $event_url . "\" target=\"_blank\"", $sample );
        elseif( $event_file )
                $cont = str_replace( "@HREF@", $event_file . "\" target=\"_blank\"", $sample );
            else 
                $cont = $field->content . $event_body;
            //$cont = $field->content;
    ?>

  <?php print $field->wrapper_prefix; ?>
    <?php print $field->label_html; ?>

    <img src="<?php print $directory; ?>/img/bat_mark.png" alt="" />&nbsp;&nbsp;<?php print $cont;?>
<div style="height : 15px; overflow : hidden; width : 100%"></div>
  <?php print $field->wrapper_suffix; ?>
<?php endforeach; ?>
