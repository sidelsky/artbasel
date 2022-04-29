<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;
	
require_once 'class-wonderplugin-popup-list-table.php';
require_once 'class-wonderplugin-popup-analytics-table.php';
require_once 'class-wonderplugin-popup-localrecord-table.php';
require_once 'class-wonderplugin-popup-creator.php';

class WonderPlugin_Popup_View {

	private $controller;
	private $list_table, $analytics_table, $localrecord_table;
	private $creator;
	
	function __construct($controller) {
		
		$this->controller = $controller;
	}
	
	function add_metaboxes() {
		add_meta_box('overview_features', __('Wonder Popup Plugin Features', 'wonderplugin_popup'), array($this, 'show_features'), 'wonderplugin_popup_overview', 'features', '');
		add_meta_box('overview_upgrade', __('Upgrade to Commercial Version', 'wonderplugin_popup'), array($this, 'show_upgrade_to_commercial'), 'wonderplugin_popup_overview', 'upgrade', '');
		add_meta_box('overview_news', __('WonderPlugin News', 'wonderplugin_popup'), array($this, 'show_news'), 'wonderplugin_popup_overview', 'news', '');
		add_meta_box('overview_contact', __('Contact Us', 'wonderplugin_popup'), array($this, 'show_contact'), 'wonderplugin_popup_overview', 'contact', '');
	}
	
	function show_upgrade_to_commercial() {
		?>
		<ul class="wonderplugin-feature-list">
			<li>Use on commercial websites</li>
			<li>Remove the wonderplugin.com watermark</li>
			<li>Techincal support</li>
			<li><a href="https://www.wonderplugin.com/wordpress-popup/order/" target="_blank">Upgrade to Commercial Version</a></li>
		</ul>
		<?php
	}
	
	function show_news() {
		
		include_once( ABSPATH . WPINC . '/feed.php' );
		
		$rss = fetch_feed( 'http://www.wonderplugin.com/feed/' );
		
		$maxitems = 0;
		if ( ! is_wp_error( $rss ) )
		{
			$maxitems = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
		}
		?>
		
		<ul class="wonderplugin-feature-list">
		    <?php if ( $maxitems > 0 ) {
		        foreach ( $rss_items as $item )
		        {
		        	?>
		        	<li>
		                <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank" 
		                    title="<?php printf( __( 'Posted %s', 'wonderplugin_popup' ), $item->get_date('j F Y | g:i a') ); ?>">
		                    <?php echo esc_html( $item->get_title() ); ?>
		                </a>
		                <p><?php echo esc_html( $item->get_description() ); ?></p>
		            </li>
		        	<?php 
		        }
		    } ?>
		</ul>
		<?php
	}
	
	function show_features() {
		?>
		<ul class="wonderplugin-feature-list">
			<li>Works on mobile, tablets and all major web browsers, including iPhone, iPad, Android, Firefox, Safari, Chrome, Opera, Internet Explorer and Microsoft Edge</li>
			<li>Pre-defined professional templates</li>
			<li>Easy-to-use wizard style user interface</li>
			<li>Instantly preview</li>
		</ul>
		<?php
	}
	
	function show_contact() {
		?>
		<p>Technical support is available for Commercial Version users at support@wonderplugin.com. Please include your license information, WordPress version, link to your webpage, all related error messages in your email.</p> 
		<?php
	}
	
