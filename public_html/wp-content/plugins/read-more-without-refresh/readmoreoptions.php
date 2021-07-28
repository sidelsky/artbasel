<?php
/*
Plugin Name: Read More Without Refresh
Version: 3.1
Plugin URI: https://en.wordpress.org/plugins/read-more-without-refresh/
Description: Boost your SEO without affecting user experience. A simple plugin that will use Javascript actions to show/hide extra text, through a shortcode call.
Author: George Gkouvousis
Author URI: https://8web.gr/en/
License: GPL2
*/

/* load necessary stuff */
define('READ_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('READ_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('READ_VERSION', '3.1');

/* call colorpicker */
add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/main.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

/* register shortcodes */
function read_register_shortcodes() {
   add_shortcode('read', 'read_main');
}

/* do magic */
function read_main($atts, $content = null) {
	extract(shortcode_atts(array(
		'more' => 'READ MORE',
		'less' => 'READ LESS'
	), $atts));

	mt_srand((double)microtime() * 1000000);
	$rnum = mt_rand();
   
	$new_string = '<span><a onclick="read_toggle(' . $rnum . ', \'' . get_option('rm_text') . '\', \'' . get_option('rl_text') . '\'); return false;" class="read-link" id="readlink' . $rnum . '" style="readlink" href="#">' . get_option('rm_text') . '</a></span>' . "\n";
	$new_string .= '<div class="read_div" id="read' . $rnum . '" style="display: none;">' . do_shortcode($content) . '</div>';

	return $new_string;
}

/* load CSS */
function rmwr_dynamic_css() { ?>
<style type="text/css">

*[id^='readlink'] {
 font-weight: <?php echo get_option('rmwr_font_weight'); ?>;
 color: <?php echo get_option('rmwr_text_color'); ?>;
 background: <?php echo get_option('rmwr_background_color'); ?>;
 padding: <?php echo get_option('rmwr_padding'); ?>;
 border-bottom: <?php echo get_option('rmwr_border_bottom'); ?> solid <?php echo get_option('rmwr_border_bottom_color'); ?>;
 -webkit-box-shadow: none !important;
 box-shadow: none !important;
 -webkit-transition: none !important;
}

*[id^='readlink']:hover {
 font-weight: <?php echo get_option('rmwr_font_weight'); ?>;
 color: <?php echo get_option('rmwr_text_hover_color'); ?>;
 padding: <?php echo get_option('rmwr_padding'); ?>;
 border-bottom: <?php echo get_option('rmwr_border_bottom'); ?> solid <?php echo get_option('rmwr_border_bottom_color'); ?>;
}

*[id^='readlink']:focus {
 outline: none;
 color: <?php echo get_option('rmwr_text_color'); ?>;
}

</style>
<?php }
add_action( 'wp_head', 'rmwr_dynamic_css', 99 );

/* header actions */
add_action('wp_head', 'read_javascript');
add_action('init', 'read_register_shortcodes');

function read_javascript() {
	echo '<script>
	function expand(param) {
		param.style.display = (param.style.display == "none") ? "block" : "none";
	}
	function read_toggle(id, more, less) {
		el = document.getElementById("readlink" + id);
		el.innerHTML = (el.innerHTML == more) ? less : more;
		expand(document.getElementById("read" + id));
	}
	</script>';
}

class read_more_without_refresh_plugin {

    public function __construct() {
    	// Hook into the admin menu
    	add_action( 'admin_menu', array( $this, 'create_rmrl_settings_page' ) );

        // Add Settings and Fields
    	add_action( 'admin_init', array( $this, 'rmwr_sections' ) );
    	add_action( 'admin_init', array( $this, 'rmwr_fields' ) );
    }

    public function create_rmrl_settings_page() {
    	// Add the menu item and page
    	$page_title = 'Read More without Refresh';
    	$menu_title = 'RMWR Settings';
    	$capability = 'manage_options';
    	$slug = 'read_more_without_refresh';
    	$callback = array( $this, 'rmrl_settings_page_content' );
    	$icon = 'dashicons-text';
    	$position = 100;

    	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    public function rmrl_settings_page_content() {
	?>
	
    	<div class="wrap">
    		<h2>Read More Without Refresh settings</h2><?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
                  $this->admin_notice();
            } ?>
    		<form method="POST" action="options.php">
                <?php settings_fields( 'read_more_without_refresh' ); do_settings_sections( 'read_more_without_refresh' ); submit_button(''); ?>
    		</form>

		<br>If you appreciate my plugin, proove it with a donation.
		<br><a href="https://www.paypal.me/eightweb/20?message=Thanks+for+the+awesome+RMWR+plugin" target="_blank"><img style="margin-left: 0px;" src="<?php echo plugins_url('read-more-without-refresh/images/donate.png'); ?>"></a>
		<br><br>Made with <span class="dashicons dashicons-heart" style="color: #ff005c;"></span> by <a href="https://www.linkedin.com/in/georgegkouvousis/" target="_blank">George Gkouvousis</a>
    	</div> 
		
	<?php
    }
    
    public function admin_notice() { ?>
        <div class="notice notice-success is-dismissible">
            <p>RMRL settings have been updated.</p>
        </div><?php
    }

    public function rmwr_sections() {
        add_settings_section( 'rmrl_settings_first_section', '', array( $this, 'section_callback' ), 'read_more_without_refresh' );
	// future prediction        add_settings_section( 'rmrl_settings_second_section', 'Another options section', array( $this, 'section_callback' ), 'read_more_without_refresh' );
	// future prediction        add_settings_section( 'rmrl_settings_third_section', 'Yet another options section', array( $this, 'section_callback' ), 'read_more_without_refresh' );
    }

    public function section_callback( $arguments ) {
    	switch( $arguments['id'] ){
    		case 'rmrl_settings_first_section':
    			echo 'Below, you will find all needed options in order to customize the appearance of the Read More / Read Less buttons:<br><br>';
    			break;
    		case 'rmrl_settings_second_section':
    			// future prediction
    			break;
    		case 'rmrl_settings_third_section':
    			// future prediction
    			break;
    	}
    }

    public function rmwr_fields() {
        $fields = array(
            array(
        		'uid' => 'rm_text',
        		'label' => 'Read more text',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'text',
        		'placeholder' => 'Read More',
        		'default' => 'Read More',
        		'helper' => ' <span class="dashicons dashicons-info" style="vertical-align:middle; color:#a5a5a5"></span> Change \'Read More\' naming',
        	),
        	array(
        		'uid' => 'rl_text',
        		'label' => 'Read less text',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'text',
        		'default' => 'Read Less',
        		'placeholder' => 'Read Less',
        		'helper' => ' <span class="dashicons dashicons-info" style="vertical-align:middle; color:#a5a5a5"></span> Change \'Read Less\' naming',
        	),
        	array(
        		'uid' => 'rmwr_background_color',
        		'label' => 'Background color',
        		'type' => 'colorpicker',
        		'section' => 'rmrl_settings_first_section',
        		'placeholder' => '',
        		'default' => '#ffffff',
        		'helper' => '' ,
        	),
        	array(
        		'uid' => 'rmwr_text_color',
        		'label' => 'Text color',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'colorpicker',
        		'placeholder' => '#FF8500',
        		'default' => '#000000',
        		'helper' => '' ,
        	),
        	array(
        		'uid' => 'rmwr_text_hover_color',
        		'label' => 'Text hover color',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'colorpicker',
        		'placeholder' => '#191919',
        		'default' => '#191919',
        		'helper' => '' ,
        	),
        	array(
        		'uid' => 'rmwr_border_bottom_color',
        		'label' => 'Border bottom color',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'colorpicker',
        		'placeholder' => '',
        		'default' => '#000000',
        		'helper' => '' ,
        	),
        	array(
        		'uid' => 'rmwr_font_weight',
        		'label' => 'Font weight',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'text',
        		'default' => 'normal',
        		'placeholder' => 'normal',
        		'helper' => ' <span class="dashicons dashicons-info" style="vertical-align:middle; color:#a5a5a5"></span> Enter <b>normal</b> , <b>bold</b> or any numeric value supported by your font',
        	),
        	array(
        		'uid' => 'rmwr_border_bottom',
        		'label' => 'Border bottom',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'text',
        		'default' => '1px',
        		'placeholder' => '',
                'helper' => ' <span class="dashicons dashicons-info" style="vertical-align:middle; color:#a5a5a5"></span> Enter pixels, i.e <b>1px</b>',
        	),
        	array(
        		'uid' => 'rmwr_padding',
        		'label' => 'Padding',
        		'section' => 'rmrl_settings_first_section',
        		'type' => 'text',
        		'default' => '0px',
        		'placeholder' => '',
                'helper' => ' <span class="dashicons dashicons-info" style="vertical-align:middle; color:#a5a5a5"></span> Enter pixels, i.e <b>1px</b>',
        	),
        );
            
        // parse of the fields
    	foreach( $fields as $field ){
        	add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'read_more_without_refresh', $field['section'], $field );
            register_setting( 'read_more_without_refresh', $field['uid'] );
    	}
    }

    public function field_callback( $arguments ) {

        $value = get_option( $arguments['uid'] );

        if( ! $value ) {
            $value = $arguments['default'];
        }

        switch( $arguments['type'] ){
        // future predictions
            case 'text':
            case 'password':
            case 'number':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'colorpicker':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" class="cpa-color-picker" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'textarea':
                printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
                break;
            case 'select':
            case 'multiselect':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, true ) ], $key, false ), $label );
                    }
                    if( $arguments['type'] === 'multiselect' ){
                        $attributes = ' multiple="multiple" ';
                    }
                    printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup );
                }
                break;
            case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
                    foreach( $arguments['options'] as $key => $label ){
                        $iterator++;
                        $options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked( $value[ array_search( $key, $value, true ) ], $key, false ), $label, $iterator );
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
        }

        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }


    }

}
new read_more_without_refresh_plugin();