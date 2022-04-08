jQuery(document).ready(function ($) {
    function redirect(url) {
        window.location.href = url;
    }

    alertify.set('notifier', 'position', 'bottom-right');
    $(document).on('change', '#email_type', function () {
        var selected = $(this).val();
        var email_order = $('#email_order');
        if (selected === "choose") {
            email_order.attr('disabled', true);
        } else {
            email_order.attr('disabled', false);
        }
    });
    $(document).on('change', '#wecp-choose-language-to-copy,#wecp-choose-email-type-to-copy', function () {
        $(".wecp-after-copied-actions").hide();
    });
    $(document).on('click', '[name="show_product_image"]', function () {
        let image_data_container = $("#product-image-size-data");
        if (parseInt($(this).val()) === 1) {
            image_data_container.show();
        } else {
            image_data_container.hide();
        }
    });
    $(document).on('change', '[name="enable_retainful_integration"]', function () {
        let chosen_val = parseInt($(this).val());
        if (chosen_val === 1 && parseInt(wecp_admin_js_data.has_retainful_plugin) === 0) {
            alertify.error(wecp_admin_js_data.localize_must_install_retainful);
            $('input[name="enable_retainful_integration"][value="0"]').prop("checked", true);
        }
    });
    $(document).on('submit', '#wecp_settings_form', function (e) {
        e.preventDefault();
        let submit = $(this).find(':submit');
        let validate_btn = $("#btn-validate");
        submit.html(wecp_admin_js_data.localize_saving);
        validate_btn.html(wecp_admin_js_data.localize_validating);
        submit.attr('disabled', true);
        validate_btn.attr('disabled', true);
        doAjax($(this).serialize());
        submit.attr('disabled', false);
        validate_btn.attr('disabled', false);
        submit.html(wecp_admin_js_data.localize_save);
        validate_btn.html(wecp_admin_js_data.localize_validate);
    });
    $(document).on('submit', '#wecp-copy-template-form', function (e) {
        e.preventDefault();
        let submit = $(this).find(':submit');
        submit.html(wecp_admin_js_data.localize_copying);
        submit.attr('disabled', true);
        doAjax($(this).serialize());
        submit.attr('disabled', false);
        submit.html(wecp_admin_js_data.localize_copy);
    });
    $(document).on('submit', '#validate_license_key_form_modal', function (e) {
        e.preventDefault();
        let validate_btn = $(this).find(':submit');
        validate_btn.html(wecp_admin_js_data.localize_validating);
        validate_btn.attr('disabled', true);
        let res = doAjax($(this).serialize());
        if (res.success) {
            window.location.reload();
        }
        validate_btn.attr('disabled', false);
        validate_btn.html(wecp_admin_js_data.localize_validate);
    });
    $(document).on('click', '#btn-validate', function (e) {
        $("#wecp_settings_form").submit();
    });
    $(document).on('click', '.delete-email-template', function (e) {
        if (confirm(wecp_admin_js_data.localize_sure_want_delete)) {
            window.location.href = $(this).data("delete_url");
        }
    });
    $(document).on('change', '.activate-or-deactivate-template', function () {
        var is_active = 0;
        var id = $(this).data('template');
        if ($(this).prop("checked") === true) {
            is_active = 1;
        }
        doAjax({
            "action": "wecp_activate_or_deactivate_template",
            "is_ajax": true,
            "id": id,
            "change_status_to": is_active,
            "security": wecp_localize_data.nonce.wecp_activate_or_deactivate_template
        });
    });
    $(document).on('click', '.export-email-template', function () {
        var id = $(this).data('template');
        doAjax({
            "action": "wecp_export_email_template",
            "id": id,
            "security": wecp_localize_data.nonce.wecp_export_email_template
        });
    });
    $(document).on('click', '.export-all-email-templates', function () {
        doAjax({
            "action": "wecp_export_all_email_templates",
            "security": wecp_localize_data.nonce.wecp_export_all_email_templates
        });
    });
    $(document).on('click', '.copy-email-template', function () {
        var id = $(this).data('template');
        $("#wecp-copy-from-template-id").val(id);
        $("#wecp-customize-after-copy").hide();
    });
    $(document).on('click', '#wecp-reload-after-copy', function () {
        window.location.reload();
    });

    function doAjax(data) {
        let res = {};
        res.success = false;
        $.ajax({
            data: data,
            type: 'post',
            url: wecp_localize_data.ajax_url,
            error: function (request, error) {
                alertify.error(error);
            },
            async: false,
            success: function (data) {
                let license_key_message_conteiner = $(".license_key_message");
                if (data.license_key) {
                    if (data.license_key_message) {
                        license_key_message_conteiner.css("color", "green");
                        license_key_message_conteiner.html(data.license_key_message);
                        res.success = true;
                    }
                }
                if (!data.license_key) {
                    if (data.license_key_message) {
                        license_key_message_conteiner.css("color", "red");
                        license_key_message_conteiner.html(data.license_key_message);
                        res.success = false;
                    }
                }
                if (data.after_copy_customize_url) {
                    let customize_link = $("#wecp-customize-after-copy");
                    customize_link.show();
                    $("#wecp-reload-after-copy").show();
                    customize_link.attr("href", data.after_copy_customize_url);
                }
                if (!data.error) {
                    if (data.message) {
                        alertify.success(data.message);
                    }
                } else {
                    if (data.message) {
                        alertify.error(data.message);
                    }
                }
                if (data.redirect) {
                    redirect(data.redirect);
                }
                if (data.download) {
                    let name = (typeof data.download_name !== "undefined") ? data.download_name : '';
                    let url = (typeof data.download_url !== "undefined") ? data.download_url : '';
                    if (name !== "" && url !== "") {
                        saveToDisk(url, name);
                    }
                }
            }
        });
        return res;
    }

    function saveToDisk(fileURL, fileName) {
        // for non-IE
        if (!window.ActiveXObject) {
            var save = document.createElement('a');
            save.href = fileURL;
            save.target = '_blank';
            save.download = fileName || 'unknown';
            try {
                var evt = new MouseEvent('click', {
                    'view': window,
                    'bubbles': true,
                    'cancelable': false
                });
                save.dispatchEvent(evt);
                (window.URL || window.webkitURL).revokeObjectURL(save.href);
            } catch (e) {
                window.open(fileURL, fileName);
            }
        }

        // for IE < 11
        else if (!!window.ActiveXObject && document.execCommand) {
            var _window = window.open(fileURL, '_blank');
            _window.document.close();
            _window.document.execCommand('SaveAs', true, fileName || fileURL)
            _window.close();
        }
    }

    $(document).on("click", "#short_codes_view_order_meta_list_btn", function () {
        var sample_order_id = $("#short_codes_list_sample_orders").val();
        var data = {
            "action": "wecp_get_order_meta_shortcode_list",
            "order_id": sample_order_id,
            "security": wecp_localize_data.nonce.wecp_get_order_meta_shortcode_list
        };
        var response = {};
        $(this).attr('disabled', true);
        $(this).text('Please wait...');
        var html_holder = $(document).find("#short_codes_order_meta_details");
        html_holder.html('');
        $.ajax({
            data: data,
            type: 'post',
            url: wecp_localize_data.ajax_url,
            error: function (request, error) {
                alertify.error(error);
                $(this).attr('disabled', false);
            },
            async: false,
            success: function (data) {
                response = data;
                $(this).attr('disabled', false);
            }
        });
        if (response.success) {
            var meta_keys = Object.entries(response.data);
            for (const meta of meta_keys) {
                var row = "<tr><td style='padding: 5px 0;'>" + meta[0] + "</td><td class='wecp-wrapword'>&nbsp;-&nbsp;" + meta[1] + "</td></tr>";
                html_holder.append(row);
            }
        }
        $(this).attr('disabled', false);
        $(this).text('View order meta');
    });
});