	function print_overview() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php echo __( 'Wonder Popup', 'wonderplugin_popup' ) . ( (WONDERPLUGIN_POPUP_VERSION_TYPE == "C") ? " Pro" : ( (WONDERPLUGIN_POPUP_VERSION_TYPE == "L") ? " Lite" : " Trial" ) ) . " Version " . WONDERPLUGIN_POPUP_VERSION; ?> </h2>
		 
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<h3>WordPress Popup Plugin</h3>
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<h4>Get Started</h4>
						<a class="button button-primary button-hero" href="<?php echo admin_url('admin.php?page=wonderplugin_popup_add_new'); ?>">Create A New Popup</a>
					</div>
					<div class="welcome-panel-column welcome-panel-last">
						<h4>More Actions</h4>
						<ul>
							<li><a href="<?php echo admin_url('admin.php?page=wonderplugin_popup_show_items'); ?>" class="welcome-icon welcome-widgets-menus">Manage Existing Popups</a></li>
							<li><a href="http://www.wonderplugin.com/wordpress-popup/help/" target="_blank" class="welcome-icon welcome-learn-more">Help Document</a></li>
							<?php  if (WONDERPLUGIN_POPUP_VERSION_TYPE !== "C") { ?>
							<li><a href="http://www.wonderplugin.com/wordpress-popup/order/" target="_blank" class="welcome-icon welcome-view-site">Upgrade to Commercial Version</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder columns-2">
	 
	                 <div class="postbox-container">
	                    <?php 
	                    do_meta_boxes( 'wonderplugin_popup_overview', 'features', '' ); 
	                    do_meta_boxes( 'wonderplugin_popup_overview', 'contact', '' ); 
	                    ?>
	                </div>
	 
	                <div class="postbox-container">
	                    <?php 
	                    if (WONDERPLUGIN_POPUP_VERSION_TYPE != "C")
	                    	do_meta_boxes( 'wonderplugin_popup_overview', 'upgrade', ''); 
	                    do_meta_boxes( 'wonderplugin_popup_overview', 'news', ''); 
	                    ?>
	                </div>
	 
	        </div>
        </div>
            
		<?php
	}
	
	
	function print_edit_settings() {
	?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php _e( 'Settings', 'wonderplugin_popup' ); ?> </h2>
		<?php

		if ( isset($_POST['save-popup-options']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-settings') )
		{		
			unset($_POST['save-popup-options']);
			
			$this->controller->save_settings($_POST);
			
			echo '<div class="updated"><p>Settings saved.</p></div>';
		}
								
		$settings = $this->controller->get_settings();
		$userrole = $settings['userrole'];
		$keepdata = $settings['keepdata'];
		$disableupdate = $settings['disableupdate'];
		$supportwidget = $settings['supportwidget'];
		$addjstofooter = $settings['addjstofooter'];
		$ajaxverifynonce = $settings['ajaxverifynonce'];
		$sanitizehtmlcontent = $settings['sanitizehtmlcontent'];
		$emailfrom = $settings['emailfrom'];
		$emailfromname = $settings['emailfromname'];
		$enablesmtp = $settings['enablesmtp'];
		$smtphostname = $settings['smtphostname'];
		$smtpport = $settings['smtpport'];
		$smtpsecure = $settings['smtpsecure'];
		$smtpusername = $settings['smtpusername'];
		$smtppassword = $settings['smtppassword'];
		
		?>
		
		<h3>This page is only available for users of Administrator role.</h3>
		
        <form method="post">
        
        <?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-settings'); ?>
        
        <ul class="wonderplugin-tab-buttons-horizontal-xml" data-panelsid="wonderplugin-popup-settings-panels" id="wp-settings-toolbar">
        	<li class="wonderplugin-tab-button-horizontal-xml wonderplugin-tab-button-horizontal-xml-selected">General</li>
        	<li class="wonderplugin-tab-button-horizontal-xml">Email Settings</li>
        </ul>
        <ul class="wonderplugin-tabs-horizontal-xml" id="wonderplugin-popup-settings-panels">
        	<li class="wonderplugin-tab wonderplugin-tab-horizontal-xml wonderplugin-tab-horizontal-xml-selected">
        	
		        <table class="form-table">
		        
		        <tr valign="top">
					<th scope="row">Set minimum user role</th>
					<td>
						<select name="userrole">
						  <option value="Administrator" <?php echo ($userrole == 'manage_options') ? 'selected="selected"' : ''; ?>>Administrator</option>
						  <option value="Editor" <?php echo ($userrole == 'moderate_comments') ? 'selected="selected"' : ''; ?>>Editor</option>
						  <option value="Author" <?php echo ($userrole == 'upload_files') ? 'selected="selected"' : ''; ?>>Author</option>
						</select>
					</td>
				</tr>
					
				<tr>
					<th>Data option</th>
					<td><label><input name='keepdata' type='checkbox' id='keepdata' <?php echo ($keepdata == 1) ? 'checked' : ''; ?> /> Keep data when deleting the plugin</label>
					</td>
				</tr>
				
				<tr>
					<th>Update option</th>
					<td><label><input name='disableupdate' type='checkbox' id='disableupdate' <?php echo ($disableupdate == 1) ? 'checked' : ''; ?> /> Disable plugin version check and update</label>
					</td>
				</tr>
				
				<tr>
					<th>Display popup in widget</th>
					<td><label><input name='supportwidget' type='checkbox' id='supportwidget' <?php echo ($supportwidget == 1) ? 'checked' : ''; ?> /> Support shortcode in text widget</label>
					</td>
				</tr>
				
				<tr>
					<th>Scripts position</th>
					<td><label><input name='addjstofooter' type='checkbox' id='addjstofooter' <?php echo ($addjstofooter == 1) ? 'checked' : ''; ?> /> Add plugin js scripts to the footer (wp_footer hook must be implemented by the WordPress theme)</label>
					</td>
				</tr>
				
				<tr>
					<th>Popup Ajax</th>
					<td><label><input name='ajaxverifynonce' type='checkbox' id='ajaxverifynonce' <?php echo ($ajaxverifynonce == 1) ? 'checked' : ''; ?> /> Verify nonce in WordPress frontend Ajax</label>
					</td>
				</tr>
				
				<tr>
					<th>HTML content</th>
					<td><label><input name='sanitizehtmlcontent' type='checkbox' id='sanitizehtmlcontent' <?php echo ($sanitizehtmlcontent == 1) ? 'checked' : ''; ?> /> Sanitize HTML content</label>
					</td>
				</tr>
				
		        </table>
        
        	</li>
        	<li class="wonderplugin-tab wonderplugin-tab-horizontal-xml">
        	
        		<table class="form-table">
				
				<tr>
					<th>Send From</th>
					<td><label>Email: </label><input name='emailfrom' type='text' id='emailfrom' value='<?php if (isset($emailfrom)) echo $emailfrom; ?>' class='regular-text' /> <label>Name: </label><input name='emailfromname' type='text' id='emailfromname' value='<?php if (isset($emailfromname)) echo $emailfromname; ?>' class='regular-text' /></td>
				</tr>
				
				<tr>
					<th>SMTP Service</th>
					<td><label><input name='enablesmtp' type='checkbox' id='enablesmtp' <?php if (isset($enablesmtp) && $enablesmtp == 1)  echo 'checked'; ?> /> Use a third party SMTP service</label>
					</td>
				</tr>
				
				<tr>
					<th>SMTP Server</th>
					<td><label>Hostname: </label><input name='smtphostname' type='text' id='smtphostname' value='<?php if (isset($smtphostname)) echo $smtphostname; ?>' class='regular-text' />
					<label>Port: </label><input name='smtpport' type='number' id='smtpport' value='<?php if (isset($smtpport)) echo $smtpport; ?>' class='small-text' />
					<label>Secure: </label><select name="smtpsecure">
						  <option value="no" <?php if (isset($smtpsecure) && ($smtpsecure == 'no')) echo 'selected="selected"'; ?>>NO</option>
						  <option value="ssl" <?php if (isset($smtpsecure) && ($smtpsecure == 'ssl')) echo 'selected="selected"'; ?>>SSL</option>
						  <option value="tls" <?php if (isset($smtpsecure) && ($smtpsecure == 'tls')) echo 'selected="selected"'; ?>>TLS</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<th>SMTP Login</th>
					<td><label>Username: </label><input name='smtpusername' type='text' id='smtpusername' value='<?php if (isset($smtpusername)) echo $smtpusername; ?>' class='regular-text' />
					<label>Password: </label><input name='smtppassword' type='password' id='smtppassword' value='<?php if (isset($smtppassword)) echo $smtppassword; ?>' class='regular-text' /></td>
				</tr>
				
		        </table>
		        
        	</li>
        </ul>
        
        
        <p class="submit"><input type="submit" name="save-popup-options" id="save-popup-options" class="button button-primary" value="Save Changes"  /></p>
        
        </form>
        
		</div>
		<?php
	}

	function print_register() {
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
		
		<script>	
		function validateLicenseForm() {
			
			if (jQuery.trim(jQuery("#wonderplugin-popup-key").val()).length <= 0)
			{
				jQuery("#license-form-message").html("<p>Please enter your license key</p>").show();
				return false;
			}

			return true;
		}
		</script>

		<h2><?php _e( 'Register', 'wonderplugin_popup' ); ?></h2>
		<?php
				
		if (isset($_POST['save-popup-license']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-register'))
		{		
			unset($_POST['save-popup-license']);
	
			$ret = $this->controller->check_license($_POST);
			
			if ($ret['status'] == 'valid')
				echo '<div class="updated"><p>The key has been saved.</p><p>WordPress caches the update information. If you still see the message "Automatic update is unavailable for this plugin", please wait for some time, then click the below button "Force WordPress To Check For Plugin Updates".</p></div>';
			else if ($ret['status'] == 'expired')
				echo '<div class="error"><p>Your free upgrade period has expired, please renew your license.</p></div>';
			else if ($ret['status'] == 'invalid')
				echo '<div class="error"><p>The key is invalid.</p></div>';
			else if ($ret['status'] == 'abnormal')
				echo '<div class="error"><p>You have reached the maximum website limit of your license key. Please log into the membership area and upgrade to a higher license.</p></div>';
			else if ($ret['status'] == 'misuse')
				echo '<div class="error"><p>There is a possible misuse of your license key, please contact support@wonderplugin.com for more information.</p></div>';
			else if ($ret['status'] == 'timeout')
				echo '<div class="error"><p>The license server can not be reached, please try again later.</p></div>';
			else if ($ret['status'] == 'empty')
				echo '<div class="error"><p>Please enter your license key.</p></div>';
			else if (isset($ret['message']))
				echo '<div class="error"><p>' . $ret['message'] . '</p></div>';
		}
		else if (isset($_POST['deregister-popup-license']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-register'))
		{	
			$ret = $this->controller->deregister_license($_POST);
			
			if ($ret['status'] == 'success')
				echo '<div class="updated"><p>The key has been deregistered.</p></div>';
			else if ($ret['status'] == 'timeout')
				echo '<div class="error"><p>The license server can not be reached, please try again later.</p></div>';
			else if ($ret['status'] == 'empty')
				echo '<div class="error"><p>The license key is empty.</p></div>';
		}
		
		$settings = $this->controller->get_settings();
		$disableupdate = $settings['disableupdate'];
		
		$key = '';
		$info = $this->controller->get_plugin_info();
		if (!empty($info->key) && ($info->key_status == 'valid' || $info->key_status == 'expired'))
			$key = $info->key;
		
		?>
		
		<?php 
		if ($disableupdate == 1)
		{
			echo "<h3 style='padding-left:10px;'>The plugin version check and update is currently disabled. You can enable it in the Settings menu.</h3>";
		}
		else
		{
		?> <div style="padding-left:10px;padding-top:12px;"> <?php
			if (empty($key)) { ?>
				<form method="post" onsubmit="return validateLicenseForm()">
				<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-register'); ?>
				<div class="error" style="display:none;" id="license-form-message"></div>
				<table class="form-table">
				<tr>
					<th>Enter Your License Key:</th>
					<td><input name="wonderplugin-popup-key" type="text" id="wonderplugin-popup-key" value="" class="regular-text" /> <input type="submit" name="save-popup-license" id="save-popup-license" class="button button-primary" value="Register"  />
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
					<p><strong>By entering your license key and registering your website, you agree to the following terms:</strong></p>
					<ul style="list-style-type:square;margin-left:20px;">
						<li>The key is unique to your account. You may not distribute, give away, lend or re-sell it. We reserve the right to monitor levels of your key usage activity and take any necessary action in the event of abnormal usage being detected.</li>
						<li>By entering your license key and clicking the button "Register", your domain name, the plugin name and the key will be sent to the plugin website <a href="https://www.wonderplugin.com" target="_blank">https://www.wonderplugin.com</a> for verification and registration.</li>
						<li>You can view all your registered domain name(s) and plugin(s) by logging into <a href="https://www.wonderplugin.com/members/" target="_blank">WonderPlugin Members Area</a>, left menu "License Key and Register".</li>
						<li>For more information, please view <a href="https://www.wonderplugin.com/terms-of-use/" target="_blank">Terms of Use</a>.</li>
					</ul>
					<p style="margin:8px 0;">To find your license key, please log into <a href="https://www.wonderplugin.com/members/" target="_blank">WonderPlugin Members Area</a>, then click "License Key and Register" on the left menu.</p>
					<p style="margin:8px 0;">After registration, when there is a new version available and you are in the free upgrade period, you can directly upgrade the plugin in your WordPress dashboard. If you do not register, you can still upgrade the plugin manually: <a href="https://www.wonderplugin.com/wordpress-carousel-plugin/how-to-upgrade-to-a-new-version-without-losing-existing-work/" target="_blank">How to upgrade to a new version without losing existing work</a>.</p>
					</td>
				</tr>
				</table>
				</form>
			<?php } else { ?>
				<form method="post">
				<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-register'); ?>
				<p>You have entered your license key and this domain has been successfully registered. &nbsp;&nbsp;<input name="wonderplugin-popup-key" type="hidden" id="wonderplugin-popup-key" value="<?php echo esc_html($key); ?>" class="regular-text" /><input type="submit" name="deregister-popup-license" id="deregister-popup-license" class="button button-primary" value="Deregister"  /></p>
				</form>
				<?php if ($info->key_status == 'expired') { ?>
				<p><strong>Your free upgrade period has expired.</strong> To get upgrades, please <a href="https://www.wonderplugin.com/renew/" target="_blank">renew your license</a>.</p>
				<?php } ?>
			<?php } ?>
			</div>
		<?php } ?>
		
		<div style="padding-left:10px;padding-top:30px;">
		<a href="<?php echo admin_url('update-core.php?force-check=1'); ?>"><button class="button-primary">Force WordPress To Check For Plugin Updates</button></a>
		</div>
					
		<div style="padding-left:10px;padding-top:20px;">
        <ul style="list-style-type:square;font-size:16px;line-height:28px;margin-left:24px;">
		<li><a href="https://www.wonderplugin.com/how-to-upgrade-a-commercial-version-plugin-to-the-latest-version/" target="_blank">How to upgrade to the latest version</a></li>
	    <li><a href="https://www.wonderplugin.com/register-faq/" target="_blank">Where can I find my license key and other frequently asked questions</a></li>
	    </ul>
        </div>
	        
			</div>
			
			<?php
	}
		
	function print_items() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php _e( 'Manage Popups', 'wonderplugin_popup' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_popup_add_new'); ?>" class="add-new-h2"> <?php _e( 'New Popup', 'wonderplugin_popup' ); ?></a> <a href="#" class="add-new-h2 wonderplugin-clearcookies"> <?php _e( 'Clear All Popup Cookies', 'wonderplugin_popup' ); ?></a></h2>
				
		<form id="popup-list-table" method="post">
		<input type="hidden" name="page" value="<?php echo esc_html($_REQUEST['page']); ?>" />
		<?php 
		
		if ( !is_object($this->list_table) )
			$this->list_table = new WonderPlugin_Popup_List_Table($this);
		
		$this->process_actions();
		
		$this->list_table->list_data = $this->controller->get_list_data();
		$this->list_table->prepare_items();
		$this->list_table->views();
		$this->list_table->display();		
		?>								
        </form>
        
		</div>
		<?php
	}
	
	function print_localrecord() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php _e( 'Local Database', 'wonderplugin_popup' ); ?></h2>
		
		<p><b>Display subscrpition data saved in the Local database of this website</b></p>
			
		<?php 
		if ( !is_object($this->localrecord_table) )
			$this->localrecord_table = new WonderPlugin_Popup_Localrecord_Table($this);
				
		$this->process_localrecord_actions();
		
		$list = $this->controller->get_list_data();
		
		$ret = null;
		$id = -1;
		$daterange = "last30";
		$customstart = date("Y-m-d", strtotime("-30 days"));
		$customend = date('Y-m-d');
		$numperpage = 20;
		$page = 1;
		
		$totalpages = 1;
		
		if ( (isset($_POST['wonderplugin-popup-localrecordactions']) || isset($_POST['wonderplugin-popup-localrecorddatesubmit'])) && !empty($_POST['wonderplugin-popup-localrecordid']) && !empty($_POST['wonderplugin-popup-localrecorddaterange']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-localrecord'))
		{
			$id = sanitize_text_field($_POST['wonderplugin-popup-localrecordid']);
			$daterange = sanitize_text_field($_POST['wonderplugin-popup-localrecorddaterange']);
			$customstart = (!empty($_POST['wonderplugin-popup-localrecorddatestart'])) ? sanitize_text_field($_POST['wonderplugin-popup-localrecorddatestart']) : date("Y-m-d", strtotime("-30 days"));
			$customend = (!empty($_POST['wonderplugin-popup-localrecorddateend'])) ? sanitize_text_field($_POST['wonderplugin-popup-localrecorddateend']) : date('Y-m-d');
			$numperpage = sanitize_text_field($_POST['wonderplugin-popup-numperpage']);
			$page = (isset($_POST['wonderplugin-popup-page'])) ? sanitize_text_field($_POST['wonderplugin-popup-page']) : 1;
			$ret = $this->controller->get_localrecord_data($id, $daterange, $customstart, $customend, $numperpage, $page, false);
			
			if (!empty($ret) && $ret['success'] && !empty($ret['result']))
			{
				$totalpages = ceil($ret['result']['total'] / $numperpage);
				if ($ret['result']['total'] < $numperpage * ($page - 1))
					$page = 1;
			}
		}
		?>
		
		<div style="margin:12px 0px;">
		<form method="post">
		<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-localrecord'); ?>
		<span>PopUp: </span>
		<select name="wonderplugin-popup-localrecordid" id="wonderplugin-popup-localrecordid">
		<?php 
		foreach($list as $item)
			echo '<option value="' . $item['id'] . '" ' . (($item['id'] == $id) ? 'selected' : '') . ' >' . $item['id'] . ' : ' . $item['name'] . '</option>';
		?>
		</select>
		<span style="margin-left:12px;">Date Range: </span>
		<select name="wonderplugin-popup-localrecorddaterange" id="wonderplugin-popup-localrecorddaterange">
		<option value="thismonth" <?php if ($daterange == "thismonth") echo "selected"; ?>>This Month</option>
		<option value="lastmonth" <?php if ($daterange == "lastmonth") echo "selected"; ?>>Last Month</option>
		<option value="last30" <?php if ($daterange == "last30") echo "selected"; ?>>Last 30 Days</option>
		<option value="custom" <?php if ($daterange == "custom") echo "selected"; ?>>Custom</option>
		</select>
		<script>
		jQuery(document).ready(function() {
			jQuery('#wonderplugin-popup-localrecorddaterange').change(function() {
				jQuery('#wonderplugin-popup-localrecordselectdates').css({display: (jQuery(this).val() == 'custom' ? 'inline-block' : 'none') });
			});
		});
		</script>
		<div id="wonderplugin-popup-localrecordselectdates" style="display:<?php echo ($daterange == "custom") ? "inline-block" : "none"; ?>">
		<input name="wonderplugin-popup-localrecorddatestart" type="date" id="wonderplugin-popup-localrecorddatestart" value="<?php echo $datestart; ?>" />
		<input name="wonderplugin-popup-localrecorddateend" type="date" id="wonderplugin-popup-localrecorddateend" value="<?php echo $dateend; ?>" />
		</div>
		<span style="margin-left:12px;">Numbers Per Page: </span>
		<select name="wonderplugin-popup-numperpage" id="wonderplugin-popup-numperpage">
		<option value="10" <?php if ($numperpage == "10") echo "selected"; ?>>10</option>
		<option value="20" <?php if ($numperpage == "20") echo "selected"; ?>>20</option>
		<option value="50" <?php if ($numperpage == "50") echo "selected"; ?>>50</option>
		<option value="100" <?php if ($numperpage == "100") echo "selected"; ?>>100</option>
		</select>
		<?php if (!empty($ret) && $ret['success'] && !empty($ret['result'])) printf(_n('%s Record', '%s Records', $ret['result']['total']), $ret['result']['total']); ?>
		<?php if ($totalpages > 1) { ?>
		<select name="wonderplugin-popup-page" id="wonderplugin-popup-page">
		<?php for ($i = 1; $i <= $totalpages; $i++ ) { ?>
			<option value="<?php echo $i; ?>" <?php if ($page == $i) echo "selected"; ?>>Page <?php echo $i; ?></option>
		<?php } ?>
		</select>
		<?php } ?>
		<input name='wonderplugin-popup-localrecorddatesubmit' id='wonderplugin-popup-localrecorddatesubmit' class="button button-primary" type="submit" onclick="JavaScript:form.action='<?php echo admin_url('admin.php?page=wonderplugin_popup_show_localrecord'); ?>';" value="Show Record"></input>
		</div>
		</form>
		
		<div style="margin:24px 0px;">
		<?php 
		if (!empty($ret))
		{			
			if ( $ret['success'] )
			{
				if ( !empty($ret['result']) )
				{
					?>
					<form method="post" action="<?php echo admin_url('admin-post.php?action=wonderplugin_popup_export_csv'); ?>">
					<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-localrecord'); ?>
					<input type="hidden" name="wonderplugin-popup-localrecordid" value="<?php echo $ret['result']['id'];?>">
					<input type="hidden" name="wonderplugin-popup-localrecorddaterange" value="<?php echo $ret['result']['daterange'];?>">
					<input type="hidden" name="wonderplugin-popup-localrecorddatestart" value="<?php echo $ret['result']['customstart'];?>">
					<input type="hidden" name="wonderplugin-popup-localrecorddateend" value="<?php echo $ret['result']['customend'];?>">
					<input type="hidden" name="wonderplugin-popup-numperpage" value="<?php echo $ret['result']['numperpage'];?>">
					<input type="hidden" name="wonderplugin-popup-page" value="<?php echo $ret['result']['page'];?>">
					<?php
					echo '<p><input name="wonderplugin-popup-savetocsvsubmit" id="wonderplugin-popup-savetocsvsubmit" class="button button-primary" type="submit" value="Export To CSV"></input>';
					if ( WP_DEBUG )
						echo '<span style="margin-left:12px;">Warning: WP_DEBUG is enabled, the function "Export to CSV" may not work correctly. Please check your WordPress configuration file wp-config.php and change the WP_DEBUG to false.</span>';
					echo '</p>';	
					?>
					</form>
					<?php
				}
				else
				{
					echo '<h3>No record found. Please make sure you have enabled the option "Save to the Local Database of this WordPress Website" in the popup editor, Email Service tab.</h3>';
				}
			}
			else
			{
				echo '<h3>' . $ret['message'] . '<h3>';
			}
		}
		?>
		</div>
		
		
		<?php if (!empty($ret) && $ret['success'] && !empty($ret['result'])) { ?>
		
		<form method="post">
		<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-localrecord'); ?>
		<input type="hidden" name="wonderplugin-popup-localrecordid" value="<?php echo $ret['result']['id'];?>">
		<input type="hidden" name="wonderplugin-popup-localrecorddaterange" value="<?php echo $ret['result']['daterange'];?>">
		<input type="hidden" name="wonderplugin-popup-localrecorddatestart" value="<?php echo $ret['result']['customstart'];?>">
		<input type="hidden" name="wonderplugin-popup-localrecorddateend" value="<?php echo $ret['result']['customend'];?>">
		<input type="hidden" name="wonderplugin-popup-numperpage" value="<?php echo $ret['result']['numperpage'];?>">
		<input type="hidden" name="wonderplugin-popup-page" value="<?php echo $ret['result']['page'];?>">
		<input type="hidden" name="wonderplugin-popup-localrecordactions" value="1">
		<?php 
		$this->localrecord_table->set_data($ret['result']['header'], $ret['result']['data']);
			
		$this->localrecord_table->prepare_items();
		$this->localrecord_table->display();
		?>
		<?php } ?>
		</form>
		
		</div>
		<?php
	}
	
	function process_localrecord_actions() {
		
		if (!isset($_REQUEST['_wpnonce']) || (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->localrecord_table->_args['plural'])))
			return;
					
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'delete'))) && isset( $_REQUEST['itemid'] ) )
		{
			$deleted = 0;
				
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->delete_localrecord_item($id);
						if ($ret > 0)
							$deleted += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$deleted = $this->controller->delete_localrecord_item( $_REQUEST['itemid'] );
			}
				
			if ($deleted > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d record deleted.', '%d records deleted.', $deleted), $deleted );
				echo '</p></div>';
			}
		}
	}
	
	function print_analytics() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php _e( 'Analytics Report', 'wonderplugin_popup' ); ?></h2>
		
		<?php
		
		$daterange = "last30";
		$datestart = date("Y-m-d", strtotime("-30 days"));
		$dateend = date('Y-m-d');
		
		if ( isset($_POST['wonderplugin-popup-analyticsdatesubmit']) && !empty($_POST['wonderplugin-popup-analyticsdaterange']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-analytics'))
		{
			$daterange = sanitize_text_field($_POST['wonderplugin-popup-analyticsdaterange']);
			switch ($daterange)
			{
				case 'thismonth':
					$datestart = date('Y-m-01');
					$dateend = date('Y-m-d');
					break;
				case 'lastmonth':
					$datestart = date("Y-m-d", strtotime("first day of previous month"));
					$dateend = date('Y-m-d', strtotime("last day of previous month"));
					break;
				case 'last30':
					$datestart = date("Y-m-d", strtotime("-30 days"));
					$dateend = date('Y-m-d');
					break;
				case 'custom':
					if ( !empty($_POST['wonderplugin-popup-analyticsdatestart']) )
						$datestart = sanitize_text_field($_POST['wonderplugin-popup-analyticsdatestart']);
					if ( !empty($_POST['wonderplugin-popup-analyticsdateend']) )
						$dateend = sanitize_text_field($_POST['wonderplugin-popup-analyticsdateend']);
					break;
			}
		}
		?>
		<div style="margin:12px 0px;">
		<form method="post">
		<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-analytics'); ?>
		<span>Select date range:</span>
		<select name="wonderplugin-popup-analyticsdaterange" id="wonderplugin-popup-analyticsdaterange">
		<option value="thismonth" <?php if ($daterange == "thismonth") echo "selected"; ?>>This Month</option>
		<option value="lastmonth" <?php if ($daterange == "lastmonth") echo "selected"; ?>>Last Month</option>
		<option value="last30" <?php if ($daterange == "last30") echo "selected"; ?>>Last 30 Days</option>
		<option value="custom" <?php if ($daterange == "custom") echo "selected"; ?>>Custom</option>
		</select>
		<script>
		jQuery(document).ready(function() {
			jQuery('#wonderplugin-popup-analyticsdaterange').change(function() {
				jQuery('#wonderplugin-popup-analyticsselectdates').css({display: (jQuery(this).val() == 'custom' ? 'inline-block' : 'none') });
			});
		});
		</script>
		<div id="wonderplugin-popup-analyticsselectdates" style="display:<?php echo ($daterange == "custom") ? "inline-block" : "none"; ?>">
		<input name="wonderplugin-popup-analyticsdatestart" type="date" id="wonderplugin-popup-analyticsdatestart" value="<?php echo $datestart; ?>" />
		<input name="wonderplugin-popup-analyticsdateend" type="date" id="wonderplugin-popup-analyticsdateend" value="<?php echo $dateend; ?>" />
		</div>
		<input name='wonderplugin-popup-analyticsdatesubmit' id='wonderplugin-popup-analyticsdatesubmit' class="button button-primary" type="submit" value="Apply"></input>
		</form>
		</div>
		
		<div style="margin:24px 0px;">
		<?php 
		
			$ret = $this->controller->get_analytics_data($datestart, $dateend);

			if ( $ret['success'] )
			{
				if ( !empty($ret['data']) )
				{
					if ( !is_object($this->analytics_table) )
						$this->analytics_table = new WonderPlugin_Popup_Analytics_Table($this);

					$display_data = array();
					foreach($ret['data'] as $data )
					{
						$display_data[] = array(
							'popupid' 		=> $data['popupid'],
							'popupname' 	=> $data['popupname'],
							'status'		=> ($data['status'] ? 'Enabled': 'Disabled'),
							'showevent'		=> (empty($data['showevent']) ? 0 : $data['showevent']),
							'actionevent' 	=> (empty($data['actionevent']) ? 0 : $data['actionevent']),
							'actionrate'	=> ((empty($data['showevent']) || empty($data['actionevent'])) ? 0 : round($data['actionevent'] * 100 / $data['showevent']))
							);
					}
					
					$this->analytics_table->list_data = $display_data;
					$this->analytics_table->prepare_items();
					$this->analytics_table->display();
				}
				else
				{
					echo '<h3>There is no analytics data. Please make sure you have enabled the option "Local Analytics" in the popup editor, menu Analytics.<h3>';
				}
			}
			else
			{
				echo '<h3>' . $ret['message'] . '<h3>';
			}
		?>
		</div>
		
		</div>
		<?php
	}
	
	function print_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) || !is_numeric( $_REQUEST['itemid'] ) )
			return;
		
		
		$data = $this->controller->get_item_data( $_REQUEST['itemid'] );
		if ( empty($data) )
			return;
		
		$type = $data['type'];
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
					
		<h2><?php _e( 'Preview Popup', 'wonderplugin_popup' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_popup_edit_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'Edit Popup', 'wonderplugin_popup' ); ?>  </a>	<a href="#" class="add-new-h2 wonderplugin-clearcookies"> <?php _e( 'Clear All Popup Cookies', 'wonderplugin_popup' ); ?></a>	
		</h2>
		
		<?php 
		if ( $data['status'] != 1)
		{
			echo '<div class="error"><p style="text-align:center;">The popup is currently paused.</p></div>';
		}
		else
		{
			if ( $data['type'] == "embed") {
				echo '<div class="updated"><p style="text-align:center;">To embed the form into a post, a page or a sidebar text widget, use shortcode: ' . esc_attr('[wonderplugin_popup id=' . $_REQUEST['itemid'] . ']') . '</p></div>';
				echo '<div class="updated"><p style="text-align:center;">To embed the form into a template, use php code: ' . esc_attr('<?php echo do_shortcode("[wonderplugin_popup id=' . $_REQUEST['itemid'] . ']") ?>') . '</p></div>';
			}
			else {
				echo '<div class="updated"><p style="text-align:center;">The popup has been activated on the website according to the defined display rules.</p></div>';
				echo '<div class="updated"><p style="text-align:center;">The default retargeting rules will prevent the popup from appearing again. Please clear caches of your web browser when retesting the popup on your webpage.</p></div>';
				echo '<div class="updated"><p style="text-align:center;"> <a href="#" class="wppopup" data-popupid=' . $_REQUEST['itemid'] . '>Show the Popup</a></p></div>';
				echo '<div class="updated"><p style="text-align:center;"> To create a link that opens the popup on clicking, use HTML code: <span style="color:#ff0000;">' . esc_html('<a href="#" class="wppopup" data-popupid=' . $_REQUEST['itemid'] . '>Show Popup</a>') . '</span>.</p></div>';
			}
			
			if (WONDERPLUGIN_POPUP_VERSION_TYPE !== "C")
				echo '<div class="updated"><p style="text-align:center;">To remove the Free Version watermark, please <a href="https://www.wonderplugin.com/wordpress-popup/order/" target="_blank">Upgrade to Commercial Version</a>.</p></div>';
		}
		
		echo $this->controller->generate_body_code( $_REQUEST['itemid'], true ); 
		?>
				
		</div>
		<?php
	}
	
	function process_actions()
	{
		if (!isset($_REQUEST['_wpnonce']) || (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->list_table->_args['plural']) && !wp_verify_nonce($_REQUEST['_wpnonce'], 'wonderplugin-list-table-nonce')))
			return;
			
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'trash')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'trash'))) && isset( $_REQUEST['itemid'] ) )
		{
			$trashed = 0;
		
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->trash_item($id);
						if ($ret > 0)
							$trashed += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$trashed = $this->controller->trash_item( $_REQUEST['itemid'] );
			}
		
			if ($trashed > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d popup moved to the trash.', '%d popups moved to the trash.', $trashed), $trashed );
				echo '</p></div>';
			}
		}
		
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'restore')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'restore'))) && isset( $_REQUEST['itemid'] ) )
		{
			$restored = 0;
		
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->restore_item($id);
						if ($ret > 0)
							$restored += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$restored = $this->controller->restore_item( $_REQUEST['itemid'] );
			}
		
			if ($restored > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d popup restored.', '%d popups restored.', $restored), $restored );
				echo '</p></div>';
			}
		}
		
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'delete'))) && isset( $_REQUEST['itemid'] ) )
		{
			$deleted = 0;
				
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->delete_item($id);
						if ($ret > 0)
							$deleted += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$deleted = $this->controller->delete_item( $_REQUEST['itemid'] );
			}
				
			if ($deleted > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d popup deleted.', '%d popups deleted.', $deleted), $deleted );
				echo '</p></div>';
			}
		}
		
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'clone')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'clone'))) && isset( $_REQUEST['itemid'] ) && is_numeric( $_REQUEST['itemid'] ))
		{
			$cloned_id = $this->controller->clone_item( $_REQUEST['itemid'] );
			if ($cloned_id > 0)
			{
				echo '<div class="updated"><p>';
				printf( 'New popup created with ID: %d', $cloned_id );
				echo '</p></div>';
			}
			else
			{
				echo '<div class="error"><p>';
				printf( 'The popup cannot be cloned.' );
				echo '</p></div>';
			}
		}
		
		if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'disable') && isset( $_REQUEST['itemid'] ) && is_numeric( $_REQUEST['itemid'] ) )
		{
			$ret = $this->controller->disable_item( $_REQUEST['itemid'] );
			if ( $ret )
				echo '<div class="updated"><p>The popup has been disabled.</p></div>';
			else
				echo '<div class="error"><p>The popup cannot be disabled.</p></div>';
		}
		else if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'enable') && isset( $_REQUEST['itemid'] ) && is_numeric( $_REQUEST['itemid'] ) )
		{
			$ret = $this->controller->enable_item( $_REQUEST['itemid'] );
			if ( $ret )
				echo '<div class="updated"><p>The popup has been enabled.</p></div>';
			else
				echo '<div class="error"><p>The popup cannot be enabled.</p></div>';
		}
		
	}
	
	function get_rules($ruletype, $post) {
		
		$rules = array();
		
		for ($i = 0; ; $i++)
		{
			if ( !isset($post['wonderplugin-popup-' . $ruletype . 'ruleaction-' . $i]) || !isset($post['wonderplugin-popup-' . $ruletype . 'rule-' . $i]))
				break;
					
				$rule = array();
				$rule['action'] = $post['wonderplugin-popup-' . $ruletype . 'ruleaction-' . $i];
				$rule['rule'] = $post['wonderplugin-popup-' . $ruletype . 'rule-' . $i];
					
				if ( array_key_exists('wonderplugin-popup-' . $ruletype . 'param0-' . $i, $post) )
				{
				$rule['param0'] = $post['wonderplugin-popup-' . $ruletype . 'param0-' . $i];
				unset($post['wonderplugin-popup-' . $ruletype . 'param0-' . $i]);
			}
				
			if ( array_key_exists('wonderplugin-popup-' . $ruletype . 'param1-' . $i, $post) )
			{
			$rule['param1'] = $post['wonderplugin-popup-' . $ruletype . 'param1-' . $i];
			unset($post['wonderplugin-popup-' . $ruletype . 'param1-' . $i]);
			}
				
			unset($post['wonderplugin-popup-' . $ruletype . 'ruleaction-' . $i]);
			unset($post['wonderplugin-popup-' . $ruletype . 'rule-' . $i]);
				
			$rules[] = $rule;
		}
		
		return $rules;
	}
	
	function prepare_save_post($post) {
		
		unset($post['onderplugin-popup-save']);
		unset($post['wonderplugin-popup-mailchimpapikeysave']);
		unset($post['wonderplugin-popup-getresponseapikeysave']);
		unset($post['wonderplugin-popup-getresponsev3apikeysave']);
		unset($post['wonderplugin-popup-campaignmonitorapikeysave']);
		unset($post['wonderplugin-popup-constantcontactapikeysave']);
		unset($post['wonderplugin-popup-icontactsave']);
		unset($post['wonderplugin-popup-activecampaignapikeysave']);
		unset($post['wonderplugin-popup-infusionsoftapikeysave']);
		unset($post['wonderplugin-popup-mailpoetsave']);
		unset($post['wonderplugin-popup-mailpoet3save']);
		
		if (!current_user_can('manage_options'))
		{
			unset($post['wonderplugin-popup-customjs']);
		}
				
		$sanitizehtmlcontent = get_option( 'wonderplugin_popup_sanitizehtmlcontent', 1 );
		if ($sanitizehtmlcontent == 1)
		{
			add_filter('safe_style_css', 'wonderplugin_popup_css_allow');
			add_filter('wp_kses_allowed_html', 'wonderplugin_popup_tags_allow', 'post');
			
			foreach ($post as $key => &$value)
			{
				if (is_array($value))
				{
					foreach($value as $arraykey => &$arrayvalue)
					{
						$arrayvalue = wp_kses_post($arrayvalue);
					}
				}
				else
				{
					$value = wp_kses_post($value);
				}
			}
			
			remove_filter('wp_kses_allowed_html', 'wonderplugin_popup_tags_allow', 'post');
			remove_filter('safe_style_css', 'wonderplugin_popup_css_allow');
		}
		
		$data = array();
		
		$data['displaypagerules'] = json_encode( $this->get_rules('page', $post) );
		$data['displaydevicerules'] = json_encode( $this->get_rules('device', $post) );
		if ($this->controller->multilingual)
			$data['displaylangrules'] = json_encode( $this->get_rules('lang', $post) );
						
		$bool_options = array('autoclose', 'uniquevideoiframeid', 'removeinlinecss', 'enableretarget', 'loggedinonly', 'status', 'fullscreen', 'overlayclose', 'showclose', 'closeshowshadow', 'showprivacy', 'showribbon', 'showclosetip', 'showgrecaptcha', 'showemail', 'showname', 'showfirstname', 'showlastname', 'showcompany', 'showphone', 'showzip', 'showaction', 'showcancel', 'cancelastext', 'videoautoplay', 'videoautoclose', 'videomuted', 'videocontrols', 'videonodownload',
				'showterms', 'termsrequired', 'showmessage', 'displayonpageload', 'displayonpagescrollpercent', 'displayonpagescrollpixels', 'displayonpagescrollcssselector', 'displayonuserinactivity', 'displayonclosepage', 'closeafterbutton', 'redirectafterbutton',
				'showprivacyconsent', 'privacyconsentrequired',
				'hidebarnotshowafteraction', 'barfloat',
				'displayloading', 'displaydetailedmessage',
				'emailautoresponder', 'emailnotify', 'savetolocal', 'mailchimpdoubleoptin', 'icontactdoubleoptin', 'infusionsoftdoubleoptin', 'getresponseautoresponder', 'getresponsev3autoresponder',
				'mailpoet3sendconfirmationemail', 'mailpoet3schedulewelcomeemail',
				'enablegoogleanalytics', 'enablelocalanalytics');
		
		foreach ($bool_options as $option)
		{
			$data[$option] = 0;
		}
		
		foreach ($post as $key => $value)
		{
			$names = explode('-', $key);
			$name = array_pop( $names );
			
			if (is_string($value))
			{
				$data[$name] = stripslashes($value);
			}
		}
		
		return $data;
	}
	
	function print_add_new() {
				
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php _e( 'New Popup', 'wonderplugin_popup' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_popup_show_items'); ?>" class="add-new-h2"> <?php _e( 'Manage Popups', 'wonderplugin_popup' ); ?>  </a> <a href="#" class="add-new-h2 wonderplugin-clearcookies"> <?php _e( 'Clear All Popup Cookies', 'wonderplugin_popup' ); ?></a></h2>
		
		<?php 
		
		$display_type = array(
			"lightbox" => array("Lightbox", "Create a lightbox popup that displays in the center of the web browser"),
			"embed" => array("Embed", "Embed into pages, posts or sidebar widgets"),
			"slidein" => array("Slide In", "Create a popup that slides in from corner"),
			"bar" => array("Notification Bar", "Create a bar that displays on top or bottom of the web page")
		);
		
		if ( !empty($_GET['type']) && array_key_exists($_GET['type'], $display_type) )
		{
			$this->creator = new WonderPlugin_Popup_Creator($this);
			echo $this->creator->render( -1, array(
					"type" => $_GET['type']
				), null);
		}
		else
		{
		?>
			<div style="text-align:center;">
			<p style="font-size:24px;">Select A Display Type</p>
			
			<?php 
			foreach ($display_type as $key => $value)
			{
				$type_disabled = (WONDERPLUGIN_POPUP_VERSION_TYPE == 'L' && $key != 'lightbox' && $key != 'embed');
			?>
				<div class="wonderplugin-popup-typeitem<?php if ($type_disabled) echo " wonderplugin-skin-commercial-only";?>">
					<div class="wonderplugin-popup-typeimage">
					<a href="<?php echo admin_url('admin.php?page=wonderplugin_popup_add_new') . '&type=' . $key; ?>"><img src="<?php echo WONDERPLUGIN_POPUP_URL . 'images/type-' . $key . '.jpg'; ?>" /></a>
					<?php if ($type_disabled) { ?>
						<div class="wonderplugin-skin-commercial-lock"></div>
						<div class="wonderplugin-skin-commercial-textblock"><div class="wonderplugin-skin-commercial-text"><p>This popup type is only available in Commercial Version.</p><p><a href="https://www.wonderplugin.com/wordpress-popup/?ref=lite" target="_blank">Upgrade to Commercial Version</a></p><p><a href="https://www.wonderplugin.com/wordpress-popup/?ref=lite" target="_blank">View Demos Created with Commercial Version</a></p></div></div>
					<?php } ?>
					</div>
					<div class="wonderplugin-popup-typetext">
					<p class="wonderplugin-popup-typeitem-title"><?php echo $value[0]; ?></p>
					<p class="wonderplugin-popup-typeitem-description"><?php echo $value[1]; ?></p>
					</div>
				</div>
			<?php
			}
			?>
			<div style="clear:both;"></div>
			</div>
		<?php	
		}		
	}
	
	function print_edit_item()
	{	
		$options = array();
		
		$savedid = -1;
		if ( ( !empty($_POST['wonderplugin-popup-save']) 
			|| !empty($_POST['wonderplugin-popup-mailchimpapikeysave'])
			|| !empty($_POST['wonderplugin-popup-getresponseapikeysave'])
			|| !empty($_POST['wonderplugin-popup-getresponsev3apikeysave'])
			|| !empty($_POST['wonderplugin-popup-campaignmonitorapikeysave'])
			|| !empty($_POST['wonderplugin-popup-constantcontactapikeysave']) 
			|| !empty($_POST['wonderplugin-popup-icontactsave']) 
			|| !empty($_POST['wonderplugin-popup-activecampaignapikeysave'])
			|| !empty($_POST['wonderplugin-popup-mailpoetsave'])
			|| !empty($_POST['wonderplugin-popup-mailpoet3save'])
			|| !empty($_POST['wonderplugin-popup-infusionsoftapikeysave']) )
			&& check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-saveform') )
		{
			// MailChimp
			if (!empty($_POST['wonderplugin-popup-mailchimpapikeysave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-mailchimpapikey']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'mailchimp',
							'serviceaction'	=> 'getlists',
							'servicekey'	=> sanitize_text_field($_POST['wonderplugin-popup-mailchimpapikey'])
					));
				
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-mailchimpapikey'] = sanitize_text_field($_POST['wonderplugin-popup-mailchimpapikey']);
						$_POST['wonderplugin-popup-mailchimplists'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
						
						unset($_POST['wonderplugin-popup-mailchimpapikey']);
						unset($_POST['wonderplugin-popup-mailchimplists']);
					}
				}
				else
				{		
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-mailchimpapikey']);
					unset($_POST['wonderplugin-popup-mailchimplists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-getresponseapikeysave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-getresponseapikey']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'getresponse',
							'serviceaction'	=> 'getcampaigns',
							'servicekey'	=> sanitize_text_field($_POST['wonderplugin-popup-getresponseapikey'])
					));
				
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-getresponseapikey'] = sanitize_text_field($_POST['wonderplugin-popup-getresponseapikey']);
						$_POST['wonderplugin-popup-getresponsecampaigns'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
						
						unset($_POST['wonderplugin-popup-getresponseapikey']);
						unset($_POST['wonderplugin-popup-getresponsecampaigns']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-getresponseapikey']);
					unset($_POST['wonderplugin-popup-getresponsecampaigns']);
				}				
			}
			else if (!empty($_POST['wonderplugin-popup-getresponsev3apikeysave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-getresponsev3apikey']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'getresponsev3',
							'serviceaction'	=> 'getcampaigns',
							'servicekey'	=> sanitize_text_field($_POST['wonderplugin-popup-getresponsev3apikey'])
					));
				
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-getresponsev3apikey'] = sanitize_text_field($_POST['wonderplugin-popup-getresponsev3apikey']);
						$_POST['wonderplugin-popup-getresponsev3campaigns'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
						
						unset($_POST['wonderplugin-popup-getresponsev3apikey']);
						unset($_POST['wonderplugin-popup-getresponsev3campaigns']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-getresponsev3apikey']);
					unset($_POST['wonderplugin-popup-getresponsev3campaigns']);
				}				
			}
			else if (!empty($_POST['wonderplugin-popup-campaignmonitorapikeysave']))
			{
				if ( !empty($_POST['wonderplugin-popup-campaignmonitorapikey']) && !empty($_POST['wonderplugin-popup-campaignmonitorclientid']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'campaignmonitor',
							'serviceaction'	=> 'getlists',
							'servicekey'	=> sanitize_text_field($_POST['wonderplugin-popup-campaignmonitorapikey']),
							'clientid'		=> sanitize_text_field($_POST['wonderplugin-popup-campaignmonitorclientid'])
					));
						
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-campaignmonitorapikey'] = sanitize_text_field($_POST['wonderplugin-popup-campaignmonitorapikey']);
						$_POST['wonderplugin-popup-campaignmonitorclientid'] = sanitize_text_field($_POST['wonderplugin-popup-campaignmonitorclientid']);
						$_POST['wonderplugin-popup-campaignmonitorlists'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
							
						unset($_POST['wonderplugin-popup-campaignmonitorapikey']);
						unset($_POST['wonderplugin-popup-campaignmonitorclientid']);
						unset($_POST['wonderplugin-popup-campaignmonitorlists']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-campaignmonitorapikey']);
					unset($_POST['wonderplugin-popup-campaignmonitorclientid']);
					unset($_POST['wonderplugin-popup-campaignmonitorlists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-constantcontactapikeysave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-constantcontactapikey']) && !empty($_POST['wonderplugin-popup-constantcontactaccesstoken']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'constantcontact',
							'serviceaction'	=> 'getlists',
							'servicekey'	=> sanitize_text_field($_POST['wonderplugin-popup-constantcontactapikey']),
							'accesstoken'	=> sanitize_text_field($_POST['wonderplugin-popup-constantcontactaccesstoken'])
					));
						
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-constantcontactapikey'] = sanitize_text_field($_POST['wonderplugin-popup-constantcontactapikey']);
						$_POST['wonderplugin-popup-constantcontactaccesstoken'] = sanitize_text_field($_POST['wonderplugin-popup-constantcontactaccesstoken']);
						$_POST['wonderplugin-popup-constantcontactlists'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
				
						unset($_POST['wonderplugin-popup-constantcontactapikey']);
						unset($_POST['wonderplugin-popup-constantcontactaccesstoken']);
						unset($_POST['wonderplugin-popup-constantcontactlists']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-constantcontactapikey']);
					unset($_POST['wonderplugin-popup-constantcontactaccesstoken']);
					unset($_POST['wonderplugin-popup-constantcontactlists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-icontactsave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-icontactusername']) && !empty($_POST['wonderplugin-popup-icontactappid']) && !empty($_POST['wonderplugin-popup-icontactapppassword']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'icontact',
							'serviceaction'	=> 'getlists',
							'username'	=> sanitize_text_field($_POST['wonderplugin-popup-icontactusername']),
							'appid'	=> sanitize_text_field($_POST['wonderplugin-popup-icontactappid']),
							'apppassword'	=> sanitize_text_field($_POST['wonderplugin-popup-icontactapppassword'])
					));
						
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-icontactusername'] = sanitize_text_field($_POST['wonderplugin-popup-icontactusername']);
						$_POST['wonderplugin-popup-icontactappid'] = sanitize_text_field($_POST['wonderplugin-popup-icontactappid']);
						$_POST['wonderplugin-popup-icontactapppassword'] = sanitize_text_field($_POST['wonderplugin-popup-icontactapppassword']);
						$_POST['wonderplugin-popup-icontactlists'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
							
						unset($_POST['wonderplugin-popup-icontactusername']);
						unset($_POST['wonderplugin-popup-icontactappid']);
						unset($_POST['wonderplugin-popup-icontactapppassword']);
						unset($_POST['wonderplugin-popup-icontactlists']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-icontactusername']);
					unset($_POST['wonderplugin-popup-icontactappid']);
					unset($_POST['wonderplugin-popup-icontactapppassword']);
					unset($_POST['wonderplugin-popup-icontactlists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-activecampaignapikeysave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-activecampaignapiurl']) && !empty($_POST['wonderplugin-popup-activecampaignapikey']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'activecampaign',
							'serviceaction'	=> 'getlists',
							'apiurl'	=> sanitize_text_field($_POST['wonderplugin-popup-activecampaignapiurl']),
							'apikey'	=> sanitize_text_field($_POST['wonderplugin-popup-activecampaignapikey'])
					));
			
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-activecampaignapiurl'] = sanitize_text_field($_POST['wonderplugin-popup-activecampaignapiurl']);
						$_POST['wonderplugin-popup-activecampaignapikey'] = sanitize_text_field($_POST['wonderplugin-popup-activecampaignapikey']);
						$_POST['wonderplugin-popup-activecampaignlists'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
			
						unset($_POST['wonderplugin-popup-activecampaignapiurl']);
						unset($_POST['wonderplugin-popup-activecampaignapikey']);
						unset($_POST['wonderplugin-popup-activecampaignlists']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-activecampaignapiurl']);
					unset($_POST['wonderplugin-popup-activecampaignapikey']);
					unset($_POST['wonderplugin-popup-activecampaignlists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-mailpoetsave']) )
			{
				$service_return = $this->controller->service_connect(array(
						'service'		=> 'mailpoet',
						'serviceaction'	=> 'getlists'
				));
					
				if ($service_return['success'])
				{
					$_POST['wonderplugin-popup-mailpoetlists'] = $service_return['data'];
				}
				else
				{
					$options['servicemessage'] = $service_return['message'];

					unset($_POST['wonderplugin-popup-mailpoetlists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-mailpoet3save']) )
			{
				$service_return = $this->controller->service_connect(array(
						'service'		=> 'mailpoet3',
						'serviceaction'	=> 'getlists'
				));
					
				if ($service_return['success'])
				{
					$_POST['wonderplugin-popup-mailpoet3lists'] = $service_return['data'];
				}
				else
				{
					$options['servicemessage'] = $service_return['message'];
			
					unset($_POST['wonderplugin-popup-mailpoet3lists']);
				}
			}
			else if (!empty($_POST['wonderplugin-popup-infusionsoftapikeysave']) )
			{
				if ( !empty($_POST['wonderplugin-popup-infusionsoftsubdomain']) && !empty($_POST['wonderplugin-popup-infusionsoftapikey']) )
				{
					$service_return = $this->controller->service_connect(array(
							'service'		=> 'infusionsoft',
							'serviceaction'	=> 'getlists',
							'subdomain'	=> sanitize_text_field($_POST['wonderplugin-popup-infusionsoftsubdomain']),
							'apikey'	=> sanitize_text_field($_POST['wonderplugin-popup-infusionsoftapikey'])
					));
						
					if ($service_return['success'])
					{
						$_POST['wonderplugin-popup-infusionsoftsubdomain'] = sanitize_text_field($_POST['wonderplugin-popup-infusionsoftsubdomain']);
						$_POST['wonderplugin-popup-infusionsoftapikey'] = sanitize_text_field($_POST['wonderplugin-popup-infusionsoftapikey']);
						$_POST['wonderplugin-popup-infusionsoftlists'] = $service_return['data'];
					}
					else
					{
						$options['servicemessage'] = $service_return['message'];
							
						unset($_POST['wonderplugin-popup-infusionsoftsubdomain']);
						unset($_POST['wonderplugin-popup-infusionsoftapikey']);
						unset($_POST['wonderplugin-popup-infusionsoftlists']);
					}
				}
				else
				{
					$options['servicemessage'] = 'Please fill in the required field.';
					unset($_POST['wonderplugin-popup-infusionsoftsubdomain']);
					unset($_POST['wonderplugin-popup-infusionsoftapikey']);
					unset($_POST['wonderplugin-popup-infusionsoftlists']);
				}
			}
			
			// save options
			$data = $this->prepare_save_post($_POST);
			$ret = $this->controller->save_item($data);
				
			if (isset($ret['success']) && $ret['success'] && isset($ret['id']) && $ret['id'] >= 0)
				$savedid = $ret['id'];
			
			// keep creator state
			$options['keepstate'] = '1';
		}
		
		if ( isset( $_REQUEST['itemid'] ) && is_numeric( $_REQUEST['itemid'] ) )
			$itemid = $_REQUEST['itemid'];
		else if ( $savedid >= 0 )
			$itemid = $savedid;
		else
			return;
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-popup" class="icon32"><br /></div>
			
		<h2><?php _e( 'Edit Popup', 'wonderplugin_popup' ); ?> - ID <?php echo $itemid; ?><a href="<?php echo admin_url('admin.php?page=wonderplugin_popup_show_items'); ?>" class="add-new-h2"> <?php _e( 'Manage Popups', 'wonderplugin_popup' ); ?>  </a> <a target="_blank" href="<?php echo admin_url('admin.php?page=wonderplugin_popup_show_item') . '&itemid=' . $itemid; ?>" class="add-new-h2"> <?php _e( 'Preview Popup', 'wonderplugin_popup' ); ?>  </a> <a href="#" class="add-new-h2 wonderplugin-clearcookies"> <?php _e( 'Clear All Popup Cookies', 'wonderplugin_popup' ); ?></a></h2>
		
		<?php 
		
		if ( !empty($_POST['wonderplugin-popup-save']) )
		{
			if ($savedid >= 0)
			{
				echo "<div class='updated'><p>The popup has been saved and published.</p></div>";
				
				$data = $this->controller->get_item_data( $savedid );
				if ( isset($data['type']) )
				{
					if ( $data['status'] != 1)
					{
						echo '<div class="error"><p>The popup is currently paused.</p></div>';
					}
					else
					{
						if ( $data['type'] == "embed") {
							echo '<div class="updated"><p>To embed the form into a post, a page or a sidebar text widget, use shortcode: ' . esc_attr('[wonderplugin_popup id=' . $savedid . ']') . '</p></div>';
							echo '<div class="updated"><p>To embed the form into a template, use php code: ' . esc_attr('<?php echo do_shortcode("[wonderplugin_popup id=' . $savedid . ']") ?>') . '</p></div>';
						}
						else {
							echo '<div class="updated"><p>The lightbox popup has been activated on the website according to the defined display rules.</p></div>';
						}
					}
				}
			}
			else
			{
				echo "<div class='error'><p>The popup can not be saved.</p></div>";
				echo "<div class='error'><p>Error Message: " . ((isset($ret['message'])) ? $ret['message'] : "") . "</p></div>";
			}
		}
		
		$this->creator = new WonderPlugin_Popup_Creator($this);
		echo $this->creator->render( $itemid, $this->controller->get_item_data( $itemid ), $options );
	}
	
	function import_export()
	{
		?>
		<div class="wrap">
			<div id="icon-wonderplugin-popup" class="icon32">
				<br />
			</div>

			<h2>
				<?php _e( 'Import/Export', 'wonderplugin_popup' ); ?>
			</h2>

			<p>
				<b>This function only imports/exports popup configurations.
					It does not import/export the media files.</b>
			</p>

			<p>The plugin uses WordPress Media Library to manage media files.
				Please transfer your WordPress Media Library to the new site after
				importing/exporting the popup.</p>

			<ul class="wonderplugin-tab-buttons-horizontal-xml"
				id="wp-tools-toolbar"
				data-panelsid="wonderplugin-popup-display-panels">
				<li
					class="wonderplugin-tab-button-horizontal-xml wonderplugin-tab-button-horizontal-xml-selected"><span
					class="dashicons dashicons-download" style="margin-right: 8px;"></span>
					<?php _e( 'Import', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button-horizontal-xml"><span
					class="dashicons dashicons-upload" style="margin-right: 8px;"></span>
					<?php _e( 'Export', 'wonderplugin_popup' ); ?></li>
					
				<li class="wonderplugin-tab-button-horizontal-xml"><span class="dashicons dashicons-search" style="margin-right:8px;"></span><?php _e( 'Search and Replace', 'wonderplugin_popup' ); ?></li>
			</ul>

			<?php 
			$data = $this->controller->get_list_data(true);
			?>
			<ul class="wonderplugin-tabs-horizontal-xml"
				id="wonderplugin-popup-display-panels">
				<li
					class="wonderplugin-tab wonderplugin-tab-horizontal-xml wonderplugin-tab-horizontal-xml-selected">

					<?php 
					if (isset($_POST['wp-import']) && isset($_FILES['importxml']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-import'))
						$import_return = $this->controller->import_popup($_POST, $_FILES);
					?>

					<form method="post" enctype="multipart/form-data">
						<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-import'); ?>
						<?php 
						if (isset($import_return))
							echo '<div class="' . ($import_return['success'] ? 'wonderplugin-updated' : 'wonderplugin-error') . '"><p>' . $import_return['message'] . '</p></div>';
						$users = get_users();
						?>
						<h2>Choose an exported .xml file to upload, then click Upload
							file and import.</h2>
						<div class='wonderplugin-error wonderplugin-error-message'
							id="wp-import-error"></div>
						<input type="file" name="importxml" id="wp-importxml" />
						<p>
							<label><input type="radio" name="keepid" value=1 checked>Keep
								the same popup ID</label>
						</p>
						<p>
							<label><input type="radio" name="keepid" value=0>Append to the
								existing popup list </label>
						</p>
						<p>
							Assign to the user: <select name="authorid">
								<?php foreach ( $users as $user ) { ?>
								<option value="<?php echo $user->ID; ?>">
									<?php echo $user->user_login; ?>
								</option>
								<?php } ?>
							</select>
						</p>
						<h3>Search and replace</h3>
						<div class='wonderplugin-error wonderplugin-error-message'
							id="wp-replace-error"></div>
						<div id='wp-search-replace'></div>
						<div id="wp-site-url" style="display: none;"><?php echo get_site_url(); ?></div>
						<button class="button-secondary" id="wp-add-replace-list">Add
							Row</button>
						<p class="submit">
							<input type="submit" name="wp-import" id="wp-import-submit"
								class="button button-primary" value="Upload file and import" />
					
					</form>
				</li>

				<li class="wonderplugin-tab wonderplugin-tab-horizontal-xml"><?php 
				if (empty($data)) {
					echo '<p>No popup found!</p>';
				} else {
					?>
					<h2>Export to an .xml file.</h2>
					<form method="post"
						action="<?php echo admin_url('admin-post.php?action=wonderplugin_popup_export'); ?>">
						<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-export'); ?>

						<p>
							<label><input type="radio" name="allpopup" value=1 checked>Export
								all popups</label>
						</p>
						<p>
							<label><input type="radio" name="allpopup" value=0>Select a
								popup: </label> <select name="popupid">
								<?php foreach ($data as $export_item) { ?>
								<option value="<?php echo $export_item['id']; ?>">
									<?php echo 'ID ' . $export_item['id'] . ' : ' . $export_item['name']; ?>
								</option>
								<?php } ?>
							</select>
						</p>
						<p class="submit">
							<input type="submit" name="wp-export"
								class="button button-primary" value="Export" />
							<?php if ( WP_DEBUG ) { ?>
							<span style="margin-left: 12px;">Warning: WP_DEBUG is enabled,
								the function "Export" may not work correctly. Please check
								your WordPress configuration file wp-config.php and change the
								WP_DEBUG to false.</span>
							<?php } ?>
						</p>
					</form> <?php } ?>
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-horizontal-xml">
			
				<?php 
	        	if (empty($data)) {
	        		echo '<p>No popup found!</p>';
	        	} else {
	        	?>
	        	<h2>Search and Replace</h2>
				<form method="post">
	        	<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-search-replace'); ?>
	        	<?php
	        	if (isset($_POST['wp-search-replace-submit']) && check_admin_referer('wonderplugin-popup', 'wonderplugin-popup-search-replace'))
					$search_return = $this->controller->search_replace_items($_POST);
				
	        	if (isset($search_return))
	        		echo '<div class="' . ($search_return['success'] ? 'wonderplugin-updated' : 'wonderplugin-error') . '"><p>' . $search_return['message'] . '</p></div>';
	        	?>
	        	<p><label><input type="radio" name="allitems" value=1 checked>Apply to all popups</label></p>
	        	<p><label><input type="radio" name="allitems" value=0>Select a popup: </label>
	        	<select name="itemid">
	        	<?php foreach ($data as $item) { ?>
	  				<option value="<?php echo $item['id']; ?>"><?php echo 'ID ' . $item['id'] . ' : ' . $item['name']; ?></option>
	  			<?php } ?>
	  			</select>
	        	</p>
	        	
	        	<h3>Search and replace</h3>
	        	<div class='wonderplugin-error wonderplugin-error-message' id="wp-standalone-replace-error"></div>
	        	<div id='wp-standalone-search-replace'></div>
	        	<button class="button-secondary" id="wp-add-standalone-replace-list">Add Row</button>
	        	<p class="submit"><input type="submit" name="wp-search-replace-submit" id="wp-search-replace-submit" class="button button-primary" value="Search and Replace"  />
	        	</p>
				</form>	
				<?php } ?>
				</li>
			</ul>

		</div>
		<?php
	}
}