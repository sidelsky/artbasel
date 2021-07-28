<section class="wecp-main wrap">
    <div class="column-four ">
        <div class="back-icon-container"><a href="<?php echo $back_btn_url; ?>"><span class="dashicons dashicons-arrow-left-alt2"></span></a>
            <h2><?php _e('Choose Your Template', WECP_TEXT_DOMAIN); ?></h2></div>
        <?php
        if (!empty($templates)) {
            foreach ($templates as $template_name => $template_details) {
                ?>
                <div class="templates-list">
                    <h3><?php echo isset($template_details['name']) ? $template_details['name'] : ''; ?></h3>
                    <div class="card_template_box">
                        <div class="choose_template_order">
                            <img class="template-img"
                                 src="<?php echo isset($template_details['template_preview_image_url']) ? $template_details['template_preview_image_url'] : 'https://www.dropbox.com/s/3o650e17y3vg9p9/blanck-image.jpg?raw=1'; ?>"
                                 alt="default">
                            <div class="hvrbox-layer_top"></div>
                        </div>
                        <p>
                            <a class="btn btn-info"
                               href="<?php echo isset($template_details['template_create_url']) ? $template_details['template_create_url'] : ''; ?>&template=<?php echo $template_name ?>">
                                <?php echo __('Use', WECP_TEXT_DOMAIN); ?>
                            </a>
                        </p>
                    </div>
                </div>

                <?php
            }
        }
        ?>
    </div>
    <?php echo isset($extra) ? $extra : NULL ?>
</section>