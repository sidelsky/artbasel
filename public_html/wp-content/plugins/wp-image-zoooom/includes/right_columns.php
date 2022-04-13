<?php

$now = time();

$zoooom_activation_time = get_option( 'zoooom_activation_time', '' );
$zoooom_version         = get_option( 'zoooom_version', '' );

if ( empty( $zoooom_activation_time ) || version_compare( $zoooom_version, IMAGE_ZOOM_VERSION, '<' ) ) {
	$zoooom_activation_time = $now;
	update_option( 'zoooom_activation_time', $now );
	update_option( 'zoooom_version', IMAGE_ZOOM_VERSION );
}


$show_discount = false;
if ( $now - 3 * 86400 < $zoooom_activation_time ) {
	$show_discount = true;
}

$start_date = date( 'j M', $zoooom_activation_time - 3 * 86400 );
$end_date   = date( 'j M', $zoooom_activation_time + 2 * 86400 );



function iz_convert_numbers_letters( $text, $from = 'numbers' ) {
	$alphabet = str_split( 'abcdefghij' );
	$numbers  = str_split( '0123456789' );

	if ( $from == 'numbers' ) {
		return str_replace( $numbers, $alphabet, $text );
	} else {
		return str_replace( $alphabet, $numbers, $text );
	}
}

$offer_link = 'https://www.silkypress.com/wp-image-zoom-plugin/?a=' . iz_convert_numbers_letters( $zoooom_activation_time ) . '&utm_source=wordpress&utm_campaign=iz_offer&utm_medium=banner';


?>


<div id="right_column_metaboxes">

	<?php if ( $show_discount ) : ?>
	<div class="panel main_container">
	<div class="container_title">
	<h3><img src="<?php echo site_url(); ?>/wp-content/plugins/wp-image-zoooom/assets/images/icon.svg" /> <?php _e( 'WP Image Zoom Pro', 'wp-image-zoooom' ); ?></h3>
	</div>
		<div class="metabox-holder discount" style="text-align: center;"> 
				
		<p>Shhh... Can you keep a secret?</p>

		<p>
		<span style="color: #bc1117; font-size: 24px;">30% OFF</span><br />
		only between <span style="color: #bc1117;"><?php echo $start_date; ?> - <?php echo $end_date; ?></span>. 

		</p>
		<p>Don't tell anyone.</p>
		<p style="text-align: center;">
			<a href="<?php echo $offer_link; ?>" target="_blank" class="button" rel="noreferrer"><?php _e( 'Upgrade to PRO', 'wp-image-zoooom' ); ?></a>
		</p>
		</div> 
	</div>   
	<?php endif; ?>
	
	<div class="panel main_container">
	<div class="container_title">
		<h3><?php _e( 'Like this Plugin?', 'wp-image-zoooom' ); ?></h3>
	</div>
		<div class="metabox-holder rating" style="text-align: center;"> 
		<p><?php _e( 'Share your opinion with the world on the WordPress.org Plugin Repository.', 'wp-image-zoooom' ); ?></p>
		<p><a href="https://wordpress.org/plugins/wp-image-zoooom/" target="_blank" class="button"><?php _e( 'Rate it on WordPress.org', 'wp-image-zoooom' ); ?></a></p>
		</div> 
	</div>   
</div>

<div style="clear: both"></div>

