<section class="wecp-main wrap">
    <div class="sec-title centered">
        <h1><?php esc_html_e('Email Customizer Plus', WECP_TEXT_DOMAIN); ?>&nbsp;<small>v<?php echo WECP_PLUGIN_VERSION ?></small></h1>
    </div>
    <nav class="nav-tab-wrapper">
        <a class="nav-tab <?php echo ($current_view == "customize") ? 'nav-tab-active' : ''; ?>"
           href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'customize'))) ?>"><?php _e('Customizer', WECP_TEXT_DOMAIN) ?>
        </a>
        <a class="nav-tab <?php echo ($current_view == "settings") ? 'nav-tab-active' : ''; ?>"
           href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'settings'))) ?>"><?php _e('Settings', WECP_TEXT_DOMAIN) ?>
        </a>
    </nav>
    <article>
        <?php echo isset($extra) ? $extra : NULL ?>
        <?php echo isset($tab_content) ? $tab_content : NULL ?>
    </article>
    <div class="right-sidebar">
        <?php echo $side_bar; ?>
    </div>
</section>
