<?php

function remove_gf_placeholder_script( $scripts) {
	if (!is_admin()) {
		wp_deregister_script('gform_placeholder');
		wp_dequeue_script('gform_placeholder');
	}
}
add_action('wp_enqueue_scripts', 'remove_gf_placeholder_script');