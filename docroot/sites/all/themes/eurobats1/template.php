<?php

//drupal_add_css(path_to_theme() . '/css/style_ie78.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
//drupal_add_css(path_to_theme() . '/css/style_ie78.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'IE 8', '!IE' => FALSE), 'preprocess' => FALSE));


function eurobats1_preprocess_page( &$vars, $hook ) {
    //drupal_add_js(path_to_theme() . '/inc/js/libcommon.js');
    //drupal_add_js(path_to_theme() . '/inc/js/libmenu.js');
    drupal_add_js(array('pathToTheme' => array('pathToTheme' => path_to_theme())), 'setting');
    if (isset($vars['node'])) {
       $vars['theme_hook_suggestions'][] = 'page__type__' . $vars['node']->type;
    }
   /* if (isset($vars['view'])) {
        drupal_set_message( 'asda1231212312312312sdsad' );
    }*/
}
//drupal_set_message( '123' );

function eurobats1_preprocess_view( &$vars ) {
    drupal_set_message( "view" );
}

function eurobats1_preprocess_views_view( &$vars ) {
    //drupal_set_message( $vars[ "name" ] );
    
    //if (isset($vars['view'])) {
        //ob_start();
        //print_r( $vars[ 'view' ] );
        //print( $vars[ 'view' ]->name );
        //drupal_set_message( ob_get_clean() );
        //drupal_set_message( "eurobats1_preprocess_views_view " .  $vars['view']->type );
        //$vars['theme_hook_suggestions'][] = 'views__view__' . $vars['view']->name;
    //}
}

/*function hook_node_insert( $node ) {
    drupal_set_message( "eurobats1_node_insert" );
}*/

function eurobats1_preprocess_search_result( &$vars ) {
    //drupal_set_message( "eurobats1_preprocess_search_result" );
    $result = $vars['result'];
    $vars['url'] = check_url($result['link']);
    $vars['title'] = check_plain($result['title']);

    $info = array();
    /* HIDE THIS FROM END USER
    if (!empty($result['type'])) {
    $info['type'] = check_plain($result['type']);
    }
    if (!empty($result['user'])) {
    $info['user'] = $result['user'];
    }
    if (!empty($result['date'])) {
    $info['date'] = format_date($result['date'], 'small');
    }
    if (isset($result['extra']) && is_array($result['extra'])) {
    $info = array_merge($info, $result['extra']);
    }
    */
    $info['user'] = "<br /><br />";
    // Check for existence. User search does not include snippets.
    $vars['snippet'] = isset($result['snippet']) ? $result['snippet'] : '';
    // Provide separated and grouped meta information..
    $vars['info_split'] = $info;
    $vars['info'] = implode(' - ', $info);
    // Provide alternate search result template.
    //$vars['template_files'][] = 'search-result-'. $vars['type'];
}


//drupal_set_message( 'asda1231212312312312sdsad' );
function eurobats1_preprocess_html(&$vars) {
//     drupal_add_css(path_to_theme() . '/css/style_ie78.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
//     drupal_add_css(path_to_theme() . '/css/style_ie78.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'IE 8', '!IE' => FALSE), 'preprocess' => FALSE));

}

function eurobats1_preprocess_node(&$vars) {
//     drupal_add_css(path_to_theme() . '/css/style_ie78.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
//     drupal_add_css(path_to_theme() . '/css/style_ie78.css', array('weight' => CSS_THEME, 'browsers' => array('IE' => 'IE 8', '!IE' => FALSE), 'preprocess' => FALSE));

}
?>
