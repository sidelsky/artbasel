<?php
/**
 * Zoom settings page
 */

defined( 'ABSPATH' ) || exit;

$brand = vsprintf(
	'<img src="%s" /> <a href="%s">%s</a>',
	array(
		IMAGE_ZOOM_URL . 'assets/images/silkypress_logo.png',
		'https://www.silkypress.com/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner',
		'SilkyPress.com',
	)
);

if ( ! function_exists( 'iz_load_step' ) ) {
	function iz_load_steps( $step, $description ) {
		return sprintf( '<div class="steps"><span class="steps_nr">%s</span><span class="steps_desc">%s</span></div>', $step, $description );
	}
}


?>
<h2><?php printf( esc_html__( 'WP Image Zoom by %1$s', 'wp-image-zoooom' ), $brand ); ?></h2>

<div class="wrap">
	<h3 class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a href="?page=zoooom_settings&tab=general" class="nav-tab"><?php _e( 'General Settings', 'wp-image-zoooom' ); ?></a>
		<a href="?page=zoooom_settings&tab=settings" class="nav-tab nav-tab-active"><?php _e( 'Zoom Settings', 'wp-image-zoooom' ); ?></a>
	</h3>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div id="alert_messages">
					<?php echo $messages; ?>
				</div>
		
				<form class="form-horizontal" method="post" action="" id="form_settings">
					<div class="form-group">
						<?php echo iz_load_steps( __( 'Step 1', 'wp-image-zoooom' ), __( 'Choose the Lens Shape', 'wp-image-zoooom' ) ); ?>

						<?php echo $form->render_field( 'lensShape', $settings_all['lensShape'] ); ?>

						<div style="clear: both; margin-bottom: 50px;"></div>


						<?php echo iz_load_steps( __( 'Step 2', 'wp-image-zoooom' ), __( 'Check your configuration changes on the image', 'wp-image-zoooom' ) ); ?>

						<img id="demo" src="<?php echo IMAGE_ZOOM_URL; ?>/assets/images/img1_medium.png" data-zoom-image="<?php echo IMAGE_ZOOM_URL; ?>/assets/images/img1_large.png" width="300" />

						<div style="clear: both; margin-bottom: 50px;"></div>


						<?php echo iz_load_steps( __( 'Step 3', 'wp-image-zoooom' ), __( 'Make more fine-grained configurations on the zoom', 'wp-image-zoooom' ) ); ?>

						<ul class="nav nav-tabs">
							<li class="" id="tab_padding" style="width: 40px;"> &nbsp; </li>
							<li class="active" id="tab_general">
								<a href="#general_settings" data-toggle="tab" aria-expanded="true"><?php _e( 'General', 'wp-image-zoooom' ); ?></a>
							</li>
							<li class="" id="tab_lens">
								<a href="#lens_settings" data-toggle="tab" aria-expanded="false"><?php _e( 'Lens', 'wp-image-zoooom' ); ?></a>
							</li>
							<li class="" id="tab_zoom_window">
								<a href="#zoom_window_settings" data-toggle="tab" aria-expanded="false"><?php _e( 'Zoom Window', 'wp-image-zoooom' ); ?></a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane fade active in" id="general_settings">
							<?php
							foreach ( array( 'cursorType', 'zwEasing', 'onClick', 'ratio' ) as $_field ) {
								echo $form->render_field( $_field, $settings_all[ $_field ] );
							}
							?>
							</div>
							<div class="tab-pane fade" id="lens_settings">
							<?php
							$fields = array( 'lensSize', 'lensColour', 'lensOverlay', 'borderThickness', 'borderColor', 'lensFade', 'tint', 'tintColor', 'tintOpacity' );
							foreach ( $fields as $_field ) {
								echo $form->render_field( $_field, $settings_all[ $_field ] );
							}
							?>
							</div>

							<div class="tab-pane fade" id="zoom_window_settings">
							<?php
							$fields = array( 'zwWidth', 'zwHeight', 'zwResponsive', 'zwResponsiveThreshold', 'zwPositioning', 'zwPadding', 'zwBorderThickness', 'zwBorderColor', 'zwShadow', 'zwBorderRadius', 'mousewheelZoom', 'zwFade' );

							foreach ( $fields as $_field ) {
								echo $form->render_field( $_field, $settings_all[ $_field ] );
							}
							?>
							</div>
						</div>

						<?php echo iz_load_steps( __( 'Step 4', 'wp-image-zoooom' ), __( 'Don\'t forget to save the changes in order to apply them on the website', 'wp-image-zoooom' ) ); ?>

						<div class="form-group">
							<div class="col-lg-6">
								<button type="submit" class="btn btn-primary"><?php _e( 'Save changes', 'wp-image-zoooom' ); ?></button>
							</div>
						</div>
					</div>
					<?php wp_nonce_field( 'zoooom_settings' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>

