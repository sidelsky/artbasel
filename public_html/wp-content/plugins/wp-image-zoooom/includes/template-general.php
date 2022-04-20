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

?>
<style type="text/css">
	.form-group { display:flex; align-items: center; }
	.control-label{ height: auto; }
</style>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>

<?php add_thickbox(); ?>
<div id="supported-lightboxes" style="display:none;">
	<p>The zoom is compatible with:
		<ul style="list-style: inside; padding-left: 20px;">
			<li>the lightbox created by the <a href="https://www.silkypress.com/i/wp-huge-it-gallery" target="_blank" rel="nofollow">Huge IT Gallery</a> plugin</li>
			<li>the lightbox created by the <a href="https://www.silkypress.com/i/wp-photo-gallery" target="_blank" rel="nofollow">Photo Gallery</a> plugin</li>
			<li>the iLightbox from the <a href="https://www.silkypress.com/i/avada-theme" target="_blank" rel="nofollow">Avada Theme</a></li>
			<li>the lightbox created by <a href="https://www.silkypress.com/i/jetpack-carousel" target="_blank" rel="nofollow">Carousel</a> from Jetpack</li>
			<li>the <a href="https://www.silkypress.com/i/js-prettyphoto" target="_blank" rel="nofollow">prettyPhoto</a> lightbox (also used by the <a href="https://www.silkypress.com/i/visual-composer" target="_blank" rel="nofollow">WPBakery</a> gallery)</li>
			<li>the <a href="https://www.silkypress.com/i/js-fancybox" target="_blank" rel="nofollow">fancyBox</a> lightbox (also used by the <a href="https://wordpress.org/plugins/easy-fancybox/" target="_blank" rel="nofollow">Easy Fancybox</a> or the <a href="https://wordpress.org/plugins/woocommerce-lightbox/" target="_blank" rel="nofollow">WooCommerce LightBox</a> plugin)</li>
			<li>the <a href="https://www.silkypress.com/i/js-featherlight" target="_blank" rel="nofollow">Featherlight.js</a> lightbox (also used by <a href="https://www.silkypress.com/i/wp-draw-attention" target="_blank" rel="nofollow">Draw Attention</a> plugin)</li>
			<li>the lightbox created by the Ultimate Product Catalogue by Etoile Web Design</li>
			<li>the <a href="http://dimsemenov.com/plugins/magnific-popup/" target="_blank" rel="nofollow">Magnific Popup</a> lightbox (also used by <a href="https://www.silkypress.com/i/enfold-theme" target="_blank" rel="nofollow">Enfold</a> portfolio items, the Divi gallery or the <a href="https://wordpress.org/plugins/beaver-builder-lite-version/" target="_blank">Beaver Builder</a>)</li>
			<li>the lightbox from the <a href="https://wordpress.org/plugins/elementor/" target="_blank" rel="nofollow">Elementor</a> Page Builder</li>
			<li>the lightbox from the <a href="https://lcweb.it/media-grid/bundle-pack" target="_blank" rel="nofollow">Media Grid - Bundle Pack</a></li>
		</ul>
	</p>
</div>

<h2><?php printf( esc_html__( 'WP Image Zoom by %1$s', 'wp-image-zoooom' ), $brand ); ?></h2>

<div class="wrap">
	<h3 class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a href="?page=zoooom_settings&tab=general" class="nav-tab nav-tab-active"><?php _e( 'General Settings', 'wp-image-zoooom' ); ?></a>
		<a href="?page=zoooom_settings&tab=settings" class="nav-tab"><?php _e( 'Zoom Settings', 'wp-image-zoooom' ); ?></a>
	</h3>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div id="alert_messages">
				<?php echo $messages; ?>
				</div>

				<form class="form-horizontal" method="post" action="" id="form_settings">
					<?php echo $form->render(); ?>

					<div class="form-group">
						<div class="col-lg-6">
							<input type="hidden" name="tab" value="general" />
							<button type="submit" class="btn btn-primary"><?php _e( 'Save changes', 'wp-image-zoooom' ); ?></button>
						</div>
					</div>
					<?php wp_nonce_field( 'zoooom_general' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>
