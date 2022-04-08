<div id="customizer-container">
</div>
<div class="align-center" id="customizer-loader"><?php _e('Loading editor, Please Wait...') ?></div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#customizer-container").show();
        jQuery("#customizer-loader").hide();
        window.GrapejsEditor.init({
            container_id: 'customizer-container',
            user: {type: ''},
            template_id: <?php echo $template_id; ?>,
            test_email_extra_data: [{
                label: "<?php esc_html_e('Sample Data', WECP_TEXT_DOMAIN); ?>",
                selectable: <?php echo wp_json_encode($sample_data_orders) ?>
            }],
            title: "<?php echo 'Email Customizer Plus'; ?> (v<?php echo WECP_PLUGIN_VERSION ?>)- <?php echo $current_editing_template; ?>",
            loadUrl: "<?php echo admin_url('admin-ajax.php?action=wecp_get_template&template_id=' . $template->id . '&security=' . wp_create_nonce('wecp_get_template')); ?>",
            storeUrl: "<?php echo admin_url('admin-ajax.php?action=wecp_auto_save_edited_template&template_id=' . $template->id . '&security=' . wp_create_nonce('wecp_auto_save_edited_template')); ?>",
            shortCodesUri: "<?php echo $short_code_url; ?>",
            closeCallback: function () {
                window.location.href = "<?php echo $close_url; ?>";
            },
            images: {"order_summary": "<?php echo $order_summary_image_url; ?>"},
            preview_data_uri: '<?php echo admin_url('admin-ajax.php?action=wecp_get_sample_data&security=' . wp_create_nonce('wecp_get_sample_data')); ?>',
            handleTestMail: async function ({email, template_id, test_order_id}) {
                let response = {"success": false, "message": "Unknown Issue"};
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php?action=wecp_send_test_email&security=' . wp_create_nonce('wecp_send_test_email')); ?>",
                    data: {
                        "email": email,
                        "template_id": template_id,
                        "sample_order": test_order_id
                    },
                    success: function (server_response) {
                        response.success = server_response.success;
                        response.message = server_response.data;
                    },
                    dataType: "json",
                    async: false
                });
                return response;
            },
            handlePreview: async function ({template_id, test_order_id}) {
                let response = {success: false, html: "Unknown Issue"};
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php?action=wecp_get_template_preview&security=' . wp_create_nonce('wecp_get_template_preview')); ?>",
                    data: {
                        "template_id": template_id,
                        "sample_order": test_order_id
                    },
                    success: function (server_response) {
                        response.success = server_response.success;
                        response.html = server_response.data.html;
                    },
                    dataType: "json",
                    async: false
                });
                return response;
            }
        });
        jQuery("document").on("click", "#short-code-list td", function () {
            let short_code = $(this).parent("tr").data("copy");
            console.log(short_code);
        });
        jQuery(document).ready(function () {
            if (window.wpNotesCommon !== undefined) {
                window.wpNotesCommon.getKeycode = function (KeyEvent) {
                    return null;
                }
            }
        });
    });
</script>
