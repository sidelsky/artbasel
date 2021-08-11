<?php
$post = $_GET;
require_once('../inc/php/get_wp.php');

echo esc_html__('Please note that this feature uses the last saved data. Unsaved changes will not be exported.', DZSAP_ID);
?>

<form action="" method="POST">
    <input type="hidden" class="hidden" name="slidernr" value="<?php echo $post['slidernr']; ?>"/>
    <input type="hidden" class="hidden" name="slidername" value="<?php echo $post['slidername']; ?>"/>
    <input type="hidden" class="hidden" name="currdb" value="<?php echo $post['currdb']; ?>"/>
    <input class="button-secondary" type="submit" name="dzsap_exportslider_config" value="Export"/></form>
