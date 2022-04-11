<form class="general-settings-form" id="wecp_settings_form" method="post">
    <div>
        <div class="main-row">
            <?php
            $hide_license_field = apply_filters("woocommerce_email_customizer_plus_hide_license_field", false);
            ?>
            <div class="form-row" style="<?php if ($hide_license_field) {
                echo 'display:none;';
            } ?>">
                <label for="license_key"><?php _e('License key', WECP_TEXT_DOMAIN); ?></label>
                <?php
                $license_key = isset($options['license_key']) ? $options['license_key'] : NULL;
                ?>
                <div class="form-wecp-input-group">
                    <input type="text" id="license_key" name="license_key" value="<?php echo $license_key ?>"
                           placeholder="<?php _e('Enter license key here...', WECP_TEXT_DOMAIN); ?>">
                    <button type="button" class="btn btn-validate"
                            id="btn-validate"><?php _e('Validate', WECP_TEXT_DOMAIN) ?></button>
                </div>
                <?php
                if (!empty($license_key)) {
                    ?>
                    <p class="license_key_message"
                       style="margin: 10px 0 0 0;color:<?php echo ($license_key_error) ? 'red' : 'green' ?>;">
                        <?php echo $license_key_message; ?>
                    </p>
                    <?php
                } else {
                    ?>
                    <p class="license_key_message" style="margin: 10px 0 0 0;"></p>
                    <?php
                }
                ?>
            </div>
            <div class="form-row">
                <label class="settings-label" for="wecp_custom_css">
                    <?php esc_html_e('Custom CSS', WECP_TEXT_DOMAIN); ?>
                </label>
                <?php
                $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';
                ?>
                <textarea id="wecp_custom_css"
                          name="custom_css"
                          placeholder="<?php _e('Put your custom Css here...', WECP_TEXT_DOMAIN); ?>"><?php echo $custom_css; ?></textarea>
            </div>
            <div class="form-row">
                <label class="settings-label" for="show_sku">
                    <?php esc_html_e('Show product SKU ', WECP_TEXT_DOMAIN); ?>
                </label>
                <?php
                $show_sku = isset($options['show_sku']) ? $options['show_sku'] : 1;
                ?>
                <label> &nbsp;<input type="radio"
                                     name="show_sku" <?php echo ($show_sku == 1) ? "checked" : "" ?>
                                     value="1"><?php esc_html_e('Yes', WECP_TEXT_DOMAIN); ?></label>
                <label> &nbsp;<input type="radio"
                                     name="show_sku" <?php echo ($show_sku == 0) ? "checked" : "" ?>
                                     value="0"><?php esc_html_e('No', WECP_TEXT_DOMAIN); ?></label>
            </div>
            <div class="form-row">
                <label class="settings-label" for="product_image_size">
                    <?php esc_html_e('Show payment instruction ', WECP_TEXT_DOMAIN); ?>
                </label>
                <?php
                $show_payment_instruction = isset($options['show_payment_instruction']) ? $options['show_payment_instruction'] : "1";
                ?>
                <div class="form-wecp-input-group">
                    <select name="show_payment_instruction" id="show_payment_instruction">
                        <option value="1" <?php echo ($show_payment_instruction == '1') ? 'selected' : ''; ?>><?php esc_html_e("Yes", WECP_TEXT_DOMAIN); ?></option>
                        <option value="0" <?php echo ($show_payment_instruction == '0') ? 'selected' : ''; ?>><?php esc_html_e("No", WECP_TEXT_DOMAIN); ?></option>
                        <option value="0" <?php echo ($show_payment_instruction == '2') ? 'selected' : ''; ?>><?php esc_html_e("Only to customers", WECP_TEXT_DOMAIN); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label class="settings-label" for="autofix_broken_html">
                    <?php esc_html_e('Auto fix broken HTML ', WECP_TEXT_DOMAIN); ?>
                </label>
                <?php
                $autofix_broken_html = isset($options['autofix_broken_html']) ? $options['autofix_broken_html'] : 1;
                ?>
                <label> &nbsp;<input type="radio"
                                     name="autofix_broken_html" <?php echo ($autofix_broken_html == 1) ? "checked" : "" ?>
                                     value="1"><?php esc_html_e('Yes', WECP_TEXT_DOMAIN); ?></label>
                <label> &nbsp;<input type="radio"
                                     name="autofix_broken_html" <?php echo ($autofix_broken_html == 0) ? "checked" : "" ?>
                                     value="0"><?php esc_html_e('No', WECP_TEXT_DOMAIN); ?></label>
            </div>
            <div class="form-row">
                <label class="settings-label" for="show_sku">
                    <?php esc_html_e('Enable Retainful Integration ', WECP_TEXT_DOMAIN); ?>
                </label>
                <?php
                $enable_retainful_integration = isset($options['enable_retainful_integration']) ? $options['enable_retainful_integration'] : 0;
                ?>
                <label> &nbsp;<input type="radio"
                                     name="enable_retainful_integration" <?php echo ($enable_retainful_integration == 1) ? "checked" : "" ?>
                                     value="1"><?php esc_html_e('Yes', WECP_TEXT_DOMAIN); ?></label>
                <label> &nbsp;<input type="radio"
                                     name="enable_retainful_integration" <?php echo ($enable_retainful_integration == 0) ? "checked" : "" ?>
                                     value="0"><?php esc_html_e('No', WECP_TEXT_DOMAIN); ?></label>
            </div>
        </div>
        <div class="main-row">
            <div class="form-row">
                <label class="settings-label" for="show_product_image">
                    <?php esc_html_e('Show product image ', WECP_TEXT_DOMAIN); ?>
                </label>
                <?php
                $show_product_image = isset($options['show_product_image']) ? $options['show_product_image'] : 1;
                ?>
                <label> &nbsp;<input type="radio"
                                     name="show_product_image" <?php echo ($show_product_image == 1) ? "checked" : "" ?>
                                     value="1"><?php esc_html_e('Yes', WECP_TEXT_DOMAIN); ?></label>
                <label> &nbsp;<input type="radio"
                                     name="show_product_image" <?php echo ($show_product_image == 0) ? "checked" : "" ?>
                                     value="0"><?php esc_html_e('No', WECP_TEXT_DOMAIN); ?></label>
            </div>
            <div id="product-image-size-data"
                 style="display: <?php echo ($show_product_image == 1) ? 'block' : 'none' ?>">
                <div class="form-row">
                    <label class="settings-label" for="product_image_size">
                        <?php esc_html_e('Image size ', WECP_TEXT_DOMAIN); ?>
                    </label>
                    <?php
                    $product_image_size = isset($options['product_image_size']) ? $options['product_image_size'] : "thumbnail";
                    ?>
                    <div class="form-wecp-input-group">
                        <select name="product_image_size" id="product_image_size">
                            <option value="thumbnail" <?php echo ($product_image_size == 'thumbnail') ? 'selected' : ''; ?>><?php esc_html_e("Thumbnail", WECP_TEXT_DOMAIN); ?></option>
                            <option value="full" <?php echo ($product_image_size == 'full') ? 'selected' : ''; ?>><?php esc_html_e("Full", WECP_TEXT_DOMAIN); ?></option>
                        </select>
                    </div>
                </div>
                <div class="wecp-input-group form-row">
                    <?php
                    $product_image_height = isset($options['product_image_height']) ? $options['product_image_height'] : '32';
                    ?>
                    <label class="settings-label" for="product_image_height">
                        <?php esc_html_e('Image height ', WECP_TEXT_DOMAIN); ?>
                    </label>
                    <div class="wecp-input-group-prepend">
                        <input placeholder="eg: 32" name="product_image_height" id="product_image_height" type="text"
                               class="form-control form-control-right-bdr" value="<?php echo $product_image_height; ?>">
                        <span class="wecp-input-group-text wecp-input-right-group"><i>px</i></span>
                    </div>
                    <?php
                    $product_image_width = isset($options['product_image_width']) ? $options['product_image_width'] : '32';
                    ?>
                    <label class="settings-label" for="product_image_width">
                        <?php esc_html_e('Image width ', WECP_TEXT_DOMAIN); ?>
                    </label>
                    <div class="wecp-input-group-prepend">
                        <input placeholder="eg: 32" id="product_image_width" name="product_image_width" type="text"
                               class="form-control form-control-right-bdr" value="<?php echo $product_image_width; ?>">
                        <span class="wecp-input-group-text wecp-input-right-group"><i>px</i></span>
                    </div>
                </div>
            </div>
            <div class="mb-3 wecp-input-group form-row">
                <?php
                $facebook = isset($options['facebook']) ? $options['facebook'] : '';
                ?>
                <div class="wecp-input-group-prepend"><span class="wecp-input-group-text"><i
                                class="dashicons dashicons-facebook-alt"></i></span>
                    <input placeholder="eg: www.facebook.com" autocomplete="facebook" name="facebook" type="text"
                           class="form-control form-control-bdr"
                           value="<?php echo $facebook; ?>">
                </div>
            </div>
            <div class="mb-3 wecp-input-group form-row">
                <?php
                $twitter = isset($options['twitter']) ? $options['twitter'] : '';
                ?>
                <div class="wecp-input-group-prepend"><span class="wecp-input-group-text"><i
                                class="dashicons dashicons-twitter"></i></span>
                    <input placeholder="eg: www.twitter.com" autocomplete="twitter" name="twitter" type="text"
                           class="form-control form-control-bdr"
                           value="<?php echo $twitter; ?>">
                </div>
            </div>
            <div class="mb-3 wecp-input-group form-row">
                <?php
                $instagram = isset($options['instagram']) ? $options['instagram'] : '';
                ?>
                <div class="wecp-input-group-prepend"><span class="wecp-input-group-text"><i
                                class="dashicons dashicons-instagram"></i></span>
                    <input placeholder="eg: www.instagram.com" autocomplete="instagram" name="instagram" type="text"
                           class="form-control form-control-bdr" value="<?php echo $instagram; ?>">
                </div>
            </div>

            <div class="mb-3 wecp-input-group form-row">
                <?php
                $linkedin = isset($options['linkedin']) ? $options['linkedin'] : '';
                ?>
                <div class="wecp-input-group-prepend"><span class="wecp-input-group-text"><i
                                class="fa fa-linkedin"></i></span>
                    <input placeholder="eg: www.linkedin.com" autocomplete="linkedin" name="linkedin" type="text"
                           class="form-control form-control-bdr" value="<?php echo $linkedin; ?>">
                </div>
            </div>
            <div class="mb-3 wecp-input-group form-row">
                <?php
                $pinterest = isset($options['pinterest']) ? $options['pinterest'] : '';
                ?>
                <div class="wecp-input-group-prepend"><span class="wecp-input-group-text"><i
                                class="fa fa-pinterest"></i></span>
                    <input placeholder="eg: www.pinterest.com" autocomplete="pinterest" name="pinterest" type="text"
                           class="form-control form-control-bdr" value="<?php echo $pinterest; ?>">
                </div>
            </div>
        </div>
    </div>
    <?php
    if (!empty($available_languages)) {
        ?>
        <div>
            <h6>
                <?php esc_html_e('Direction Settings ', WECP_TEXT_DOMAIN); ?>
            </h6>
            <?php
            foreach ($available_languages as $language) {
                ?>
                <div class="form-row">
                    <label class="settings-label" for="show_product_image">
                        <?php echo $woocommerce_helper->getLanguageLabel($language) ?> :&nbsp;
                    </label>
                    <?php
                    $option_name = 'language_direction_for_' . $language;
                    $language_direction = isset($options[$option_name]) ? $options[$option_name] : 'ltr';
                    ?>
                    <label> &nbsp;<input type="radio"
                                         name="<?php echo $option_name; ?>" <?php echo ($language_direction == 'ltr') ? "checked" : "" ?>
                                         value="ltr"><?php esc_html_e('Left to Right', WECP_TEXT_DOMAIN); ?></label>
                    <label> &nbsp;<input type="radio"
                                         name="<?php echo $option_name; ?>" <?php echo ($language_direction == 'rtl') ? "checked" : "" ?>
                                         value="rtl"><?php esc_html_e('Right to Left', WECP_TEXT_DOMAIN); ?></label>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
    <div>
        <div>&nbsp;</div>
        <div class="form-row">
            <input type="hidden" name="action" value="wecp_save_settings">
            <input type="hidden" name="security" value="<?php echo wp_create_nonce('wecp_save_settings'); ?>">
            <input type="hidden" name="option_key" value="<?php echo !empty($save_key) ? $save_key : '' ?>">
            <button type="submit" class="btn btn-save" onclick="">
                <?php _e('Save', WECP_TEXT_DOMAIN); ?>
            </button>
        </div>
        <div class="form-row">
            <label class="settings-label" for="">
                <?php esc_html_e('Change email subject and from name', WECP_TEXT_DOMAIN); ?>
                <a target="_blank" href="<?php echo admin_url('admin.php?page=wc-settings&tab=email'); ?>"><?php _e('Click
                    here', WECP_TEXT_DOMAIN); ?></a>
            </label>
        </div>
    </div>
</form>