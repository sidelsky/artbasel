<?php

namespace Wecp\App\Controllers\Admin;

use Wecp\App\Controllers\Base;
use Wecp\App\Controllers\Compatibles\Retainful;
use Wecp\App\Controllers\ShortCodes;
use Wecp\App\Models\EmailCustomizerPlusTemplatesModel as templateModel;

class Main extends Base
{
    /**
     * Initialize admin menu
     */
    function AddMenu()
    {
        global $submenu;
        if (isset($submenu['woocommerce'])) {
            add_submenu_page(
                'woocommerce',
                __('Email Customizer Plus', WECP_TEXT_DOMAIN),
                __('Email Customizer Plus', WECP_TEXT_DOMAIN),
                'manage_woocommerce',
                WECP_PLUGIN_SLUG,
                array($this, 'managePages')
            );
        }
    }

    /**
     * Export all email templates
     */
    function exportAllEmailTemplates()
    {
        $response = array(
            'error' => true,
        );
        if (check_ajax_referer('wecp_export_all_email_templates', 'security') && current_user_can('manage_woocommerce')) {
            try {
                $templates_model = new templateModel();
                $templates = $templates_model->getAll(array('mjml', 'type', 'language'));
                if (!empty($templates)) {
                    $export_path = 'App/Export/Files/';
                    $template_export_date_time = date('Y-m-d-h-i', current_time('timestamp'));
                    $template_content = "";
                    foreach ($templates as $template) {
                        $template_content .= "\n\n/**************************************\n* Type: " . $template->type . "\n* Language: " . $template->language . "\n**************************************/\n\n";
                        $template_content .= $template->mjml;
                        $template_content .= "\n\n";
                    }
                    $file_name = 'exported-templates-' . $template_export_date_time . '.html';
                    $file_directory_path = $export_path . $file_name;
                    $file_path = WECP_PLUGIN_PATH . $file_directory_path;
                    $this->deleteFile($file_path);
                    $this->writeFile($file_path, $template_content);
                    $response['error'] = false;
                    $response['download'] = true;
                    $response['download_name'] = $file_name;
                    $response['download_url'] = WECP_PLUGIN_URL . $export_path . $file_name;
                    $response['message'] = __('Your template was ready to download!', WECP_TEXT_DOMAIN);
                }
            } catch (\Exception $exception) {
                $response['message'] = $exception->getMessage();
            }
        }
        wp_send_json($response);
    }

