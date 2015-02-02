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
//echo "<textarea>";
//print( $fields );
//echo "</textarea>";
$news_url = "";
if( count($row->field_field_news_url) > 0 )
    $news_url = $row->field_field_news_url[ 0 ][ "raw" ][ "value" ];

$news_pdf = "";
if( count($row->field_field_news_pdf) > 0 )
    $news_pdf = file_create_url( $row->field_field_news_pdf[ 0 ][ "raw" ][ "uri" ] );
$news_title = $row->node_title;

$news_body = "";
//$news_summary = "";
if( count($row->field_body) > 0 ) {
    $news_body = $row->field_body[ 0 ][ "raw" ][ "value" ];
    //$news_summary = $row->field_body[ 0 ][ "raw" ][ "summary" ];
}
?>
<?php foreach ($fields as $id => $field): ?>

<?php //echo "<textarea>";
//print_r ( $row->field_field_news_pdf );
//print_r ( $field->content );
//echo "</textarea>"; ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

    <?php
        $sample = "<span class=\"field-content\"><a href=\"@HREF@>$news_title</a></span>";
        if ( $news_body != "" )
            //$cont = $field->content;
            $cont = str_replace( "@HREF@", "/" . drupal_get_path_alias( "node/" . $row->nid ) . "\"", $sample );
        else
       if( $news_url ) 
            $cont = str_replace( "@HREF@", $news_url . "\" target=\"_blank\"", $sample );
        elseif( $news_pdf )
                $cont = str_replace( "@HREF@", $news_pdf . "\" target=\"_blank\"", $sample );
            else 
                $cont = $field->content;
    ?>
    


  <?php print $field->wrapper_prefix; ?>
    <?php print $field->label_html; ?>
    <img src="<?php print $directory; ?>/img/bat_mark.png" alt="" />&nbsp;&nbsp;<?php print $cont; ?>
<div style="height : 15px; overflow : hidden; width : 100%"></div>
  <?php print $field->wrapper_suffix; ?>
<?php endforeach; ?>
