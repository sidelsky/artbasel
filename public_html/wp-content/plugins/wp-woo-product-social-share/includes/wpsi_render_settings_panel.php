<?php

/**
 * Add Plugin Settings Fields Form
 * 
 * @package Social Share For WooCommerce
 * @since 1.0.0
 */
function wpsi_render_social_sharing() {

    $icon = "url( " . plugins_url("images/icon/icon.png", WPSI_PLUGIN_URL) . " )";
    ?>

    <div class="wrap wpsi_social_share_buttons">
        <h2 class="wpsi_social_background_css" style="background-image: <?php echo esc_attr($icon); ?>;">
    <?php echo esc_html__('Social Share For WooCommerce', 'wpsisocialshare'); ?>
        </h2>

        <form action="options.php" method="post"><?php
            settings_fields('wpsi_settings_group');

            do_settings_sections('wpsi_settings_section');

            submit_button(esc_html__('Save Changes','wpsisocialshare'));
            ?>

        </form>
    </div>

<?php
}