    /**
     * Export template to another template
     */
    function copyTemplateTo()
    {
        $response = array(
            'error' => true,
        );
        if (check_ajax_referer('wecp_copy_template_to', 'security') && current_user_can('manage_woocommerce')) {
            try {
                $source_template_id = self::$input->post('copy_from', 0, 'key');
                $type = self::$input->post('copy_to_email_type', null);
                $language = self::$input->post('copy_to_language', null);
                if (!empty($source_template_id) && !empty($language) && !empty($type)) {
                    $template_model = new templateModel();
                    $source_template_row = $template_model->getByKey($source_template_id);
                    if (empty($source_template_row)) {
                        $response['message'] = __('Sorry, Source template not found!', WECP_TEXT_DOMAIN);
                    } else {
                        $primary_key = $template_model->getPrimaryKey();
                        $destination_template_row = $template_model->getWhere("language='" . $language . "' AND type='" . $type . "'");
                        $destination_template_id = isset($destination_template_row->{$primary_key}) ? $destination_template_row->{$primary_key} : 0;
                        if ($destination_template_id == $source_template_id) {
                            $response['message'] = __('can\'t copy the template because source and destination templates are same!', WECP_TEXT_DOMAIN);
                        } else {
                            $html = isset($source_template_row->html) ? $source_template_row->html : '';
                            $mjml = isset($source_template_row->mjml) ? $source_template_row->mjml : '';
                            $css = isset($source_template_row->css) ? $source_template_row->css : '';
                            $elements = isset($source_template_row->elements) ? $source_template_row->elements : [];
                            $styles = isset($source_template_row->styles) ? $source_template_row->styles : [];
                            if (empty($destination_template_row)) {
                                $data = array(
                                    'html' => $html,
                                    'mjml' => $mjml,
                                    'elements' => $elements,
                                    'styles' => $styles,
                                    'css' => $css,
                                    'extra' => "",
                                    'is_active' => 0,
                                    'type' => $type,
                                    'language' => $language
                                );
                                $template_id = $template_model->insertRow($data);
                                $response['error'] = false;
                                $response['after_copy_customize_url'] = admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'editor', 'lang' => $language, 'type' => $type, 'id' => $template_id)));
                                $response['message'] = __('Template copied successfully!', WECP_TEXT_DOMAIN);
                            } else {
                                $data = array(
                                    'html' => $html,
                                    'mjml' => $mjml,
                                    'elements' => $elements,
                                    'styles' => $styles,
                                    'css' => $css
                                );
                                $template_model->updateRow($data, array($primary_key => $destination_template_id));
                                $response['error'] = false;
                                $response['after_copy_customize_url'] = admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'editor', 'lang' => $language, 'type' => $type, 'id' => $destination_template_id)));
                                $response['message'] = __('Old template successfully replaced by new template!', WECP_TEXT_DOMAIN);
                            }
                        }
                    }
                } else {
                    $response['message'] = __('Sorry, Invalid ajax call!', WECP_TEXT_DOMAIN);
                }
            } catch (\Exception $exception) {
                $response['message'] = $exception->getMessage();
            }
        }
        wp_send_json($response);
    }

    /**
     * Export Email template
     */
    function exportEmailTemplate()
    {
        $response = array(
            'error' => true,
        );
        if (check_ajax_referer('wecp_export_email_template', 'security') && current_user_can('manage_woocommerce')) {
            $template_id = self::$input->post_get('id', NULL, 'key');
            if (!empty($template_id)) {
                $templates_model = new templateModel();
                $template = $templates_model->getByKey($template_id);
                if (!empty($template)) {
                    $template_language = $template->language;
                    $template_type = $template->type;
                    $template_content = $template->mjml;
                    $file_name = $template_type . '-' . $template_language . '.mjml';
                    try {
                        $export_path = 'App/Export/Files/';
                        $file_directory_path = $export_path . $file_name;
                        $this->deleteFile(WECP_PLUGIN_PATH . $file_directory_path);
                        $this->writeFile(WECP_PLUGIN_PATH . $file_directory_path, $template_content);
                        $response['error'] = false;
                        $response['download'] = true;
                        $response['download_name'] = $file_name;
                        $response['download_url'] = WECP_PLUGIN_URL . $file_directory_path;
                        $response['message'] = __('Your template was ready to download!', WECP_TEXT_DOMAIN);
                    } catch (\Exception $exception) {
                        $response['message'] = $exception->getMessage();
                    }
                }
            } else {
                $response['message'] = __('Data corrupted!', WECP_TEXT_DOMAIN);
            }
        }
        wp_send_json($response);
    }

    /**
     * Delete files of the folder
     * @param $folder
     * @return bool
     * @throws \Exception
     */
    function deleteFilesInFolder($folder)
    {
        try {
            if (is_dir($folder)) {
                $files_list = glob($folder . '*');
                if (!empty($files_list)) {
                    foreach ($files_list as $file) {
                        $this->deleteFile($file);
                    }
                }
            }
            return true;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $file
     * @return bool
     * @throws \Exception
     */
    function deleteFile($file)
    {
        try {
            if (file_exists($file)) {
                unlink($file);
            }
            return true;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Write file
     * @param $file_path
     * @param $file_data
     * @return string
     * @throws \Exception
     */
    function writeFile($file_path, $file_data)
    {
        try {
            $file = fopen($file_path, 'w');
            fputs($file, $file_data);
            fclose($file);
            return $file_path;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * activate or deactivate template
     * @return array
     */
    function activateOrDeactivateTemplate()
    {
        $response = array(
            'error' => true,
        );
        if (check_ajax_referer('wecp_activate_or_deactivate_template', 'security') && current_user_can('manage_woocommerce')) {
            $is_active = self::$input->post_get('change_status_to', 0, 'key');
            $template_id = self::$input->post_get('id', NULL, 'key');
            $is_ajax = self::$input->post('is_ajax', false, 'key');
            if (!empty($template_id)) {
                $templates_model = new templateModel();
                $template = $templates_model->getByKey($template_id);
                if (!empty($template)) {
                    $templates_model->updateRow(array('is_active' => $is_active), array('id' => $template_id));
                    $response['error'] = false;
                    $response['message'] = __('Template status changed successfully!', WECP_TEXT_DOMAIN);
                } else {
                    $response['message'] = __('Sorry, template not found!', WECP_TEXT_DOMAIN);
                }
            } else {
                $response['message'] = __('Data corrupted!', WECP_TEXT_DOMAIN);
            }
            if ($is_ajax) {
                wp_send_json($response);
            }
        }
        return $response;
    }

    /**
     * Manage menu pages
     */
    function managePages()
    {
        if (self::$input->get('page', NULL) == WECP_PLUGIN_SLUG) {
            $view = self::$input->get('view', 'customize');
            $main_page_params = array(
                'current_view' => $view,
                'tab_content' => NULL,
                'extra' => NULL,
                'side_bar' => self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/side_bar.php')->render()
            );
            $lang = self::$input->get('lang', NULL);
            switch ($view) {
                case 'change_status':
                    $this->activateOrDeactivateTemplate();
                    wp_safe_redirect(admin_url('admin.php?' . http_build_query(array('view' => 'customize', 'lang' => $lang, 'page' => WECP_PLUGIN_SLUG))));
                    break;
                case 'reset_template':
                    $templates_model = new templateModel();
                    $template_id = self::$input->get('id', NULL, 'key');
                    if (!empty($template_id)) {
                        $templates_model->deleteRow(array('id' => $template_id));
                    }
                    wp_safe_redirect(admin_url('admin.php?' . http_build_query(array('view' => 'customize', 'lang' => $lang, 'page' => WECP_PLUGIN_SLUG))));
                    break;
                case 'choose_template':
                    $type = self::$input->get('type', NULL);
                    if (!empty($lang) && !empty($type)) {
                        $main_page_params['templates'] = $this->getTemplatesList($lang, $type);
                        $main_page_params['back_btn_url'] = admin_url('admin.php?' . http_build_query(array('view' => 'customize', 'page' => WECP_PLUGIN_SLUG)));
                        self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/template_chooser.php', $main_page_params)->display();
                    }
                    break;
                case 'create_template':
                    $response = $this->saveTemplates();
                    $is_error = isset($response['error']) ? $response['error'] : 1;
                    if (!$is_error) {
                        $redirect_url = isset($response['redirect']) ? $response['redirect'] : 1;
                        wp_safe_redirect($redirect_url);
                        exit;
                    } else {
                        $message = __('Can\'t process further', WECP_TEXT_DOMAIN);
                        wp_die($message);
                    }
                    break;
                case 'editor':
                    $templates_model = new templateModel();
                    $template_id = sanitize_key(self::$input->get('id', 0, 'key'));
                    $template = $templates_model->getByKey($template_id);
                    if (!empty($template)) {
                        $primary_key = $templates_model->getPrimaryKey();
                        try {
                            $params = array(
                                'template' => $template,
                                'sample_data_orders' => $this->getSampleOrders(),
                                'current_editing_template' => $this->getTemplateTypeTitle($template->type),
                                'template_id' => $template->{$primary_key},
                                'close_url' => admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG))),
                                'short_code_url' => admin_url('admin-ajax.php?' . http_build_query(array('action' => 'wecp_get_short_code_lists', 'security' => wp_create_nonce('wecp_get_short_code_lists')))),
                                'order_summary_image_url' => 'https://www.dropbox.com/s/ebek9iih9p0746k/cart-summary.png?raw=1'
                            );
                        } catch (\Exception $e) {
                            wp_die('Unable to process by email customizer plus. Reason =>' . $e->getMessage());
                        }
                        self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/editor.php', $params)->display();
                    }
                    break;
                case 'settings':
                    $default_tab = 'general';
                    $tab = self::$input->get('tab', $default_tab);
                    $page_details = $this->getSettingsPages($tab, $default_tab);
                    $main_page_params['tab_content'] = self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/settings.php', $page_details)->render();
                    self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/main.php', $main_page_params)->display();
                    break;
                default:
                case 'customize':
                    $default_language = self::$woocommerce->getSiteDefaultLang();
                    $chosen_language = (empty($lang)) ? $default_language : $lang;
                    $templates = $this->getSavedTemplates($chosen_language, array('type', 'id', 'is_active'));
                    $customized_templates = $this->preProcessTemplates($templates);
                    $params = array(
                        'has_valid_license' => self::$license->isValidLicense(),
                        'woocommerce' => self::$woocommerce,
                        'default_language' => $default_language,
                        'chosen_language' => $chosen_language,
                        'customized_templates' => $customized_templates,
                        'available_languages' => self::$woocommerce->getAvailableLanguages(),
                        'default_language_label' => self::$woocommerce->getLanguageLabel($default_language),
                        'email_type_lists' => self::$woocommerce->getEmailTypeLists(),
                        /*'supported_templates' => $this->supportedEmailTemplates()*/
                    );
                    $main_page_params['tab_content'] = self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/customizer.php', $params)->render();
                    $main_page_params['extra'] = $this->licenseValidationForm();
                    self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/main.php', $main_page_params)->display();
                    break;
            }
        } else {
            wp_die(__('Unable to process', WECP_TEXT_DOMAIN));
        }
    }

    /**
     * get the template type title
     * @param $type
     * @return string
     */
    function getTemplateTypeTitle($type)
    {
        $types = self::$woocommerce->getEmailTypeLists();
        if (!empty($types)) {
            foreach ($types as $email_type) {
                if ($email_type->id == $type) {
                    return $email_type->title;
                }
            }
        }
        return $type;
    }

    /**
     * get the order label
     * @param $order
     * @return mixed|void
     */
    function getOrderLabel($order)
    {
        $label = self::$woocommerce->getOrderNumber($order) . ' - ' . self::$woocommerce->getBillingFirstName($order) . ' ' . self::$woocommerce->getBillingLastName($order);
        return apply_filters('woocommerce_email_customizer_plus_get_sample_order_label', $label, $order);
    }

    /**
     * get the sample orders to send test email
     * @param int $limit_orders
     * @param bool $get_sample_orders
     * @return array
     * @throws \Exception
     */
    function getSampleOrders($limit_orders = 10, $get_sample_orders = true)
    {
        $limit_orders = apply_filters('woocommerce_email_customizer_plus_get_limit_for_sample_orders', $limit_orders);
        $return = array();
        $query = new \WC_Order_Query(array(
            'limit' => $limit_orders,
            'orderby' => 'date',
            'order' => 'DESC',
            'type' => 'shop_order',
            'return' => 'ids'
        ));
        $order_collection = $query->get_orders();
        if (!empty($order_collection)) {
            foreach ($order_collection as $order_id) {
                $order = self::$woocommerce->getOrder($order_id);
                $label = $this->getOrderLabel($order);
                $return[] = array(
                    "key" => $order->get_id(),
                    "label" => $label
                );
            }
        }
        if ($get_sample_orders) {
            $return[] = array(
                "key" => 'dummy_data',
                "label" => esc_html__('Dummy Data', WECP_TEXT_DOMAIN)
            );
        }
        return $return;
    }

    /**
     * License validation form
     * @return string
     */
    function licenseValidationForm()
    {
        if (self::$license->isValidLicense()) {
            return NULL;
        } else {
            $license_key = self::$license->getLicenseKey();
            return '<div class="model-license-key">
            <form id="validate_license_key_form_modal">
                <div class="wecp-card">
                    <div class="pt-1">
                        <h2 class="swal2-title">' . __("Enter License Key", WECP_TEXT_DOMAIN) . '</h2>   
                        <input type="hidden" name="action" value="wecp_validate_license_key">
                        <input type="hidden" name="security" value="' . wp_create_nonce("wecp_validate_license_key") . '">
                        <input type="text" id="modal_license_key" name="license_key" class="license-key-input" value="' . $license_key . '"
                               placeholder="Enter license key here...">
                        <p class="license_key_message" style="text-align: left;margin: 10px 0 25px; 0"></p>
                        <button id="validate-_license_key_modal_btn" type="submit" class="button license-key-submit">' . __("Validate", WECP_TEXT_DOMAIN) . '</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="hvrbox-layer_top"></div>';
        }
    }

    /**
     * List of short codes that can used by customer
     */
    function getShortCodeList()
    {
        if (check_ajax_referer('wecp_get_short_code_lists', 'security') && current_user_can('manage_woocommerce')) {
            $billing_address_list = array(
                'billing_address.formatted_address' => esc_html__('- To get billing address', WECP_TEXT_DOMAIN),
                'billing_address.first_name' => esc_html__('- To get billing first name', WECP_TEXT_DOMAIN),
                'billing_address.last_name' => esc_html__('- To get billing last name', WECP_TEXT_DOMAIN),
                'billing_address.name' => esc_html__('- To get billing name', WECP_TEXT_DOMAIN),
                'billing_address.company' => esc_html__('- to get billing company', WECP_TEXT_DOMAIN),
                'billing_address.phone' => esc_html__('- To get billing phone', WECP_TEXT_DOMAIN),
                'billing_address.address1' => esc_html__('- To get billing address 1', WECP_TEXT_DOMAIN),
                'billing_address.address2' => esc_html__('- To get billing address 2', WECP_TEXT_DOMAIN),
                'billing_address.city' => esc_html__('- To get billing city', WECP_TEXT_DOMAIN),
                'billing_address.state' => esc_html__('- To get billing state', WECP_TEXT_DOMAIN),
                'billing_address.province' => esc_html__('- To get billing province', WECP_TEXT_DOMAIN),
                'billing_address.zip' => esc_html__('- To get billing zip code', WECP_TEXT_DOMAIN),
                'billing_address.country' => esc_html__('- To get billing country', WECP_TEXT_DOMAIN)
            );
            $shipping_address_list = array(
                'shipping_address.formatted_address' => esc_html__('- To get shipping address', WECP_TEXT_DOMAIN),
                'shipping_address.first_name' => esc_html__('- To get shipping first name', WECP_TEXT_DOMAIN),
                'shipping_address.last_name' => esc_html__('- To get shipping last name', WECP_TEXT_DOMAIN),
                'shipping_address.name' => esc_html__('- To get shipping name', WECP_TEXT_DOMAIN),
                'shipping_address.company' => esc_html__('- To get shipping company', WECP_TEXT_DOMAIN),
                'shipping_address.address1' => esc_html__('- To get shipping address 1', WECP_TEXT_DOMAIN),
                'shipping_address.address2' => esc_html__('- To get shipping address 2', WECP_TEXT_DOMAIN),
                'shipping_address.city' => esc_html__('- To get shipping city', WECP_TEXT_DOMAIN),
                'shipping_address.state' => esc_html__('- To get shipping state', WECP_TEXT_DOMAIN),
                'shipping_address.province' => esc_html__('- To get shipping province', WECP_TEXT_DOMAIN),
                'shipping_address.zip' => esc_html__('- To get shipping zip code', WECP_TEXT_DOMAIN),
                'shipping_address.country' => esc_html__('- To get shipping country', WECP_TEXT_DOMAIN),
                'shipping_method.title' => esc_html__('- To get shipping method title', WECP_TEXT_DOMAIN),
                'shipping_method.price' => esc_html__('- To get shipping price', WECP_TEXT_DOMAIN)
            );
            $shop_details = array(
                'shop.name' => esc_html__('- To get shop name', WECP_TEXT_DOMAIN),
                'shop.email' => esc_html__('- To get shop E-Mail', WECP_TEXT_DOMAIN),
                'shop.url' => esc_html__('- To get shop URL', WECP_TEXT_DOMAIN),
                'shop.address1' => esc_html__('- To get shop address 1', WECP_TEXT_DOMAIN),
                'shop.address2' => esc_html__('- To get shop address 2', WECP_TEXT_DOMAIN),
                'shop.city' => esc_html__('- To get shop city', WECP_TEXT_DOMAIN),
                'shop.zip' => esc_html__('- To get shop zip code', WECP_TEXT_DOMAIN)
            );
            $order_details = array(
                'id' => esc_html__('- to get order id', WECP_TEXT_DOMAIN),
                'email' => esc_html__('- To get order E-Mail', WECP_TEXT_DOMAIN),
                'order_number' => esc_html__('- To get order number', WECP_TEXT_DOMAIN),
                'item_count' => esc_html__('- To get total item count', WECP_TEXT_DOMAIN),
                'last_note' => esc_html__('- To get last note', WECP_TEXT_DOMAIN),
                'landing_site' => esc_html__('- To get landing site URL', WECP_TEXT_DOMAIN),
                'note' => esc_html__('- To get note', WECP_TEXT_DOMAIN),
                'order_status_url' => esc_html__('- To get order status URL', WECP_TEXT_DOMAIN),
                'order_status_link' => esc_html__('- To get order status link', WECP_TEXT_DOMAIN),
                'subtotal_price' => esc_html__('- To get order sub-total', WECP_TEXT_DOMAIN),
                'total_price' => esc_html__('- To get order total', WECP_TEXT_DOMAIN),
                'created_at' => esc_html__('- To get order created date', WECP_TEXT_DOMAIN),
                'order.id' => esc_html__('- To get order id', WECP_TEXT_DOMAIN),
                'order.fee' => esc_html__('- To get order fee', WECP_TEXT_DOMAIN),
                'order.refund' => esc_html__('- To get order refund', WECP_TEXT_DOMAIN),
                'order.url' => esc_html__('- To get order url', WECP_TEXT_DOMAIN),
                'order.cart' => esc_html__('- To get order cart details', WECP_TEXT_DOMAIN),
                'order.downloads' => esc_html__('- To get order downloads details', WECP_TEXT_DOMAIN),
                'order.customer_provided_note' => esc_html__('- To get customer provided note', WECP_TEXT_DOMAIN),
            );
            $payment_details = array(
                'payment_method' => esc_html__('- To get order payment method', WECP_TEXT_DOMAIN),
                'payment_url' => esc_html__('- To get payment URL', WECP_TEXT_DOMAIN),
                'payment_transaction_id' => esc_html__('- To get order transaction id', WECP_TEXT_DOMAIN)
            );
            $customer_details = array(
                'customer.name' => esc_html__('- To get customer name', WECP_TEXT_DOMAIN),
                'customer.email' => esc_html__('- To get customer E-Mail', WECP_TEXT_DOMAIN),
                'customer.first_name' => esc_html__('- To get customer first name', WECP_TEXT_DOMAIN),
                'customer.last_name' => esc_html__('- To get customer last name', WECP_TEXT_DOMAIN)
            );
            $new_user_details = array(
                'customer.password' => esc_html__('- To get customer password', WECP_TEXT_DOMAIN),
                'customer.activation_url' => esc_html__('- To get customer activation URL', WECP_TEXT_DOMAIN)
            );
            $password_reset_details = array(
                'customer.password_reset_url' => esc_html__('- To get customer name', WECP_TEXT_DOMAIN)
            );
            $social_media_details = array(
                'facebook' => esc_html__('- Facebook URL from settings', WECP_TEXT_DOMAIN),
                'twitter' => esc_html__('- Twitter URL from settings', WECP_TEXT_DOMAIN),
                'instagram' => esc_html__('- Instagram URL from settings', WECP_TEXT_DOMAIN),
                'linkedin' => esc_html__('- Linkedin URL from settings', WECP_TEXT_DOMAIN),
                'pinterest' => esc_html__('- Pintrest URL from settings', WECP_TEXT_DOMAIN),
            );
            $additional_short_codes = apply_filters("woocommerce_email_customizer_plus_additional_short_codes_list", array());
            try {
                $sample_orders = $this->getSampleOrders(10, false);
            } catch (\Exception $exception) {
                $sample_orders = array();
            }
            $params = array(
                'billing_address' => $billing_address_list,
                'shipping_address' => $shipping_address_list,
                'shop_details' => $shop_details,
                'order_details' => $order_details,
                'payment_details' => $payment_details,
                'reset_details' => $password_reset_details,
                'new_user_details' => $new_user_details,
                'additional_short_codes' => $additional_short_codes,
                'customer_details' => $customer_details,
                'social_media_details' => $social_media_details,
                'sample_orders' => $sample_orders
            );
            self::$template->setData(WECP_PLUGIN_PATH . 'App/Views/Admin/short_codes.php', $params)->display();
            exit;
        }
    }

    /**
     * get short code list
     */
    function getOrderMetaShortCodeList()
    {
        if (check_ajax_referer('wecp_get_order_meta_shortcode_list', 'security') && current_user_can('manage_woocommerce')) {
            $order_id = self::$input->post('order_id', null, 'key');
            $meta_details = array();
            if (!empty($order_id)) {
                $order_meta_keys = get_post_custom_keys($order_id);
                if (!empty($order_meta_keys)) {
                    $order_meta_values = get_post_meta($order_id);
                    foreach ($order_meta_keys as $order_meta_key) {
                        if (isset($order_meta_values[$order_meta_key]) && isset($order_meta_values[$order_meta_key][0])) {
                            $order_meta_key_for_short_code = str_replace(' ', '_', $order_meta_key);
                            $key = 'order_meta.' . $order_meta_key_for_short_code;
                            if (is_string($order_meta_values[$order_meta_key][0])) {
                                $meta_details[$key] = $order_meta_values[$order_meta_key][0];
                            }
                        }
                    }
                }
            }
            wp_send_json_success($meta_details);
        }
    }

    /**
     * Validate the license key
     */
    function validateLicenseKey()
    {
        if (check_ajax_referer('wecp_validate_license_key', 'security') && current_user_can('manage_woocommerce')) {
            $license_key = self::$input->post('license_key', NULL);
            $response = $this->verifyLicenseKey($license_key);
            wp_send_json($response);
        }
    }

    /**
     * verify the license_key
     * @param $license_key
     * @return array
     */
    function verifyLicenseKey($license_key)
    {
        $current_status = 'invalid';
        update_option('woocommerce_email_customizer_plus_license_key_status', $current_status);
        update_option('woocommerce_email_customizer_plus_license_key', $license_key);
        if (!empty($license_key)) {
            $license = new License();
            $license_key_status = $license->validateKey($license_key);
            if ($license_key_status) {
                $current_status = $license_key_status;
            }
            if ($current_status == "invalid") {
                $response['license_key'] = false;
                $response['license_key_message'] = esc_html__('Please enter valid license key!', WECP_TEXT_DOMAIN);
            } elseif ($current_status == "expired") {
                $response['license_key'] = false;
                $response['license_key_message'] = esc_html__('Your license key was expired!', WECP_TEXT_DOMAIN);
            } else {
                $response['license_key'] = true;
                $response['license_key_message'] = esc_html__('Your license key validated successfully!', WECP_TEXT_DOMAIN);
            }
        } else {
            $response['license_key'] = false;
            $response['license_key_message'] = esc_html__("Please enter license key!", WECP_TEXT_DOMAIN);
        }
        update_option('woocommerce_email_customizer_plus_license_key_status', $current_status);
        return $response;
    }

    /**
     * Template preview URLs
     * @param $name
     * @param $type
     * @return mixed|null
     */
    function templatePreviewUrl($name, $type)
    {
        $template_preview_urls = array('default' => array(
            //Same templates user for 5
            'customer_on_hold_order' => 'https://www.dropbox.com/s/uoek0ebjirpjjua/on-hold-order.png?raw=1',
            'failed_order' => 'https://www.dropbox.com/s/9kfey4mf93o9wvz/failed-order.png?raw=1',
            'customer_completed_order' => 'https://www.dropbox.com/s/9qxqlocxmdzamx4/completed-order.png?raw=1',
            'customer_invoice' => 'https://www.dropbox.com/s/c4jhjyb7v6csubd/customer-invoice.png?raw=1',
            'customer_note' => 'https://www.dropbox.com/s/kxbbtc478d61lau/order-note.png?raw=1',
            'customer_processing_order' => 'https://www.dropbox.com/s/w2md6asm3klam1e/processing-order.png?raw=1',
            'new_renewal_order' => 'https://www.dropbox.com/s/tgiggdqwiu1e390/new-renewal-order.png?raw=1',
            'new_switch_order' => 'https://www.dropbox.com/s/mh63gknzi6uxcac/subscription-switched.png?raw=1',
            'customer_completed_renewal_order' => 'https://www.dropbox.com/s/eddf2gn35ugn71y/completed-renewal-order.png?raw=1',
            'customer_completed_switch_order' => 'https://www.dropbox.com/s/5bxsf7fw0i9nwh4/subscription-switch-complete.png?raw=1',
            'customer_renewal_invoice' => 'https://www.dropbox.com/s/b14gnqef21ppt93/customer-renewal-invoice.png?raw=1',
            'expired_subscription' => 'https://www.dropbox.com/s/g8juqvjdszdqjno/subscription-expired.png?raw=1',
            'suspended_subscription' => 'https://www.dropbox.com/s/1v3ff9fvkcj9ucw/subscription-suspended.png?raw=1',
            'new_order' => 'https://www.dropbox.com/s/00qiv1kpaug5ed7/new-order.png?raw=1',
            'cancelled_order' => 'https://www.dropbox.com/s/8xyf1w6jl5j9ah8/cancelled-order.png?raw=1',
            'customer_refunded_order' => 'https://www.dropbox.com/s/jcrhekpcfxe8pp2/refunded-order.png?raw=1',
            'customer_reset_password' => 'https://www.dropbox.com/s/c42rt9sq4t2n6kd/reset-password.png?raw=1',
            'customer_new_account' => 'https://www.dropbox.com/s/ilnqk0w3vuhl817/new-account.png?raw=1',
            'failed_renewal_authentication' => 'https://www.dropbox.com/s/ssqh5pwvaxuhxl3/failed-subscription-enewal-sca-authentication.png?raw=1',
            'failed_preorder_sca_authentication' => 'https://www.dropbox.com/s/iec04o96br14edl/pre-order-payment-action-needed.png?raw=1',
            'failed_authentication_requested' => 'https://www.dropbox.com/s/1eghhvl4ak3o1ob/payment-authentication-requested.png?raw=1',
            'cancelled_subscription' => 'https://www.dropbox.com/s/vy7707eiizkbwh3/subscription-cancelled.png?raw=1',
            'customer_processing_renewal_order' => 'https://www.dropbox.com/s/chbdb8syr3srxhm/processing-renewal-order.png?raw=1',
            'wlpr_point_expire_email' => 'https://www.dropbox.com/s/l82os2ynjuuw0r1/loyalty-expiry-email.jpg?raw=1',
            'wlpr_earn_email' => 'https://www.dropbox.com/s/3fufht1qbf4inbn/points-earned.jpg?raw=1',
            'wlpr_referee_email' => 'https://www.dropbox.com/s/gjstiozhj59feyi/points-earned-by-friends.jpg?raw=1',
            'wlpr_referral_email' => 'https://www.dropbox.com/s/5rqdagfass8g83e/points-earned-advocate.jpg?raw=1',
            'booking_cancelled' => 'https://www.dropbox.com/s/4mvmeh2n5lp1z4p/booking-cancelled-customer-yellow.png?raw=1',
            'admin_booking_cancelled' => 'https://www.dropbox.com/s/10a1yfhw20iokbc/booking-cancelled-yellow.png?raw=1',
            'new_booking' => 'https://www.dropbox.com/s/414rrhp6gv8wrfw/new-booking-yellow.png?raw=1',
            'booking_pending_confirmation' => 'https://www.dropbox.com/s/466w10rsytsvt9r/booking-pending-yellow.png?raw=1',
            'booking_confirmed' => 'https://www.dropbox.com/s/kjs507ouj9vctak/booking-confirmed-yellow.png?raw=1',
            'booking_notification' => 'https://www.dropbox.com/s/f76gkw6zwzvdgwh/booking-notification-yellow.png?raw=1',
            'booking_reminder' => 'https://www.dropbox.com/s/cntersxsrtvj1hn/booking-reminder-yellow.png?raw=1',
            'yith_wcmap_verify_account' => 'https://www.dropbox.com/s/ilnqk0w3vuhl817/new-account.png?raw=1',
        ), 'default_2' => array(
            'cancelled_order' => 'https://www.dropbox.com/s/n2o6myfhltha6hb/cancelled-order.png?raw=1',
            'customer_completed_order' => 'https://www.dropbox.com/s/pxmqg2dpeqnsx6f/completed-order.png?raw=1',
            'customer_invoice' => 'https://www.dropbox.com/s/nctuxef9ztoayiz/customer-invoice.png?raw=1',
            'customer_note' => 'https://www.dropbox.com/s/nj5ceaqb0b12989/customer-note.png?raw=1',
            'customer_renewal_invoice' => 'https://www.dropbox.com/s/zwqe2wjp4atjnka/customer-renewal-invoice.png?raw=1',
            'failed_order' => 'https://www.dropbox.com/s/hnbgbfjpa0pzll9/failed-order.png?raw=1',
            'failed_renewal_authentication' => 'https://www.dropbox.com/s/dtsj4dux0e4rvlm/failed-subscription-renewal-authentication.png?raw=1',
            'customer_new_account' => 'https://www.dropbox.com/s/myq34s7o00dp5lb/new-account.png?raw=1',
            'new_order' => 'https://www.dropbox.com/s/97jq37mvdig6xkx/new-order.png?raw=1',
            'new_renewal_order' => 'https://www.dropbox.com/s/298iai0zl584chi/new-subscription.png?raw=1',
            'customer_on_hold_order' => 'https://www.dropbox.com/s/jwnpyenm6sxvqt1/on-hold-order.png?raw=1',
            'failed_authentication_requested' => 'https://www.dropbox.com/s/vu06hw9i3q3fc6w/payment-authentication-requested-email.png?raw=1',
            'failed_preorder_sca_authentication' => 'https://www.dropbox.com/s/sjjzn61hacw09kh/pre-order-payment-action-needed.png?raw=1',
            'customer_processing_order' => 'https://www.dropbox.com/s/3k27sp26ux1wve5/processing-order.png?raw=1',
            'customer_refunded_order' => 'https://www.dropbox.com/s/2msk6c586rzbihp/refunded-order.png?raw=1',
            'customer_reset_password' => 'https://www.dropbox.com/s/86b48laxzs42pph/reset-password.png?raw=1',
            'cancelled_subscription' => 'https://www.dropbox.com/s/4bpoauczqesvfl7/subscription-cancelled.png?raw=1',
            'customer_completed_renewal_order' => 'https://www.dropbox.com/s/x07h2n7pufahyq0/subscription-completed.png?raw=1',
            'expired_subscription' => 'https://www.dropbox.com/s/vjiwy7z9cpysows/subscription-expired.png?raw=1',
            'suspended_subscription' => 'https://www.dropbox.com/s/xgwplxe7tipy33w/subscription-suspended.png?raw=1',
            'new_switch_order' => 'https://www.dropbox.com/s/m727v53s01gv2zn/subscription-switched.png?raw=1',
            'customer_completed_switch_order' => 'https://www.dropbox.com/s/p7k8n4k0y4dgyf5/subscription-switch-completed.png?raw=1',
            'customer_processing_renewal_order' => 'https://www.dropbox.com/s/hgeilfq3mjuyya3/processing-renewal-order.png?raw=1',
            'wlpr_point_expire_email' => 'https://www.dropbox.com/s/vkc292d5l5wsrg5/wlpr_point_expire_email.jpg?raw=1',
            'wlpr_earn_email' => 'https://www.dropbox.com/s/nh97sty9ir90kyd/wlpr_earn_email.jpg?raw=1',
            'wlpr_referee_email' => 'https://www.dropbox.com/s/dg5wy97ek20csh9/wlpr_referee_email.jpg?raw=1',
            'wlpr_referral_email' => 'https://www.dropbox.com/s/sbwm8oy4vcdm1ij/wlpr_referral_email.jpg?raw=1',
            'booking_cancelled' => 'https://www.dropbox.com/s/nmjdu91ptc178o1/boocking-cancelled-customer-blue.png?raw=1',
            'admin_booking_cancelled' => 'https://www.dropbox.com/s/ztj3lmvm06mbqkt/booking-cancelled-blue.png?raw=1',
            'new_booking' => 'https://www.dropbox.com/s/0epppsjlgutyi15/new-booking-blue.png?raw=1',
            'booking_pending_confirmation' => 'https://www.dropbox.com/s/ohpeajlcerii3sf/booking-pending-blue.png?raw=1',
            'booking_confirmed' => 'https://www.dropbox.com/s/nk9ojm0qdrx192m/booking-confirmed-blue.png?raw=1',
            'booking_notification' => 'https://www.dropbox.com/s/6wo1k8ir0jx343f/booking-notification-blue.png?raw=1',
            'booking_reminder' => 'https://www.dropbox.com/s/h8fnndfd1c92jgn/booking-reminder-blue.png?raw=1',
            'yith_wcmap_verify_account' => 'https://www.dropbox.com/s/myq34s7o00dp5lb/new-account.png?raw=1',
        ));
        $preview_url = isset($template_preview_urls[$name][$type]) ? $template_preview_urls[$name][$type] : NULL;
        return apply_filters('woocommerce_email_customizer_plus_templates_list', $preview_url, $name, $type);
    }

    /**
     * get the list of templates
     * @param $lang
     * @param $type
     * @return array
     */
    function getTemplatesList($lang, $type)
    {
        $list = array(
            'default' => array(
                'template_create_url' => admin_url('admin.php?' . http_build_query(array('view' => 'create_template', 'language' => $lang, 'page' => WECP_PLUGIN_SLUG, 'step' => 'choose_templates', 'type' => $type))),
                'template_preview_image_url' => $this->templatePreviewUrl('default', $type),
                'name' => __('Yellow', WECP_TEXT_DOMAIN)
            ),
            'default_2' => array(
                'template_create_url' => admin_url('admin.php?' . http_build_query(array('view' => 'create_template', 'language' => $lang, 'page' => WECP_PLUGIN_SLUG, 'step' => 'choose_templates', 'type' => $type))),
                'template_preview_image_url' => $this->templatePreviewUrl('default_2', $type),
                'name' => __('Blue', WECP_TEXT_DOMAIN)
            )
        );
        return apply_filters('woocommerce_email_customizer_plus_templates_list', $list, $lang, $type);
    }

    /**
     * Send test email for user
     */
    function sendTestMail()
    {
        if (check_ajax_referer('wecp_send_test_email', 'security') && current_user_can('manage_woocommerce')) {
            if (apply_filters('woocommerce_email_customizer_plus_is_demo', false)) {
                wp_send_json_error(esc_html__('Test E-Mail disabled for this site!', WECP_TEXT_DOMAIN));
            }
            $email = self::$input->post('email', NULL, 'email');
            $template_id = self::$input->post('template_id', NULL, 'key');
            $sample_order = self::$input->post('sample_order', "dummy_data");
            if (!empty($email) && !empty($template_id) && !empty($sample_order)) {
                $template_model = new templateModel();
                $template = $template_model->getByKey($template_id);
                if (!empty($template)) {
                    $html = $this->getHtmlContent($template, $sample_order);
                    $headers = $this->getMailHeaders();
                    if (wc_mail($email, esc_html__('Email customizer test email', WECP_TEXT_DOMAIN), $html, $headers)) {
                        wp_send_json_success(esc_html__('Email sent successfully!', WECP_TEXT_DOMAIN));
                    } else {
                        wp_send_json_error(esc_html__('Error in sending E-Mail!', WECP_TEXT_DOMAIN));
                    }
                }
            } else {
                wp_send_json_error(esc_html__('Data corrupted!', WECP_TEXT_DOMAIN));
            }
        }
    }

    /**
     * get the preview content
     */
    function getTemplatePreview()
    {
        if (check_ajax_referer('wecp_get_template_preview', 'security') && current_user_can('manage_woocommerce')) {
            $template_id = self::$input->post('template_id', NULL, 'key');
            $sample_order = self::$input->post('sample_order', "dummy_data");
            if (!empty($template_id) && !empty($sample_order)) {
                $template_model = new templateModel();
                $template = $template_model->getByKey($template_id);
                if (!empty($template)) {
                    $html = $this->getHtmlContent($template, $sample_order, false);
                    wp_send_json_success(array('html' => $html));
                }
            } else {
                wp_send_json_error(esc_html__('Data corrupted!', WECP_TEXT_DOMAIN));
            }
        }
    }

    /**
     * get the content html
     * @param $template
     * @param $sample_order
     * @param $encoded_content
     * @return mixed|void
     */
    function getHtmlContent($template, $sample_order, $encoded_content = true)
    {
        $html_template = $template->html;
        $args = array();
        $email_obj = new \stdClass();
        $email_obj->id = $template->type;
        $args['email'] = $email_obj;
        if ($sample_order == "dummy_data") {
            $short_code_obj = new ShortCodes($template->type, $html_template, $args, '', $template->language, true);
            $html = $short_code_obj->renderSampleTemplate($encoded_content);
        } else {
            $short_code_obj = new ShortCodes($template->type, $html_template, $args, $sample_order, $template->language, true);
            //$data = $short_code_obj->organizeTemplateData();
            $html = $short_code_obj->renderTemplate($encoded_content);
        }
        return $html;
    }

    /**
     * Mail headers
     * @return mixed|string
     */
    function getMailHeaders()
    {
        $charset = (function_exists('get_bloginfo')) ? get_bloginfo('charset') : 'UTF-8';
        $from_name = esc_html__('Admin', WECP_TEXT_DOMAIN);
        $from_address = get_option('admin_email');
        //Prepare for sending emails
        $headers = array(
            "From: \"$from_name\" <$from_address>",
            "Return-Path: <" . $from_address . ">",
            "Reply-To: \"" . $from_name . "\" <" . $from_address . ">",
            "X-Mailer: PHP" . phpversion(),
            "Content-Type: text/html; charset=\"" . $charset . "\""
        );
        $header = implode("\n", $headers);
        $header = apply_filters('rnocp_modify_coupon_email_headers', $header);
        return $header;
    }

    /**
     * Preprocess the template to display
     * @param $templates
     * @return array
     */
    function preProcessTemplates($templates)
    {
        $result = array();
        if (!empty($templates)) {
            foreach ($templates as $template) {
                $result[$template->type] = array('id' => $template->id, 'is_active' => $template->is_active);
            }
        }
        return $result;
    }

    /**
     * get the templates by language
     * @param $language
     * @param $select
     * @return array|object|void|null
     */
    function getSavedTemplates($language, $select = array())
    {
        $template_model = new templateModel();
        return $template_model->getWhere("language='{$language}'", $select, false);
    }

    /**
     * get the settings page list
     * @param $tab
     * @param $default_tab
     * @return array
     */
    function getSettingsPages($tab, $default_tab)
    {
        $page_contents = array();
        $name = self::$general_settings->name();
        $page_contents[$name] = self::$general_settings;
        $pages_list = array(
            'tabs' => $page_contents,
            'tab_content' => NULL
        );
        if (isset($page_contents[$tab])) {
            $pages_list['active'] = $tab;
            $pages_list['tab_content'] = $page_contents[$tab]->render();
        } elseif (isset($page_contents[$default_tab])) {
            $pages_list['active'] = $default_tab;
            $pages_list['tab_content'] = $page_contents[$default_tab]->render();
        }
        return $pages_list;
    }

    /**
     * un-register elementor scripts to solve the issue caused by elementor
     */
    function deRegisterElementorScripts()
    {
        wp_deregister_script("backbone-marionette");
        wp_deregister_script("elementor-dialog");
        wp_deregister_script("backbone-radio");
        wp_deregister_script("elementor-common-modules");
    }

    /**
     * add the default editor assets
     */
    function addDefaultEditorAssets()
    {
        //Registering js
        wp_register_script(WECP_PLUGIN_PREFIX . '-editor', WECP_PLUGIN_URL . 'Assets/Editor/Js/main.js', array(), WECP_PLUGIN_VERSION);
        //Registering css
        wp_register_style(WECP_PLUGIN_PREFIX . '-editor', WECP_PLUGIN_URL . 'Assets/Editor/Css/main.css', array(), WECP_PLUGIN_VERSION, 'screen');
        //Adding script meta
        //add_filter('script_loader_src', array($this, 'add_id_to_script'), 10, 2);
        //add_filter('clean_url', array($this, 'unclean_url'), 10, 3);
        //Enqueue the scripts
        wp_enqueue_style(WECP_PLUGIN_PREFIX . '-editor');
        wp_enqueue_script(WECP_PLUGIN_PREFIX . '-editor');
        $retainful_integration = new Retainful();
        $data = array(
            'localize_saving' => __('Saving...', WECP_TEXT_DOMAIN),
            'localize_copying' => __('Copying...', WECP_TEXT_DOMAIN),
            'localize_copy' => __('Copy', WECP_TEXT_DOMAIN),
            'localize_sure_want_delete' => __('Are you sure want to delete this template?', WECP_TEXT_DOMAIN),
            'localize_save' => __('Save  ', WECP_TEXT_DOMAIN),
            'localize_validating' => __('Validating...', WECP_TEXT_DOMAIN),
            'localize_validate' => __('Validate  ', WECP_TEXT_DOMAIN),
            'has_retainful_plugin' => ($retainful_integration->hasRetainfulPlugin()) ? 1 : 0,
            'localize_must_install_retainful' => __('You need to install and activate the Retainful plugin in-order to enable integration.')
        );
        $data = apply_filters("woocommerce_email_customizer_plus_localize_editor_data", $data);
        wp_localize_script(WECP_PLUGIN_PREFIX . '-editor', 'wecp_admin_js_data', $data);
    }

    /**
     * Clean the url
     * @param $good_protocol_url
     * @param $original_url
     * @param $_context
     * @return string
     */
    function unclean_url($good_protocol_url, $original_url, $_context)
    {
        if (false !== strpos($original_url, 'id')) {
            remove_filter('clean_url', 'unclean_url', 10);
            $url_parts = parse_url($good_protocol_url);
            return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . "' id='rflckeditor'";
        }
        return $good_protocol_url;
    }

    /**
     * Adding script ID attribute
     * @param $src
     * @param $handle
     * @return string
     */
    function add_id_to_script($src, $handle)
    {
        if ($handle != WECP_PLUGIN_PREFIX . '-ckeditor')
            return $src;
        return $src . "' id='rflckeditor'";
    }

    /**
     * Automatically save the template
     */
    function autoSaveTemplate()
    {
        if (check_ajax_referer('wecp_auto_save_edited_template', 'security') && current_user_can('manage_woocommerce')) {
            $json = file_get_contents('php://input');
            $response = array(
                'error' => true,
            );
            $template_id = self::$input->get('template_id', 0, 'key');
            if ($this->isJson($json) && !empty($template_id)) {
                $request_data = json_decode($json, true);
                $response = $this->saveTemplateByRequestData($request_data, $template_id);
            } else {
                $response['message'] = esc_html__('Data has corrupted!', WECP_TEXT_DOMAIN);
            }
            wp_send_json($response);
        }
    }

    /**
     * Save template
     * @param $template_id
     * @param $request_data
     * @return mixed
     */
    function saveTemplateByRequestData($request_data, $template_id)
    {
        $templates_model = new templateModel();
        $row = $templates_model->getByKey($template_id);
        if (!empty($row)) {
            $data = $this->getDataFromRequest($request_data);
            $templates_model->updateRow($data, array('id' => $template_id));
            $response['error'] = false;
            $response['message'] = esc_html__('Template saved successfully!', WECP_TEXT_DOMAIN);
        } else {
            $response['error'] = true;
            $response['message'] = esc_html__('Sorry, template not found!', WECP_TEXT_DOMAIN);
        }
        return $response;
    }

    /**
     * Get data to save from the request data
     * @param $request_data
     * @return array
     */
    function getDataFromRequest($request_data)
    {
        $html = isset($request_data['gjs-html']) ? $request_data['gjs-html'] : '';
        $mjml = isset($request_data['gjs-mjml']) ? $request_data['gjs-mjml'] : '';
        $css = isset($request_data['gjs-css']) ? $request_data['gjs-css'] : '';
        $elements = isset($request_data['gjs-components']) ? $request_data['gjs-components'] : '';
        $styles = isset($request_data['gjs-styles']) ? $request_data['gjs-styles'] : '';
        $data = array(
            'html' => $html,
            'mjml' => $mjml,
            'elements' => $elements,
            'styles' => $styles,
            'css' => $css,
            'extra' => ""
        );
        return $data;
    }

    /**
     * check the content is valid json content
     * @param $content
     * @return bool
     */
    public function isJson($content)
    {
        json_decode($content);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Add admin scripts
     * @param $hook
     */
    function adminScripts($hook)
    {
        if (self::$input->get('page', '') != WECP_PLUGIN_SLUG) {
            return;
        }
        $this->deRegisterElementorScripts();
        if (self::$input->get('view', 'customize') == "settings") {
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
            wp_localize_script('wp-theme-plugin-editor', 'cm_settings', $cm_settings);
            wp_enqueue_script('wp-theme-plugin-editor');
            wp_enqueue_style('wp-codemirror');
        }
        //Register the scripts
        wp_register_script(WECP_PLUGIN_SLUG . '-alertify', WECP_PLUGIN_URL . 'Assets/Admin/Js/alertify.min.js', array(), WECP_PLUGIN_VERSION);
        wp_register_script(WECP_PLUGIN_SLUG . '-popup', WECP_PLUGIN_URL . 'Assets/Admin/Js/jquery.modal.min.js', array(), WECP_PLUGIN_VERSION);
        wp_register_script(WECP_PLUGIN_SLUG . '-main', WECP_PLUGIN_URL . 'Assets/Admin/Js/main.js', array('jquery'), WECP_PLUGIN_VERSION, true);
        //Enqueue the scripts
        wp_enqueue_script(WECP_PLUGIN_SLUG . '-alertify');
        wp_enqueue_script(WECP_PLUGIN_SLUG . '-popup');
        wp_enqueue_script(WECP_PLUGIN_SLUG . '-main');
        //Enqueue styles
        wp_register_style(WECP_PLUGIN_SLUG . '-main', WECP_PLUGIN_URL . 'Assets/Admin/Css/main.css', array(), WECP_PLUGIN_VERSION);
        wp_register_style(WECP_PLUGIN_SLUG . '-alertify', WECP_PLUGIN_URL . 'Assets/Admin/Css/alertify.min.css', array(), WECP_PLUGIN_VERSION);
        wp_register_style(WECP_PLUGIN_SLUG . '-popup', WECP_PLUGIN_URL . 'Assets/Admin/Css/jquery.modal.min.css', array(), WECP_PLUGIN_VERSION);
        wp_enqueue_style(WECP_PLUGIN_SLUG . '-alertify');
        wp_enqueue_style(WECP_PLUGIN_SLUG . '-popup');
        wp_enqueue_style(WECP_PLUGIN_SLUG . '-main');
        //Enqueue Editor.helps to add more then one editor
        do_action('woocommerce_email_customizer_plus_load_editor_assets');
        //localize scripts
        $localize = array(
            'home_url' => get_home_url(),
            'admin_url' => admin_url(),
            'plugin_url' => WECP_PLUGIN_URL,
            'user_email' => wp_get_current_user()->user_email,
            'customizer_content_width' => '',
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => array(
                'wecp_activate_or_deactivate_template' => wp_create_nonce('wecp_activate_or_deactivate_template'),
                'wecp_export_all_email_templates' => wp_create_nonce('wecp_export_all_email_templates'),
                'wecp_export_email_template' => wp_create_nonce('wecp_export_email_template'),
                'wecp_get_order_meta_shortcode_list' => wp_create_nonce('wecp_get_order_meta_shortcode_list'),
            )
        );
        wp_localize_script(WECP_PLUGIN_SLUG . '-main', 'wecp_localize_data', $localize);
    }

    /**
     * save the templates
     */
    function saveTemplates()
    {
        $default_language = self::$woocommerce->getSiteDefaultLang();
        $lang = self::$input->get('language', $default_language);
        $type = self::$input->get('type', NULL);
        $response = array('error' => false);
        if (!empty($lang) && !empty($type)) {
            $template_name = self::$input->get('template', 'default');
            $step = self::$input->get('step', 'get_primary_details');
            $templates_model = new templateModel();
            $row = $templates_model->getWhere("language='" . $lang . "' AND type='" . $type . "'");
            if (!empty($row)) {
                $primary_key = $templates_model->getPrimaryKey();
                $template_id = isset($row->{$primary_key}) ? $row->{$primary_key} : 0;
                $redirect_url = admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'editor', 'lang' => $lang, 'type' => $type, 'id' => $template_id)));
                $redirect_url = apply_filters('woocommerce_email_customizer_plus_editor_redirect_url', $redirect_url, $type, $lang, $template_name, $template_id);
            } elseif (empty($row) && $step == "choose_templates") {
                $savable_data = self::$input->get();
                $savable_data = $this->getTemplateDetailsForSave($lang, $type, $template_name, $savable_data);
                $template_id = $templates_model->saveData($savable_data);
                do_action('woocommerce_email_customizer_plus_after_creating_template', $template_id, $lang, $type, $template_name);
                $redirect_url = admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'editor', 'lang' => $lang, 'type' => $type, 'id' => $template_id)));
                $redirect_url = apply_filters('woocommerce_email_customizer_plus_editor_redirect_url', $redirect_url, $type, $lang, $template_name, $template_id);
            } else {
                $redirect_url = admin_url('admin.php?' . http_build_query(array('page' => WECP_PLUGIN_SLUG, 'view' => 'choose_template', 'lang' => $lang, 'type' => $type)));
            }
            //Modify the redirect url if you have any other editor
            $response['redirect'] = $redirect_url;
        } else {
            $response['error'] = true;
        }
        return $response;
    }

    /**
     * get the template details for saving the template
     * @param $lang
     * @param $type
     * @param $template
     * @param $data
     * @return mixed|void
     */
    function getTemplateDetailsForSave($lang, $type, $template, $data)
    {
        //$default_language = self::$woocommerce->getSiteDefaultLang();
        //if (($default_language == $lang) && !empty($template)) {
        $file_path_json = WECP_PLUGIN_PATH . 'App/Views/Templates/' . $type . '/' . $template . '.mjml';
        $file_path_json = apply_filters('woocommerce_email_customizer_plus_template_path', $file_path_json, $lang, $type, $template, $data, 'mjml');
        if (file_exists($file_path_json)) {
            $content = self::$template->setData($file_path_json)->render();
        } else {
            $content = '<mjml><mj-body><mj-section><mj-column><mj-text font-family="Lato,Helvetica,sans-serif">Content 1</mj-text></mj-column></mj-section></mj-body></mjml>';
        }
        //}
        $template_content = array('gjs-mjml' => $content);
        $data = $this->getDataFromRequest($template_content);
        $data['is_active'] = '1';
        $data['language'] = $lang;
        $data['type'] = $type;
        $data = apply_filters('woocommerce_email_customizer_plus_before_save_new_template', $data, $lang, $type, $template);
        return $data;
    }

    /**
     * Add settings link
     * @param $links
     * @return array
     */
    function pluginActionLinks($links)
    {
        $action_links = array(
            'customized_templates' => '<a href="' . admin_url('admin.php?page=' . WECP_PLUGIN_SLUG) . '">' . __('Templates', WECP_TEXT_DOMAIN) . '</a>',
            'settings' => '<a href="' . admin_url('admin.php?page=' . WECP_PLUGIN_SLUG . '&view=settings') . '">' . __('Settings', WECP_TEXT_DOMAIN) . '</a>'
        );
        $return_links = array_merge($action_links, $links);
        return apply_filters('woocommerce_email_customizer_plus_action_links', $return_links);
    }

    /**
     * Save the settings
     */
    function saveSettings()
    {
        if (check_ajax_referer('wecp_save_settings', 'security') && current_user_can('manage_woocommerce')) {
            $key = self::$input->post('option_key', NULL);
            $response = array();
            if (!empty($key)) {
                $data = self::$input->post();
                if (isset($data['option_key'])) {
                    unset($data['option_key']);
                }
                if (isset($data['action'])) {
                    unset($data['action']);
                }
                $license_key = self::$input->post('license_key', NULL);
                $response = $this->verifyLicenseKey($license_key);
                $data = apply_filters('woocommerce_email_customizer_plus_before_save_settings', $data, $key);
                update_option($key, $data, true);
                do_action('woocommerce_email_customizer_plus_after_save_settings', $data, $key);
                $response['error'] = false;
                $response['message'] = esc_html__('Settings saved successfully!', WECP_TEXT_DOMAIN);
            } else {
                $response['error'] = true;
                $response['message'] = esc_html__('Settings key not found!', WECP_TEXT_DOMAIN);
            }
            wp_send_json($response);
        }
    }

    /**
     * get template
     */
    function getTemplate()
    {
        if (check_ajax_referer('wecp_get_template', 'security') && current_user_can('manage_woocommerce')) {
            $template_id = self::$input->get('template_id', 0, 'key');
            $template_json_decoded = $this->getTemplateById($template_id);
            wp_send_json($template_json_decoded);
        }
    }

    /**
     * get sample data to load
     */
    function getSampleData()
    {
        if (check_ajax_referer('wecp_get_sample_data', 'security') && current_user_can('manage_woocommerce')) {
            $last_order = self::$woocommerce->getLastOrderId();
            if (!empty($last_order)) {
                $order = self::$woocommerce->getOrder($last_order);
                $short_code_obj = new ShortCodes($order, '', array(), $last_order);
                $data = $short_code_obj->organizeTemplateData();
                $export_path = 'App/Export/Files/';
                $file_directory_path = $export_path . 'sample.json';
                $file_path = WECP_PLUGIN_PATH . $file_directory_path;
                $template_content = json_encode($data);
                try {
                    $this->writeFile($file_path, $template_content);
                } catch (\Exception $e) {
                    $data = array('error' => true, 'message' => $e->getMessage());
                }
            } else {
                $data = array();
            }
            wp_send_json($data);
        }
    }

    /**
     * get template by id
     * @param $template_id
     * @return array|false|mixed|object|string|void
     */
    function getTemplateById($template_id)
    {
        if (!empty($template_id)) {
            $templates_model = new templateModel();
            $template = $templates_model->getByKey($template_id);
            $response = array(
                'gjs-assets' => array(),
                'gjs-css' => NULL,//(isset($template->css) && !empty($template->css)) ? $template->css : "",
                'gjs-styles' => NULL,//(isset($template->styles) && !empty($template->styles)) ? $template->styles : "",
                'gjs-html' => NULL,//(isset($template->html) && !empty($template->html)) ? $template->html : "",
                'gjs-components' => NULL, //(isset($template->elements) && !empty($template->elements)) ? $template->elements : array(),
                'gjs-price_rule' => NULL,
                'gjs-mjml' => $template->mjml
            );
            return apply_filters('wecp_get_template_by_id', $response, $template);
        }
        return $this->defaultTemplateData();
    }

    /**
     * get default template data
     * @return false|mixed|string|void
     */
    function defaultTemplateData()
    {
        return json_encode(array('gjs-assets' => array(),
            'gjs-css' => NULL,
            'gjs-styles' => NULL,
            'gjs-html' => NULL,
            'gjs-components' => array(),
            'gjs-price_rule' => NULL));
    }
}