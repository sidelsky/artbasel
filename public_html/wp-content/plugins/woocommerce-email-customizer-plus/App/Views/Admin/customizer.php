<nav class="nav-tab-wrapper">
    <?php
    if (is_array($available_languages)) {
        //include default language to available languages
        $available_languages = array_merge(array($default_language), $available_languages);
    }
    if (!empty($available_languages)) {
        $available_languages = array_unique($available_languages);
        foreach ($available_languages as $language) {
            ?>
            <a class="nav-tab <?php echo ($chosen_language == $language) ? 'nav-tab-active' : ''; ?>"
               href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'customize', 'lang' => $language))) ?>">
                <?php echo $woocommerce->getLanguageLabel($language) ?>
            </a>
            <?php
        }
    }
    ?>
    <a class="btn-flt btn_import_tp export-all-email-templates"
       href="javascript:void(0);"><?php echo __('Export All', WECP_TEXT_DOMAIN); ?></a>
</nav>
<section class="email_type_list_table">
    <table>
        <tbody>
        <tr class="mr_space">
            <td><?php _e('Status', WECP_TEXT_DOMAIN); ?></td>
            <td style="color: black;font-size: 15px;font-weight: normal;"><?php _e('Email', WECP_TEXT_DOMAIN); ?></td>
            <td><?php _e('Action', WECP_TEXT_DOMAIN); ?></td>
        </tr>
        <?php
        if (!empty($email_type_lists)) {
            foreach ($email_type_lists as $key => $email_type) {
                /*if (!in_array($key, $supported_templates)) {
                    continue;
                }*/
                ?>
                <tr class="mr_space">
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="is_active" class="activate-or-deactivate-template"
                                   data-template="<?php echo isset($customized_templates[$email_type->id]['id']) ? $customized_templates[$email_type->id]['id'] : 0; ?>"
                                <?php
                                if (!isset($customized_templates[$email_type->id])) {
                                    echo 'disabled';
                                }
                                if (isset($customized_templates[$email_type->id]['is_active']) && !empty($customized_templates[$email_type->id]['is_active'])) {
                                    echo 'checked';
                                }
                                ?>>
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <?php
                        if (isset($customized_templates[$email_type->id])) {
                            ?>
                            <a href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'editor', 'lang' => $chosen_language, 'type' => $email_type->id, 'id' => $customized_templates[$email_type->id]['id']))); ?>"><?php echo ucwords($email_type->title); ?></a>
                            <?php
                        } else {
                            ?>
                            <a href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'choose_template', 'lang' => $chosen_language, 'type' => $email_type->id))); ?>"><?php echo ucwords($email_type->title); ?></a>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($customized_templates[$email_type->id])) {
                            ?>
                            <a class="btn btn_customizer"
                               href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'editor', 'lang' => $chosen_language, 'type' => $email_type->id, 'id' => $customized_templates[$email_type->id]['id']))); ?>"><span
                                        class="dashicons dashicons-edit wecp-mn-icons"></span></a>
                            <a class="btn btn_delete delete-email-template"
                               data-delete_url="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'reset_template', 'lang' => $chosen_language, 'type' => $email_type->id, 'id' => $customized_templates[$email_type->id]['id']))); ?>"><span
                                        class="dashicons dashicons-trash wecp-mn-icons"></span></a>
                            <a class="btn btn_import export-email-template"
                               data-template="<?php echo $customized_templates[$email_type->id]['id']; ?>"
                               href="javascript:void(0);"><?php esc_html_e('Export', WECP_TEXT_DOMAIN); ?></a>
                            <a class="btn btn_import copy-email-template"
                               data-template="<?php echo $customized_templates[$email_type->id]['id']; ?>"
                               href="#wecp-copy-template"
                               rel="modal:open"><?php esc_html_e('Copy', WECP_TEXT_DOMAIN); ?></a>
                            <?php
                        } else {
                            ?>
                            <a class="btn btn_customizer"
                               href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'choose_template', 'lang' => $chosen_language, 'type' => $email_type->id))); ?>"><span
                                        class="dashicons dashicons-edit wecp-mn-icons"></span></a>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>

    <div id="wecp-copy-template" class="flycart-plugin-modal">
        <form id="wecp-copy-template-form">
            <input type="hidden" id="wecp-copy-from-template-id" name="copy_from">
            <input type="hidden" name="action" value="wecp_copy_template_to">
            <input type="hidden" name="security" value="<?php echo wp_create_nonce('wecp_copy_template_to') ?>">
            <?php
            if (!empty($available_languages)) {
                ?>
                <div class="form-row" style="">
                    <label for="wecp-choose-language-to-copy"><?php esc_html_e('Copy to Language', WECP_TEXT_DOMAIN); ?></label>
                    <div class="form-wecp-input-group">
                        <select id="wecp-choose-language-to-copy" name="copy_to_language">
                            <?php
                            $available_languages = array_unique($available_languages);
                            foreach ($available_languages as $language) {
                                ?>
                                <option value="<?php echo $language; ?>"><?php echo $woocommerce->getLanguageLabel($language) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php
            }
            if (!empty($email_type_lists)) {
                ?>
                <div class="form-row" style="">
                    <label for="wecp-choose-email-type-to-copy"><?php esc_html_e('Copy to Email Type', WECP_TEXT_DOMAIN); ?></label>
                    <div class="form-wecp-input-group">
                        <select id="wecp-choose-email-type-to-copy" name="copy_to_email_type">
                            <?php
                            foreach ($email_type_lists as $email_type) {
                                ?>
                                <option value="<?php echo $email_type->id; ?>"><?php echo ucwords($email_type->title); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php
            }
            ?>
            <br>
            <div>
                <p>
                    <strong><?php esc_html_e('Note:', WECP_TEXT_DOMAIN); ?></strong><?php esc_html_e('Copying template will overwrite the destination template with the chosen template.'); ?>
                </p>
            </div>
            <div class="form-row">
                <button type="submit" class="btn btn-blue"><?php esc_html_e('Copy', WECP_TEXT_DOMAIN); ?></button>
                <a href="" class="wecp-after-copied-actions btn btn-green" id="wecp-customize-after-copy"
                   style="display: none"><?php esc_html_e('Go to copied template'); ?></a>
            </div>
        </form>
    </div>
</section>