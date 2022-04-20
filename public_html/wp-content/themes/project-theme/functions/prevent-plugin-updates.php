<?php
/**
 * Theme My Login
 */
function filter_plugin_updates( $value ) {
    unset( $value->response['plugins/theme-my-login/theme-my-login.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );
