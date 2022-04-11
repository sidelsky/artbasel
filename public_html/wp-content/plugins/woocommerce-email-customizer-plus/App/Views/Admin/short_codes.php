<table id="short-code-list">
    <?php
    if (!empty($billing_address)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Billing Address', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($billing_address as $short_code => $detail) {
            ?>
            <tr>
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Shipping short codes
    if (!empty($shipping_address)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Shipping Address', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($shipping_address as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Shop short codes
    if (!empty($shop_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Shop Details', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($shop_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Order short codes
    if (!empty($order_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Order Details', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($order_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Order meta
    ?>
    <tr>
        <th colspan="2"><?php _e('Order Meta', WECP_TEXT_DOMAIN); ?></th>
    </tr>
    <tr data-copy="{{<?php echo $short_code; ?>}}">
        <td>{{order_meta.<?php esc_html_e('Your meta key', WECP_TEXT_DOMAIN); ?>}}</td>
        <td>- Loads value of order meta key<br><b>Replace "spaces" with "underscore"</b><br> <b>Example : </b>{{order_meta.Delivery
            Date}}<b> to </b>{{order_meta.Delivery_Date}}
        </td>
    </tr>
    <?php
    //Payment details short codes
    if (!empty($payment_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Payment Details', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($payment_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Reset Password short codes
    if (!empty($reset_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Reset Password', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($reset_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //New User short codes
    if (!empty($new_user_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('New User', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($new_user_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Customer details short codes
    if (!empty($customer_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Customer Details', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($customer_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //Customer details short codes
    if (!empty($additional_short_codes)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Additional Short codes', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($additional_short_codes as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    //social media details short codes
    if (!empty($social_media_details)) {
        ?>
        <tr>
            <th colspan="2"><?php _e('Social media Short codes', WECP_TEXT_DOMAIN); ?></th>
        </tr>
        <?php
        foreach ($social_media_details as $short_code => $detail) {
            ?>
            <tr data-copy="{{<?php echo $short_code; ?>}}">
                <td>{{<?php echo $short_code; ?>}}</td>
                <td class="wecp-wrapword"><?php echo $detail; ?></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
<?php
if (!empty($sample_orders)) {
    ?>
    <hr>
    <h3><?php esc_html_e('Order meta shortcodes', WECP_TEXT_DOMAIN); ?></h3>
    <table style="width: 100%;">
        <thead>
        <tr>
            <th width="35%">
                <?php
                esc_html_e('Choose sample order', WECP_TEXT_DOMAIN);
                ?>
            </th>
            <td>
                <label for="short_codes_list_sample_orders">
                    <select id="short_codes_list_sample_orders">
                        <?php
                        foreach ($sample_orders as $key => $value) {
                            ?>
                            <option value="<?php echo $value['key'] ?>"><?php echo $value['label'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <button style="color: #fff;background: #17a2b8;padding: 4px 10px;border: 1px solid #17a2b8;border-radius: 5px;"
                            id="short_codes_view_order_meta_list_btn"><?php esc_html_e('View order meta', WECP_TEXT_DOMAIN); ?></button>
                </label>
            </td>
        </tr>
        </thead>
        <tbody id="short_codes_order_meta_details">
        </tbody>
    </table>
    <?php
}
?>
<style>
    .wecp-wrapword {
        white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
        white-space: -webkit-pre-wrap; /* Chrome & Safari */
        white-space: -pre-wrap; /* Opera 4-6 */
        white-space: -o-pre-wrap; /* Opera 7 */
        white-space: pre-wrap; /* CSS3 */
        word-wrap: break-word; /* Internet Explorer 5.5+ */
        word-break: break-all;
        white-space: normal;
    }
</style>