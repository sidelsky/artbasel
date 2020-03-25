
<?php
/**
 * @return Number
 * Returns the WordPress Style version number
 */
function wp_style_version_no() {
   $theme = wp_get_theme();
   $versionNumber = $theme->get( 'Version' );
   return ' ' . 'V' . $versionNumber;
} 