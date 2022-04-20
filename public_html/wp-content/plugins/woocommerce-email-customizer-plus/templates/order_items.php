<div class="email-product-list" style="padding: 15px 25px;">
    <?php
    /**
     * Order details table shown in emails.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
     * will need to copy the new files to your theme to maintain compatibility. We try to do this.
     * as little as possible, but it does happen. When this occurs the version of the template file will.
     * be bumped and the readme will list any important changes.
     */
    if (!defined('ABSPATH')) {
        exit;
    }
    $obj = new stdClass();
    $sent_to_admin = (isset($sent_to_admin) ? $sent_to_admin : false);
    $email = (isset($email) ? $email : '');
    $plain_text = (isset($plain_text) ? $plain_text : '');
    $show_payment_instruction = isset($settings['show_payment_instruction']) ? $settings['show_payment_instruction'] : 1;
    $show_product_sku = isset($settings['show_product_sku']) ? $settings['show_product_sku'] : 0;
    $email_type = (isset($email_arguments['email']) && isset($email_arguments['email']->id) && !empty($email_arguments['email']->id)) ? $email_arguments['email']->id : 'new_order';
    if ($show_product_sku == 0) {
        $show_product_sku = $sent_to_admin;
    }
    if ($show_payment_instruction == 1 || ($show_payment_instruction == 2 && !$sent_to_admin)) {
        do_action('woocommerce_email_before_order_table', (isset($order) ? $order : $obj), $sent_to_admin, $plain_text, $email);
    } ?>
    <table class="email_builder_table_items" cellspacing="0" cellpadding="6" style="width: 100% !important;" border="1"
           dir="<?php echo $direction ?>"
           width="100%">
        <thead>
        <tr>
            <th class="td" scope="col"><?php _e('Product', 'woocommerce'); ?></th>
            <th class="td" scope="col"><?php _e('Quantity', 'woocommerce'); ?></th>
            <th class="td" scope="col"><?php _e('Price', 'woocommerce'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($order_items)) {
            $text_align = is_rtl() ? 'right' : 'left';
            foreach ($order_items as $item_id => $item) {
                if (apply_filters('woocommerce_order_item_visible', true, $item)) {
                    $product = $item->get_product();
                    ?>
                    <tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
                        <td class="td"
                            style=" vertical-align:middle;word-wrap:break-word;"><?php
                            // Show title/image etc
                            echo '<div style="margin-bottom: 5px">';
                            if ($args['show_image'] && is_object($product)) {
                                echo apply_filters('woocommerce_order_item_thumbnail', '<img src="' . ($product->get_image_id() ? current(wp_get_attachment_image_src($product->get_image_id(), $args['image_size'][2])) : wc_placeholder_img_src()) . '" alt="' . esc_attr__('Product image', 'woocommerce') . '" height="' . esc_attr($args['image_size'][1]) . '" width="' . esc_attr($args['image_size'][0]) . '" style="vertical-align:middle; margin-' . (is_rtl() ? 'left' : 'right') . ': 10px;"  />', $item);
                            }
                            // Product name
                            echo apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false);
                            // SKU
                            if ($args['show_sku'] && is_object($product) && $product->get_sku()) {
                                echo ' (#' . $product->get_sku() . ')';
                            }
                            echo '</div>';
                            // allow other plugins to add additional product information here
                            do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, $args['plain_text']);
                            wc_display_item_meta($item);
                            if ($args['show_download_links']) {
                                wc_display_item_downloads($item);
                            }
                            // allow other plugins to add additional product information here
                            do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, $args['plain_text']);
                            ?></td>
                        <td class="td"
                            style="vertical-align:middle;"><?php echo apply_filters('woocommerce_email_order_item_quantity', $item->get_quantity(), $item); ?></td>
                        <td class="td"
                            style="vertical-align:middle;"><?php echo $order->get_formatted_line_subtotal($item); ?></td>
                    </tr>
                    <?php
                }
                if ($args['show_purchase_note'] && is_object($product) && ($purchase_note = $product->get_purchase_note())) { ?>
                    <tr>
                        <td colspan="3"
                            style="text-align:<?php echo $text_align; ?>; vertical-align:middle;"><?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); ?></td>
                    </tr>
                <?php }
            }
        } ?>
        </tbody>
        <tfoot>
        <?php
        if ($totals) {
            $i = 0;
            foreach ($totals as $total) {
                $i++;
                ?>
                <tr>
                <th class="td" scope="row" colspan="2"
                    style=" <?php if ($i === 1) echo 'border-top-width: 1px'; ?>"><?php echo __($total['label'],'woocommerce'); ?></th>
                <td class="td"
                    style="<?php if ($i === 1) echo 'border-top-width: 1px;'; ?>"><?php echo $total['value']; ?></td>
                </tr><?php
            }
        }
        ?>
        </tfoot>
    </table>
    <?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?>
</div>
