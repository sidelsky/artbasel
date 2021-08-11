<?php
$post = $_GET;
require_once(DZSAP_BASE_PATH.'inc/php/get_wp.php');


?>
Please note that this feature uses the last saved data. Unsaved changes will not be exported.
<form action="<?php echo site_url() . '/wp-admin/options-general.php?page=dzsap_menu'; ?>" method="POST">
    <input type="hidden" class="hidden" name="slidernr" value="<?php echo $post['slidernr']; ?>"/> 
    <input type="hidden" class="hidden" name="slidername" value="<?php echo $post['slidername']; ?>"/> 
    <input type="hidden" class="hidden" name="currdb" value="<?php echo $post['currdb']; ?>"/> 
    <input class="button-secondary" type="submit" name="dzsap_exportslider" value="Export"/></form>
