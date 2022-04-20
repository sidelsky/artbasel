<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://1fix.io
 * @since      0.4.0
 *
 * @package    Category_Metabox_Enhanced
 * @subpackage Category_Metabox_Enhanced/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php // settings_errors(); ?>

	<?php
		$taxes = of_cme_supported_taxonomies();

		if ( isset( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		} else {
			$active_tab = ( isset( $taxes[0] ) ) ? $taxes[0] : '';
		}
	?>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $taxes as $tax ) { ?>
		<a href="?page=category-metabox-enhanced&amp;tab=<?php echo $tax; ?>" class="nav-tab <?php echo ( $tax == $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php $taxonomy_object = get_taxonomy( $tax ); echo $taxonomy_object->labels->name; ?></a>
		<?php } ?>
	</h2>

	<form method="post" action="options.php">
		<?php
			$section = 'category-metabox-enhanced_' . $active_tab;

			settings_fields( $section );
			do_settings_sections( $section );

			submit_button();
		?>
	</form>

</div><!-- /.wrap -->
