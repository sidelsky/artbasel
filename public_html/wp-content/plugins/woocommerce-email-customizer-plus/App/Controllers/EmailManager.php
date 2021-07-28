<?php

namespace Wecp\App\Controllers;

use Wecp\App\Controllers\Admin\Main;
use Wecp\App\Models\EmailCustomizerPlusTemplatesModel as templateModel;

class EmailManager extends Base
{
    /**
     * Register get template endpoint
     */
    function registerGetTemplateEndpoint()
    {
        register_rest_route('email-customizer/v1', '/get-template/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'getTemplate'),
        ));
    }

    /**
     * Register get template endpoint
     */
    function registerSaveTemplateEndpoint()
    {
        register_rest_route('email-customizer/v1', '/save-template', array(
            'methods' => 'POST',
            'accept_json' => true,
            'callback' => array($this, 'saveTemplate'),
        ));
    }

    /**
     * manage get template endpoint
     * @param $param
     * @return mixed|\WP_REST_Response
     */
    function getTemplate($param)
    {
        $template_id = $param->get_param('id');
        $main = new Main();
        $template = $main->getTemplateById($template_id);
        return rest_ensure_response($template);
    }

    /**
     * manage get template endpoint
     * @param $param
     * @return mixed|\WP_REST_Response
     */
    function saveTemplate($param)
    {
        $main = new Main();
        $template_id = $param->get_param('template_id');
        $template = $main->saveTemplateByRequestData($param, $template_id);
        return rest_ensure_response($template);
    }

    /**
     *
     * @param $args
     * @return string|null
     */
    function getRenderedTemplate($args)
    {
        $args = $this->preProcessArguments($args);
        if (isset($args['email']) && isset($args['email']->id) && !empty($args['email']->id)) {
            if ($template = $this->hasCustomizedTemplate($args)) {
                $order_id = '';
                if (isset($args['order'])) {
                    $order_id = self::$woocommerce->getOrderId($args['order']);
                } elseif (isset($args['subscription'])) {
                    /**
                     * @var $subscription \WC_Subscription
                     */
                    $subscription = $args['subscription'];
                    if (is_object($subscription) && method_exists($subscription, 'get_parent_id')) {
                        $order_id = $subscription->get_id();
                    }
                }
                $short_code = new ShortCodes($args['email']->id, $template->html, $args, $order_id, $template->language);
                return $short_code->renderTemplate();
            }
        }
        return NULL;
    }

    /**
     * if email not found then rearrange the args array
     * @param $args
     * @return mixed
     */
    function preProcessArguments($args)
    {
        if (!isset($args['email']) && isset($args['order']) && isset($args['email_heading'])) {
            $mailer = self::$woocommerce->getMailer();
            if (isset($mailer->emails)) {
                $emails = $mailer->emails;
                foreach ($emails as $email) {
                    if (!empty($email->object) && $args['email_heading'] == $email->heading) {
                        $args['email'] = $email;
                        break;
                    } else if (isset($email->settings) && !empty($email->settings)) {
                        $settings = $email->settings;
                        if (isset($settings['heading']) && $args['email_heading'] == $settings['heading']) {
                            $args['email'] = $email;
                            break;
                        }
                    }
                }
            }
        }
        return $args;
    }

    /**
     * override the templates
     * @param $located
     * @param $template_name
     * @param $args
     * @param $template_path
     * @param $default_path
     * @return mixed
     */
    function overrideEmailTemplates($located, $template_name, $args, $template_path, $default_path)
    {
        $args = $this->preProcessArguments($args);
        if (isset($args['email']) && isset($args['email']->id) && !empty($args['email']->id)) {
            $template = $this->hasCustomizedTemplate($args);
            if (!empty($template)) {
                if (isset($template->html) && !empty($template->html)) {
                    //For sending the the normal email templates
                    return WECP_PLUGIN_PATH . 'templates/email_template.php';
                }
            }
        }
        return $located;
    }

    /**
     * get template from the order
     * @param $args
     * @return array|bool|object|void|null
     */
    function hasCustomizedTemplate($args)
    {
        if (isset($args['email']) && isset($args['email']->id) && !empty($args['email']->id)) {
            $lang = $this->getLanguageFromOrder($args);
            $type = $args['email']->id;
            if ($type == 'customer_partially_refunded_order') {
                $type = 'customer_refunded_order';
            }
            $customizer_model = new templateModel();
            $template = $customizer_model->getWhere("language='" . $lang . "' AND is_active='1' AND type='" . $type . "'", array('id', 'html', 'language'));
            if (empty($template)) {
                return false;
            } else {
                return $template;
            }
        }
        return false;
    }

    /**
     * get the language from order
     * @param $args
     * @return string
     */
    function getLanguageFromOrder($args)
    {
        $selected_language = $language = '';
        //to get language from WPML language
        if (!empty($args['order'])) {
            $order_id = self::$woocommerce->getOrderId($args['order']);
            if (!empty($order_id)) {
                $language = get_post_meta($order_id, 'wpml_language', true);
            }
        }
        $load_language_from_order_for_admin = apply_filters('woocommerce_email_customizer_plus_load_language_from_order_even_for_admin_emails', false, $args);
        if (isset($args['sent_to_admin']) && $args['sent_to_admin'] && (!$load_language_from_order_for_admin)) {
        } else {
            if ($language !== false && $language != '') {
                if (function_exists('icl_get_languages')) {
                    $languages = icl_get_languages();
                    if (isset($languages[$language])) {
                        if (isset($languages[$language]['default_locale'])) {
                            $selected_language = $languages[$language]['default_locale'];
                        }
                    }
                }
            }
        }
        //If empty of selected language, then use site's default language as selected language
        if (empty($selected_language)) {
            $selected_language = self::$woocommerce->getSiteDefaultLang();
        }
        return apply_filters('woocommerce_email_customizer_plus_load_language_for_sending_email', $selected_language);
    }

    /**
     * @param $content
     * @return false|string
     */
    function reproduceHtmlContent($content)
    {
        $regex = '/<wecp>(.*?)<\/wecp>/';
        $new_matches = array();
        preg_match_all($regex, $content, $new_matches, PREG_SET_ORDER);
        if (isset($new_matches[0][1]) && !empty($new_matches[0][1])) {
            /*foreach ($new_matches[0] as $key => $val) {
                if ($key == 1) {*/
            $settings = self::$general_settings->getOptions();
            $autofix_broken_html = isset($settings['autofix_broken_html']) ? $settings['autofix_broken_html'] : '0';
            $decoded = base64_decode($new_matches[0][1]);
            if ($autofix_broken_html == 1) {
                $content = $this->wpautop($decoded, false);
            } else {
                $content = $decoded;
            }
            apply_filters('woocommerce_email_customizer_plus_reproduce_html', $content);
            /*}
        }*/
        }
        return $content;
    }

    /**
     * Replaces double line-breaks with paragraph elements.
     *
     * A group of regex replaces used to identify text formatted with newlines and
     * replace double line-breaks with HTML paragraph tags. The remaining line-breaks
     * after conversion become <<br />> tags, unless $br is set to '0' or 'false'.
     *
     * @param string $pee The text which has to be formatted.
     * @param bool $br Optional. If set, this will convert all remaining line-breaks
     *                    after paragraphing. Default true.
     * @return string Text which has been converted into correct paragraph tags.
     * @since 1.0.8
     *
     */
    function wpautop($pee, $br = true)
    {
        $pre_tags = array();
        if (trim($pee) === '') {
            return '';
        }
        // Just to make things a little easier, pad the end.
        $pee = $pee . "\n";
        // Change multiple <br>s into two line breaks, which will turn into paragraphs.
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
        // Add a double line break above block-level opening tags.
        $pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee);
        // Add a double line break below block-level closing tags.
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        // Add a double line break after hr tags, which are self closing.
        $pee = preg_replace('!(<hr\s*?/?>)!', "$1\n\n", $pee);
        // Standardize newline characters to "\n".
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee);
        // Find newlines in all elements and add placeholders.
        $pee = wp_replace_in_html_tags($pee, array("\n" => ' <!-- wpnl --> '));
        // Remove more than two contiguous line breaks.
        $pee = preg_replace("/\n\n+/", "\n\n", $pee);
        // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $pee);
        // In some cases <li> may get wrapped in <p>, fix them.
        $pee = preg_replace('|<p>(<li.+?)</p>|', '$1', $pee);
        // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        // If a <br /> tag is after an opening or closing block tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $pee);
        // If a <br /> tag is before a subset of opening or closing block tags, remove it.
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);
        // Replace placeholder <pre> tags with their original content.
        if (!empty($pre_tags)) {
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);
        }
        // Restore newlines in all elements.
        if (false !== strpos($pee, '<!-- wpnl -->')) {
            //$pee = str_replace(array(' <!-- wpnl --> ', '<!-- wpnl -->'), "\n", $pee);
        }
        // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee);
        // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $pee);
        // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee);
        // Under certain strange conditions it could create a P of entirely whitespace.
        $pee = preg_replace('|<p>\s*</p>|', '', $pee);
        $pee = str_replace('!important', '', $pee);
        return $pee;
    }
}