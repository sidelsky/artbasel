<?php

if ( ! defined( 'ABSPATH' ) )
	exit;
	
class WonderPlugin_Popup_Creator {
	
	private $parent_view, $list_table;
	
	function __construct($parent) {
		
		$this->parent_view = $parent;
	}
	
	function render( $id, $config, $options ) {
		
		$popup_type = array("lightbox" => "Lightbox", "slidein" => "Slide In", "embed" => "Embed", "bar" => "Notification Bar");
		
		$popup_comm_defaults = array(
				"uniquevideoiframeid"	=> 1,
				"removeinlinecss"		=> 1,
				"loggedinonly"			=> 0,
				"enableretarget"		=> 0,
				"showterms"							=> 0,
				"terms"								=> "I agree to the Terms of Service",
				"termsfieldname"					=> "TERMS",
				"termsrequired"						=> 1,
				"termsnotcheckedmessage"			=> "You must agree to our Terms of Service.",
				"showprivacyconsent"				=> 0,
				"privacyconsent"					=> "To join our email newsletter, please tick the box. You can unsubscribe at any time. ",
				"privacyconsentfieldname"			=> "CONSENT",
				"privacyconsentrequired"			=> 1,
				"privacyconsentnotcheckedmessage"	=> "To join our newsletter, please tick the box.",
				"dataoptions"						=> "",
				"alreadysubscribedandupdatedmessage" => "The subscription has been updated.",
				"fieldorder" => '',
				"customfields" => '',
				"displaylangrules"					=> '[{"action":1,"rule":"alllangs"}]',
				"autoclose"							=> 0,
				"autoclosedelay" 					=> 60,
				"emailautoresponder"				=> 0,
				"emailautorespondersubject"			=> "Thank You for Subscribing",
				"emailautorespondercontent"			=> "",
				"getresponsev3autoresponder"		=> 1,
				"videomuted"						=> 0,
				"videocontrols"						=> 1,
				"videonodownload"					=> 1,
				"videohtml5"						=> ""
			);
		
		$popup_defaults = array(
				
			"lightbox"	=> array(
				"type"						=> "lightbox",
					
				"status"					=> 1,
				"skin"						=> "simple",
					
				"popupname"					=> "New Lightbox",
				"width"						=> 600,
				"maxwidth"					=> 90,
				"mintopbottommargin"		=> 10,
				"radius"					=> 6,
				"bordershadow"				=> "0px 1px 4px 0px rgba(0, 0, 0, 0.2)",
				"overlaycolor"				=> "#333333",
				"overlayopacity"			=> 0.8,
				"overlayclose"				=> 0,
				"backgroundcolor"			=> "#ffffff",
				"backgroundimage"			=> "",
				"backgroundimagerepeat"		=> "repeat",
				"backgroundimageposition" 	=> "0px 0px",
				"backgroundtopcolor"		=> "",
				"backgroundbottomcolor"		=> "",
				
				"fullscreen"				=> 0,
				"fullscreenwidth"			=> 600,

				"slideinposition"			=> "bottom-right",
				"barposition"				=> "top",
				"barfloat"					=> 0,
					
				"showclose"					=> 1,
				"closecolor"				=> "#666666",
				"closehovercolor"			=> "#000000",
				"closebackgroundcolor"		=> "#ffffff",
				"closeshowshadow"			=> 1,
				"closeshadow"				=> "0px 2px 2px 0px rgba(0, 0, 0, 0.3)",
				"closeposition"				=> "top-right-inside",
				"leftwidth"					=> "30",

				"logo"						=> "",
				"heading"					=> "Subscribe To Our Newsletter",
				"headingcolor"				=> "#dd3333",
				"tagline"					=> "",
				"taglinecolor"				=> "#333333",
				"description"				=> "Subscribe to our email newsletter today to receive updates on the latest news, tutorials and special offers!",
				"descriptioncolor"			=> "#333333",
				"bulletedlist"				=> "",
				"bulletedlistcolor"			=> "#333333",
				"privacy"					=> "We respect your privacy. Your information is safe and will never be shared.",
				"privacycolor"				=> "#333333",
				"image"						=> "",
				"video"						=> "",
				"videoautoplay"				=> 0,
				"videoautoclose"			=> 0,
				"ribbon"					=> WONDERPLUGIN_POPUP_URL . "skins/ribbon-0.png",
				"ribboncss"					=> "top:-8px;left:-8px;",
				"customcontent"				=> "",
					
				"showprivacy"				=> 0,
				"showribbon"				=> 0,
				"showclosetip"				=> 0,
				"closetip"					=> "Don't miss out. Subscribe today.",
					
				"hidebarstyle"				=> "donotshow",
				"hidebartitle"				=> "Subscribe To Our Newsletter",
				"hidebarbgcolor"			=> "#4791d6",
				"hidebarcolor"				=> "#ffffff",
				"hidebarwidth"				=> "auto",
				"hidebarpos"				=> "bottom-right",
				"hidebarnotshowafteraction"	=> 1,
					
				"showemail"					=> 1,
				"email"						=> "Enter your email address",
				"emailinputwidth"			=> 240,
				"emailfieldname"			=> "EMAIL",
				"showname"					=> 0,
				"name"						=> "Enter your name",
				"nameinputwidth"			=> 240,
				"namefieldname"				=> "NAME",
				"showfirstname"				=> 0,
				"firstname"					=> "Enter your first name",
				"firstnameinputwidth"		=> 180,
				"firstnamefieldname"		=> "FNAME",
				"showlastname"				=> 0,
				"lastname"					=> "Enter your last name",
				"lastnameinputwidth"		=> 180,
				"lastnamefieldname"			=> "LNAME",
				"showphone"					=> 0,
				"phone"						=> "Enter your phone number",
				"phoneinputwidth"			=> 180,
				"phonefieldname"			=> "PHONE",
				"showcompany"				=> 0,
				"company"					=> "Enter your company name",
				"companyinputwidth"			=> 180,
				"companyfieldname"			=> "COMPANY",
				"showzip"					=> 0,
				"zip"						=> "Enter your zip code",
				"zipinputwidth"				=> 180,
				"zipfieldname"				=> "ZIP",
				"showaction"				=> 1,
				"action"					=> "Subscribe Now",
				"actioncss"					=> "wonderplugin-popup-btn-blue",
				"showcancel"				=> 0,
				"cancel"					=> "No Thanks",
				"cancelcss"					=> "wonderplugin-popup-btn-blue",
				"cancelastext"				=> 0,
				"canceltextcolor"			=> "#666666",
				"showgrecaptcha"			=> 0,
				"grecaptchasitekey"			=> "",
				"grecaptchasecretkey"		=> "",

				"showmessage"				=> 0,
				"message"					=> "Enter your message",
				"messageinputwidth"			=> 490,
				"messageinputheight"		=> 120,
				"messagefieldname"			=> "MESSAGE",
															
				"inanimation"				=> "swing",
				"outanimation"				=> "noanimation",

				"template"					=>	"<div class=\"wonderplugin-box-container\">\r\n\t<div class=\"wonderplugin-box-bg\"></div>\r\n\t<div class=\"wonderplugin-box-dialog\">\r\n\t\t<div class=\"wonderplugin-box-content\">\r\n\t\t\t<div class=\"wonderplugin-box-top\">\r\n\t\t\t\t<div class=\"wonderplugin-box-heading\">__HEADING__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-description\">__DESCRIPTION__</div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"wonderplugin-box-bottom\">\r\n\t\t\t\t<div class=\"wonderplugin-box-formcontainer\">__FORM__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-privacy\">__PRIVACY__</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t\t<div class=\"wonderplugin-box-ribbon\"><img src=\"__RIBBON__\"></div>\r\n\t\t<div class=\"wonderplugin-box-closetip\">__CLOSETIP__</div>\r\n\t\t<div class=\"wonderplugin-box-closebutton\">&#215;</div>\r\n\t</div>\r\n\t<div class=\"wonderplugin-box-fullscreenclosebutton\">&#215;</div>\r\n</div>",
				"css"						=>	"/* google fonts */\r\n@import url(https://fonts.googleapis.com/css?family=Open+Sans);\r\n\r\n/* DO NOT CHANGE, container */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-container {\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding-left: 0px;\r\n\tpadding-right: 0px;\r\n\ttext-align: center;\r\n\tfont-size: 12px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n}\r\n\r\n/* DO NOT CHANGE, the dialog, including content and close button,  */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-dialog {\r\n\t-webkit-overflow-scrolling: touch;\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmax-height: 100%;\r\n\tmargin: 0 auto;\r\n\tpadding: 0;\r\n}\r\n\r\n/* overlay background */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bg {\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tposition: absolute;\r\n\ttop: 0;\r\n\tleft: 0;\r\n\twidth: 100%;\r\n\theight: 100%;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton {\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tposition: absolute;\r\n\twidth: 28px;\r\n\theight: 28px;\r\n\tborder-radius: 14px;\r\n\tcursor: pointer;\r\n\tline-height: 30px;\r\n\tfont-size: 24px;\r\n\tfont-family: Arial, sans-serif;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton:hover {\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip {\r\n\tbox-sizing: border-box;\r\n\tdisplay: none;\r\n\tposition: absolute;\r\n\tbottom: 100%;\r\n\tcolor: #fff;\r\n\tbackground-color: #dd3333;\r\n\tborder-radius: 4px;\r\n\tfont-size: 14px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\t -webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n\tmargin: 0;\r\n\tpadding: 12px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip:after {\r\n\tposition: absolute;\r\n\tcontent: \" \";\r\n\twidth: 0;\r\n\theight: 0;\r\n\tborder-style: solid;\r\n\tborder-width: 6px 6px 0 6px;\r\n\tborder-color: #dd3333 transparent transparent transparent;\r\n\ttop: 100%;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-fullscreenclosebutton {\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tdisplay: none;\r\n\tposition: fixed;\r\n\ttop: 18px;\r\n\tright: 18px;\r\n\tcursor: pointer;\r\n\tfont-size: 36px;\r\n\tfont-family: Arial, sans-serif;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-fullscreenclosebutton:hover {\r\n}\r\n\r\n/* content */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-content {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tmax-height: 100%;\r\n\tbox-sizing: border-box;\r\n\toverflow: auto;\r\n\t-webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n\tcolor: #333333;\r\n}\r\n\r\n/* top part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-top {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 24px 24px 0px;\r\n\tclear:both;\r\n}\r\n\r\n/* bottom part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bottom {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 0px 24px 24px;\r\n\tclear:both;\r\n}\r\n\r\n/* heading */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-heading {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 24px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tline-height: 1.2em;\r\n\tmargin: 0 auto;\r\n\tpadding: 12px 0px;\r\n}\r\n\r\n/* description text */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-description {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 14px;\r\n\tline-height: 1.8em;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 0 auto;\r\n\tpadding: 12px 0px;\r\n}\r\n\r\n/* email form */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tmargin: 0 auto;\r\n\tpadding: 12px 0px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formbefore {\r\n\tdisplay: block;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formafter {\r\n\tdisplay: none;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formmessage {\r\n\tdisplay: none;\r\n\tcolor: #ff0000;\r\n\tfont-size: 14px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-afteractionmessage {\r\n\tcolor: #ff0000;\r\n\tfont-size: 14px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer textarea {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 4px auto;\r\n\tborder-radius: 4px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer input[type=text] {\r\n\tcolor: #333;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 4px;\r\n\tborder-radius: 4px;\r\n}\r\n\r\n/* subscribe now button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-action {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tfont-size: 14px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n}\r\n\r\n/* no thanks button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-cancel {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tfont-size: 14px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tcursor: pointer;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-privacy {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 12px;\r\n\tline-height: 1.2em;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 0 auto;\r\n\tpadding: 6px 0px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon {\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n\tposition: absolute;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon img {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tmax-width: 100%;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-recaptcha {\r\n\tdisplay: inline-block;\r\n\tmargin: 0 auto;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-form-highlight {\r\n\tborder: 1px dashed #ff0000;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-group {\r\n    padding: 4px 0;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-title {\r\n    font-weight: bold;\r\n    padding-right: 16px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-checkbox,\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-radio {\r\n    padding-right:16px;\r\n}",
																													
				"displaypagerules"			=> '[{"action":1,"rule":"allpagesposts"}]',
				"displaydevicerules"		=> '[{"action":1,"rule":"alldevices"}]',
					
				"displayonpageload"			=> 1,
				"displayonpagescrollpercent"	=> 0,
				"displayonpagescrollpixels"		=> 0,
				"displayonpagescrollcssselector"	=> 0,
				"displayonuserinactivity"	=> 0,
				"displayonclosepage"		=> 0,
				"displaydelay"				=> 3,
				"displaypercent"			=> 80,
				"displaypixels"				=> 600,
				"displaycssselector"		=> "",
				"displayinactivity"			=> 60,
				"displaysensitivity"		=> 20,
					
				"retargetnoshowaction"		=> 365,
				"retargetnoshowactionunit"	=> "days",
				"retargetnoshowcancel"		=> 60,
				"retargetnoshowcancelunit"	=> "days",
				"retargetnoshowclose"		=> 30,
				"retargetnoshowcloseunit"	=> "days",
					
				"afteraction"				=> "display",
				"redirecturl"				=> "",
				"redirecturlpassparams"		=> "passpost",
				"afteractionmessage" 		=> "Thanks for signing up. You must confirm your email address before we can send you. Please check your email and follow the instructions.",
				"afteractionbutton"			=> "Close",
				"closeafterbutton"			=> 1,
				"redirectafterbutton"		=> 0,
				"redirectafterbuttonurl"	=> "",
				"redirectafterbuttonpassparams"	=> "passpost",
					
				"displayloading"			=> 1,
				"loadingimage"				=> WONDERPLUGIN_POPUP_URL . "skins/loading-0.gif",
					
				"invalidemailmessage"		=> "The email address is invalid.",
				"fieldmissingmessage"		=> "Please fill in the required field.",
				"alreadysubscribedmessage"	=> "The email address has already subscribed.",
				"generalerrormessage"		=> "Something went wrong. Please try again later.",
				"displaydetailedmessage"		=> 0,
				
					
				"subscription" 				=> "noservice",
				"mailchimpdoubleoptin"		=> 1,
				"icontactdoubleoptin"		=> 0,
				"infusionsoftdoubleoptin"	=> 0,
				"savetolocal"				=> 0,
				"emailnotify"				=> 0,
				"emailto"					=> "",
				"emailsubject"				=> "Your list has gained a new subscriber",
				"getresponseautoresponder"	=> 1,
					
				"mailpoet3sendconfirmationemail"	=> 1,
				"mailpoet3schedulewelcomeemail"		=> 1,
					
				"enablegoogleanalytics"		=> 0,
				"gaid"						=> "",
				"gaeventcategory"			=> "Popup",
				"gaeventlabel"				=> "",
					
				"enablelocalanalytics"		=> 0,
					
				"customcss"					=> "",
				"customjs"					=> ""
			),
			"slidein" => array(
				"type"						=> "slidein",
					
				"status"					=> 1,
				"skin"						=> "blueslidein",
					
				"popupname"					=> "New Slide In",
				"width"						=> 300,
				"maxwidth"					=> 100,
				"mintopbottommargin"		=> 0,
				"radius"					=> 0,
				"bordershadow"				=> "0px 1px 4px 0px rgba(0, 0, 0, 0.2)",
				"overlaycolor"				=> "#333333",
				"overlayopacity"			=> 0.8,
				"overlayclose"				=> 0,
				"backgroundcolor"			=> "#4791d6",
				"backgroundimage"			=> "",
				"backgroundimagerepeat"		=> "repeat",
				"backgroundimageposition" 	=> "0px 0px",
				"backgroundtopcolor"		=> "",
				"backgroundbottomcolor"		=> "",
									
				"fullscreen"				=> 0,
				"fullscreenwidth"			=> 600,

				"slideinposition"			=> "bottom-right",
				"barposition"				=> "top",
				"barfloat"					=> 0,
						
				"showclose"					=> 1,
				"closecolor"				=> "#f0f0f0",
				"closehovercolor"			=> "#ffffff",
				"closebackgroundcolor"		=> "#ffffff",
				"closeshowshadow"			=> 1,
				"closeshadow"				=> "0px 2px 2px 0px rgba(0, 0, 0, 0.3)",
				"closeposition"				=> "top-left-inside",
				"leftwidth"					=> "30",

				"logo"						=> "",
				"heading"					=> "Subscribe To Our Newsletter",
				"headingcolor"				=> "#ffffff",
				"tagline"					=> "",
				"taglinecolor"				=> "#ffffff",
				"description"				=> "Subscribe to our email newsletter today to receive updates on the latest news, tutorials and special offers!",
				"descriptioncolor"			=> "#ffffff",
				"bulletedlist"				=> "",
				"bulletedlistcolor"			=> "#ffffff",
				"privacy"					=> "We respect your privacy. Your information is safe and will never be shared.",
				"privacycolor"				=> "#ffffff",
				"image"						=> "",
				"video"						=> "",
				"videoautoplay"				=> 0,
				"videoautoclose"			=> 0,
				"ribbon"					=> WONDERPLUGIN_POPUP_URL . "skins/ribbon-0.png",
				"ribboncss"					=> "top:-8px;left:-8px;",
				"customcontent"				=> "",
					
				"showprivacy"				=> 1,
				"showribbon"				=> 0,
				"showclosetip"				=> 0,
				"closetip"					=> "Don't miss out. Subscribe today.",

				"hidebarstyle"				=> "textbar",
				"hidebartitle"				=> "Subscribe To Our Newsletter",	
				"hidebarbgcolor"			=> "#4791d6",
				"hidebarcolor"				=> "#ffffff",
				"hidebarwidth"				=> "same",
				"hidebarpos"				=> "same",
				"hidebarnotshowafteraction"	=> 1,
					
				"showemail"					=> 1,
				"email"						=> "Enter your email address",
				"emailinputwidth"			=> 300,
				"emailfieldname"			=> "EMAIL",
				"showname"					=> 1,
				"name"						=> "Enter your name",
				"nameinputwidth"			=> 300,
				"namefieldname"				=> "NAME",
				"showfirstname"				=> 0,
				"firstname"					=> "Enter your first name",
				"firstnameinputwidth"		=> 300,
				"firstnamefieldname"		=> "FNAME",
				"showlastname"				=> 0,
				"lastname"					=> "Enter your last name",
				"lastnameinputwidth"		=> 300,
				"lastnamefieldname"			=> "LNAME",
				"showphone"					=> 0,
				"phone"						=> "Enter your phone number",
				"phoneinputwidth"			=> 300,
				"phonefieldname"			=> "PHONE",
				"showcompany"				=> 0,
				"company"					=> "Enter your company name",
				"companyinputwidth"			=> 300,
				"companyfieldname"			=> "COMPANY",
				"showzip"					=> 0,
				"zip"						=> "Enter your zip code",
				"zipinputwidth"				=> 300,
				"zipfieldname"				=> "ZIP",
				"showaction"				=> 1,
				"action"					=> "Subscribe Now",
				"actioncss"					=> "wonderplugin-popup-btn-green",
				"showcancel"				=> 1,
				"cancel"					=> "No Thanks",
				"cancelcss"					=> "wonderplugin-popup-btn-green",
				"cancelastext"				=> 1,
				"canceltextcolor"			=> "#f0f0f0",
				"showgrecaptcha"			=> 0,
				"grecaptchasitekey"			=> "",
				"grecaptchasecretkey"		=> "",
					
				"showmessage"				=> 0,
				"message"					=> "Enter your message",
				"messageinputwidth"			=> 300,
				"messageinputheight"		=> 120,
				"messagefieldname"			=> "MESSAGE",
					
				"inanimation"				=> "slideInUp",
				"outanimation"				=> "slideOutDown",
					
				"template"					=>	"<div class=\"wonderplugin-box-container\">\r\n\t<div class=\"wonderplugin-box-bg\"></div>\r\n\t<div class=\"wonderplugin-box-dialog\">\r\n\t\t<div class=\"wonderplugin-box-content\">\r\n\t\t\t<div class=\"wonderplugin-box-top\">\r\n\t\t\t\t<div class=\"wonderplugin-box-logo\"><img alt=\"\" src=\"__LOGO__\"></div>\r\n\t\t\t\t<div class=\"wonderplugin-box-heading\">__HEADING__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-description\">__DESCRIPTION__</div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"wonderplugin-box-bottom\">\r\n\t\t\t\t<div class=\"wonderplugin-box-formcontainer\">__FORM__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-privacy\">__PRIVACY__</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t\t<div class=\"wonderplugin-box-ribbon\"><img src=\"__RIBBON__\"></div>\r\n\t\t<div class=\"wonderplugin-box-closetip\">__CLOSETIP__</div>\r\n\t\t<div class=\"wonderplugin-box-closebutton\">&#215;</div>\r\n\t</div>\r\n\t<div class=\"wonderplugin-box-fullscreenclosebutton\">&#215;</div>\r\n</div>",
				"css"						=>	"/* google fonts */\r\n@import url(https://fonts.googleapis.com/css?family=Open+Sans);\r\n\r\n/* DO NOT CHANGE, container */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-container {\r\n	display: block;\r\n	box-sizing: border-box;\r\n	margin: 0;\r\n	padding-left: 0px;\r\n	padding-right: 0px;\r\n	text-align: center;\r\n	font-size: 12px;\r\n	font-weight: 400;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n}\r\n\r\n/* DO NOT CHANGE, the dialog, including content and close button,  */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-dialog {\r\n	-webkit-overflow-scrolling: touch;\r\n	display: block;\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	max-height: 100%;\r\n	margin: 0 auto;\r\n	padding: 0;\r\n}\r\n\r\n/* overlay background */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bg {\r\n	display: block;\r\n	box-sizing: border-box;\r\n	position: absolute;\r\n	top: 0;\r\n	left: 0;\r\n	width: 100%;\r\n	height: 100%;\r\n	margin: 0;\r\n	padding: 0;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton {\r\n	box-sizing: border-box;\r\n	text-align: center;\r\n	position: absolute;\r\n	width: 28px;\r\n	height: 28px;\r\n	border-radius: 14px;\r\n	cursor: pointer;\r\n	line-height: 30px;\r\n	font-size: 24px;\r\n	font-family: Arial, sans-serif;\r\n	margin: 0;\r\n	padding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton:hover {\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip {\r\n	box-sizing: border-box;\r\n	display: none;\r\n	position: absolute;\r\n	bottom: 100%;\r\n	color: #fff;\r\n	background-color: #dd3333;\r\n	border-radius: 4px;\r\n	font-size: 14px;\r\n	font-weight: 400;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	 -webkit-font-smoothing: antialiased;\r\n	-moz-osx-font-smoothing: grayscale;\r\n	margin: 0;\r\n	padding: 12px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip:after {\r\n	position: absolute;\r\n	content: \" \";\r\n	width: 0;\r\n	height: 0;\r\n	border-style: solid;\r\n	border-width: 6px 6px 0 6px;\r\n	border-color: #dd3333 transparent transparent transparent;\r\n	top: 100%;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-fullscreenclosebutton {\r\n	box-sizing: border-box;\r\n	text-align: center;\r\n	display: none;\r\n	position: fixed;\r\n	top: 18px;\r\n	right: 18px;\r\n	cursor: pointer;\r\n	font-size: 36px;\r\n	font-family: Arial, sans-serif;\r\n	margin: 0;\r\n	padding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-fullscreenclosebutton:hover {\r\n}\r\n\r\n/* content */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-content {\r\n	display: block;\r\n	position: relative;\r\n	max-height: 100%;\r\n	box-sizing: border-box;\r\n	overflow: auto;\r\n	-webkit-font-smoothing: antialiased;\r\n	-moz-osx-font-smoothing: grayscale;\r\n	margin: 0;\r\n	padding: 0;\r\n	color: #fff;\r\n}\r\n\r\n/* top part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-top {\r\n	display: block;\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	margin: 0;\r\n	padding: 24px 24px 0px;\r\n	clear:both;\r\n}\r\n\r\n/* bottom part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bottom {\r\n	display: block;\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	margin: 0;\r\n	padding: 0px 24px 12px;\r\n	clear:both;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-logo img {\r\n	display: block;\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	margin: 0 auto;\r\n	padding: 0;\r\n	max-width: 100%;\r\n}\r\n\r\n/* heading */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-heading {\r\n	position: relative;\r\n	display: block;\r\n	box-sizing: border-box;\r\n	font-size: 24px;\r\n	font-weight: 400;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	line-height: 1.2em;\r\n	margin: 0 auto;\r\n	padding: 6px 0px;\r\n}\r\n\r\n/* description text */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-description {\r\n	position: relative;\r\n	display: block;\r\n	box-sizing: border-box;\r\n	font-size: 14px;\r\n	line-height: 1.8em;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	margin: 0 auto;\r\n	padding: 12px 0px;\r\n}\r\n\r\n/* email form */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer {\r\n	position: relative;\r\n	display: block;\r\n	box-sizing: border-box;\r\n	text-align: center;\r\n	margin: 0 auto;\r\n	padding: 12px 0px 6px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formbefore {\r\n	display: block;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formafter {\r\n	display: none;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formmessage {\r\n	display: none;\r\n	color: #eeee22;\r\n	font-size: 14px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-afteractionmessage {\r\n	color: #eeee22;\r\n	font-size: 14px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer textarea {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 4px auto;\r\n\tborder-radius: 4px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer input[type=text] {\r\n\tcolor: #333;\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	display: inline-block;\r\n	max-width: 100%;\r\n	font-size: 12px;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	padding: 8px;\r\n	margin: 4px auto;\r\n	border-radius: 0px;\r\n}\r\n\r\n/* subscribe now button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-action {\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	display: inline-block;\r\n	font-size: 14px;\r\n	font-weight: bold;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	width: 100%;\r\n	margin: 4px auto;\r\n}\r\n\r\n/* no thanks button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-cancel {\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	display: inline-block;\r\n	font-size: 12px;\r\n	font-weight: bold;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	cursor: pointer;\r\n	margin: 4px auto;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-privacy {\r\n	position: relative;\r\n	box-sizing: border-box;\r\n	font-size: 12px;\r\n	line-height: 1.2em;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	margin: 0 auto;\r\n	padding: 6px 0px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon {\r\n	box-sizing: border-box;\r\n	margin: 0;\r\n	padding: 0;\r\n	position: absolute;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon img {\r\n	position: relative;\r\n	display: block;\r\n	box-sizing: border-box;\r\n	max-width: 100%;\r\n	margin: 0;\r\n	padding: 0;\r\n}\r\n\r\n#wonderplugin-box-hidebar-POPUPID {\r\n	padding: 4px 8px;\r\n	box-sizing: border-box;\r\n 	-webkit-font-smoothing: antialiased;\r\n	-moz-osx-font-smoothing: grayscale;\r\n}\r\n\r\n#wonderplugin-box-hidebar-POPUPID:before {\r\n	display: inline-block;\r\n	vertical-align: middle;\r\n	font-family: Arial, sans-serif;\r\n	font-size: 24px;\r\n	font-weight: 400;\r\n	content: \"+\";\r\n	margin: 0px 8px 0px 0px;\r\n}\r\n\r\n#wonderplugin-box-hidebar-POPUPID .wonderplugin-box-hidebar-title {\r\n	display: inline-block;\r\n	vertical-align: middle;\r\n	font-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n	font-size: 13px;\r\n	font-weight: bold;\r\n	line-height: 24px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-recaptcha {\r\n\tdisplay: inline-block;\r\n\tmargin: 0 auto;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-form-highlight {\r\n\tborder: 1px dashed #ff0000;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-group {\r\n    padding: 4px 0;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-title {\r\n    font-weight: bold;\r\n    padding-right: 16px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-checkbox,\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-radio {\r\n    padding-right:16px;\r\n}",
																													
				"displaypagerules"			=> '[{"action":1,"rule":"allpagesposts"}]',
				"displaydevicerules"		=> '[{"action":1,"rule":"alldevices"}]',
					
				"displayonpageload"			=> 1,
				"displayonpagescrollpercent"	=> 0,
				"displayonpagescrollpixels"		=> 0,
				"displayonpagescrollcssselector"	=> 0,
				"displayonuserinactivity"	=> 0,
				"displayonclosepage"		=> 0,
				"displaydelay"				=> 3,
				"displaypercent"			=> 80,
				"displaypixels"				=> 600,
				"displaycssselector"		=> "",
				"displayinactivity"			=> 60,
				"displaysensitivity"		=> 20,
					
				"retargetnoshowaction"		=> 365,
				"retargetnoshowactionunit"	=> "days",
				"retargetnoshowcancel"		=> 60,
				"retargetnoshowcancelunit"	=> "days",
				"retargetnoshowclose"		=> 30,
				"retargetnoshowcloseunit"	=> "days",
					
				"afteraction"				=> "display",
				"redirecturl"				=> "",
				"redirecturlpassparams"		=> "passpost",
				"afteractionmessage" 		=> "Thanks for signing up. You must confirm your email address before we can send you. Please check your email and follow the instructions.",
				"afteractionbutton"			=> "Close",
				"closeafterbutton"			=> 1,
				"redirectafterbutton"		=> 0,
				"redirectafterbuttonurl"	=> "",
				"redirectafterbuttonpassparams"	=> "passpost",
					
				"displayloading"			=> 1,
				"loadingimage"				=> WONDERPLUGIN_POPUP_URL . "skins/loading-2.gif",
					
				"invalidemailmessage"		=> "The email address is invalid.",
				"fieldmissingmessage"		=> "Please fill in the required field.",
				"alreadysubscribedmessage"	=> "The email address has already subscribed.",
				"generalerrormessage"		=> "Something went wrong. Please try again later.",
				"displaydetailedmessage"		=> 0,
					
				"subscription" 				=> "noservice",
				"mailchimpdoubleoptin"		=> 1,
				"icontactdoubleoptin"		=> 0,
				"infusionsoftdoubleoptin"	=> 0,
				"savetolocal"				=> 0,
				"emailnotify"				=> 0,
				"emailto"					=> "",
				"emailsubject"				=> "Your list has gained a new subscriber",
				"getresponseautoresponder"	=> 1,
					
				"mailpoet3sendconfirmationemail"	=> 1,
				"mailpoet3schedulewelcomeemail"		=> 1,
					
				"enablegoogleanalytics"		=> 0,
				"gaid"						=> "",
				"gaeventcategory"			=> "Popup",
				"gaeventlabel"				=> "",
					
				"enablelocalanalytics"		=> 0,
					
				"customcss"					=> "",
				"customjs"					=> ""
			),
			"embed"	=> array(
				"type"						=> "embed",
					
				"status"					=> 1,
				"skin"						=> "classic",
					
				"popupname"					=> "New Embed",
				"width"						=> 240,
				"maxwidth"					=> 90,
				"mintopbottommargin"		=> 0,
				"radius"					=> 0,
				"bordershadow"				=> "0px 0px 1px 0px rgba(0, 0, 0, 0.2)",
				"overlaycolor"				=> "#333333",
				"overlayopacity"			=> 0.8,
				"overlayclose"				=> 0,
				"backgroundcolor"			=> "#ffffff",
				"backgroundimage"			=> "",
				"backgroundimagerepeat"		=> "repeat",
				"backgroundimageposition" 	=> "0px 0px",
				"backgroundtopcolor"		=> "",
				"backgroundbottomcolor"		=> "",
				"fullscreen"				=> 0,
				"fullscreenwidth"			=> 600,
				
				"slideinposition"			=> "bottom-right",
				"barposition"				=> "top",
				"barfloat"					=> 0,
					
				"showclose"					=> 0,
				"closecolor"				=> "#666666",
				"closehovercolor"			=> "#000000",
				"closebackgroundcolor"		=> "#ffffff",
				"closeshowshadow"			=> 1,
				"closeshadow"				=> "0px 2px 2px 0px rgba(0, 0, 0, 0.3)",
				"closeposition"				=> "top-right-inside",
				"leftwidth"					=> "30",

				"logo"						=> "",
				"heading"					=> "Subscribe To Our Newsletter",
				"headingcolor"				=> "#dd3333",
				"tagline"					=> "",
				"taglinecolor"				=> "#333333",
				"description"				=> "Subscribe to our email newsletter today to receive updates on the latest news, tutorials and special offers!",
				"descriptioncolor"			=> "#333333",
				"bulletedlist"				=> "",
				"bulletedlistcolor"			=> "#333333",
				"privacy"					=> "We respect your privacy. Your information is safe and will never be shared.",
				"privacycolor"				=> "#333333",
				"image"						=> "",
				"video"						=> "",
				"videoautoplay"				=> 0,
				"videoautoclose"			=> 0,
				"ribbon"					=> WONDERPLUGIN_POPUP_URL . "skins/ribbon-0.png",
				"ribboncss"					=> "top:-8px;left:-8px;",
				"customcontent"				=> "",
					
				"showprivacy"				=> 0,
				"showribbon"				=> 0,
				"showclosetip"				=> 0,
				"closetip"					=> "",

				"hidebarstyle"				=> "donotshow",
				"hidebartitle"				=> "Subscribe To Our Newsletter",
				"hidebarbgcolor"			=> "#4791d6",
				"hidebarcolor"				=> "#ffffff",
				"hidebarwidth"				=> "auto",
				"hidebarpos"				=> "bottom-right",
				"hidebarnotshowafteraction"	=> 1,
					
				"showemail"					=> 1,
				"email"						=> "Enter your email address",
				"emailinputwidth"			=> 240,
				"emailfieldname"			=> "EMAIL",
				"showname"					=> 1,
				"name"						=> "Enter your name",
				"nameinputwidth"			=> 240,
				"namefieldname"				=> "NAME",
				"showfirstname"				=> 0,
				"firstname"					=> "Enter your first name",
				"firstnameinputwidth"		=> 180,
				"firstnamefieldname"		=> "FNAME",
				"showlastname"				=> 0,
				"lastname"					=> "Enter your last name",
				"lastnameinputwidth"		=> 180,
				"lastnamefieldname"			=> "LNAME",
				"showphone"					=> 0,
				"phone"						=> "Enter your phone number",
				"phoneinputwidth"			=> 180,
				"phonefieldname"			=> "PHONE",
				"showcompany"				=> 0,
				"company"					=> "Enter your company name",
				"companyinputwidth"			=> 180,
				"companyfieldname"			=> "COMPANY",
				"showzip"					=> 0,
				"zip"						=> "Enter your zip code",
				"zipinputwidth"				=> 180,
				"zipfieldname"				=> "ZIP",
				"showaction"				=> 1,
				"action"					=> "Subscribe Now",
				"actioncss"					=> "wonderplugin-popup-btn-blue",
				"showcancel"				=> 0,
				"cancel"					=> "No Thanks",
				"cancelcss"					=> "wonderplugin-popup-btn-blue",
				"cancelastext"				=> 0,
				"canceltextcolor"			=> "#666666",
				"showgrecaptcha"			=> 0,
				"grecaptchasitekey"			=> "",
				"grecaptchasecretkey"		=> "",
					
				"showmessage"				=> 0,
				"message"					=> "Enter your message",
				"messageinputwidth"			=> 180,
				"messageinputheight"		=> 120,
				"messagefieldname"			=> "MESSAGE",
					
				"inanimation"				=> "noanimation",
				"outanimation"				=> "noanimation",

				"template"					=>	"<div class=\"wonderplugin-box-container\">\r\n\t<div class=\"wonderplugin-box-bg\"></div>\r\n\t<div class=\"wonderplugin-box-dialog\">\r\n\t\t<div class=\"wonderplugin-box-content\">\r\n\t\t\t<div class=\"wonderplugin-box-top\">\r\n\t\t\t\t<div class=\"wonderplugin-box-heading\">__HEADING__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-description\">__DESCRIPTION__</div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"wonderplugin-box-bottom\">\r\n\t\t\t\t<div class=\"wonderplugin-box-formcontainer\">__FORM__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-privacy\">__PRIVACY__</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t\t<div class=\"wonderplugin-box-ribbon\"><img src=\"__RIBBON__\"></div>\r\n\t\t<div class=\"wonderplugin-box-closetip\">__CLOSETIP__</div>\r\n\t\t<div class=\"wonderplugin-box-closebutton\">&#215;</div>\r\n\t</div>\r\n</div>",
				"css"						=>	"/* google fonts */\r\n@import url(https://fonts.googleapis.com/css?family=Open+Sans);\r\n\r\n/* DO NOT CHANGE, container */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-container {\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding-left: 0px;\r\n\tpadding-right: 0px;\r\n\ttext-align: center;\r\n}\r\n\r\n/* DO NOT CHANGE, the dialog, including content and close button,  */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-dialog {\r\n\t-webkit-overflow-scrolling: touch;\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmax-height: 100%;\r\n\tmargin: 0 auto;\r\n\tpadding: 0;\r\n}\r\n\r\n/* overlay background */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bg {\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tposition: absolute;\r\n\ttop: 0;\r\n\tleft: 0;\r\n\twidth: 100%;\r\n\theight: 100%;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton {\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tposition: absolute;\r\n\twidth: 28px;\r\n\theight: 28px;\r\n\tborder-radius: 14px;\r\n\tcursor: pointer;\r\n\tline-height: 30px;\r\n\tfont-size: 24px;\r\n\tfont-family: Arial, sans-serif;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton:hover {\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip {\r\n\tbox-sizing: border-box;\r\n\tdisplay: none;\r\n\tposition: absolute;\r\n\tbottom: 100%;\r\n\tcolor: #fff;\r\n\tbackground-color: #dd3333;\r\n\tborder-radius: 4px;\r\n\tfont-size: 14px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\t -webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n\tmargin: 0;\r\n\tpadding: 12px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip:after {\r\n\tposition: absolute;\r\n\tcontent: \" \";\r\n\twidth: 0;\r\n\theight: 0;\r\n\tborder-style: solid;\r\n\tborder-width: 6px 6px 0 6px;\r\n\tborder-color: #dd3333 transparent transparent transparent;\r\n\ttop: 100%;\r\n}\r\n\r\n/* content */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-content {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tmax-height: 100%;\r\n\tbox-sizing: border-box;\r\n\toverflow: auto;\r\n\t-webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n\tcolor: #333333;\r\n\tborder: 1px solid #ccc;\r\n}\r\n\r\n/* top part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-top {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 12px 12px 0px;\r\n\tclear:both;\r\n}\r\n\r\n/* bottom part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bottom {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 0px 12px 12px;\r\n\tclear:both;\r\n}\r\n\r\n/* heading */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-heading {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 20px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tline-height: 1.2em;\r\n\tmargin: 0 auto;\r\n\tpadding: 8px 0px;\r\n}\r\n\r\n/* description text */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-description {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 14px;\r\n\tline-height: 1.6em;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 0 auto;\r\n\tpadding: 8px 0px;\r\n}\r\n\r\n/* email form */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tmargin: 0 auto;\r\n\tpadding: 8px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formbefore {\r\n\tdisplay: block;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formafter {\r\n\tdisplay: none;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formmessage {\r\n\tdisplay: none;\r\n\tcolor: #ff0000;\r\n\tfont-size: 14px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-afteractionmessage {\r\n\tcolor: #ff0000;\r\n\tfont-size: 14px;\r\n}\r\n\r\n/* input text field */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer textarea {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 4px auto;\r\n\tborder-radius: 4px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer input[type=text] {\r\n\tcolor: #333;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 4px 0px;\r\n\tborder-radius: 4px;\r\n}\r\n\r\n/* subscribe now button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-action {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tfont-size: 14px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 8px 0px;\r\n\tpadding: 12px;\r\n\twidth: 100%;\r\n}\r\n\r\n/* no thanks button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-cancel {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tfont-size: 14px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 8px;\r\n\tpadding: 12px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-privacy {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 12px;\r\n\tline-height: 1.2em;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 0 auto;\r\n\tpadding: 6px 0px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon {\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n\tposition: absolute;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon img {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tmax-width: 100%;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-recaptcha {\r\n\tdisplay: inline-block;\r\n\tmargin: 0 auto;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-form-highlight {\r\n\tborder: 1px dashed #ff0000;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-group {\r\n    padding: 4px 0;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-title {\r\n    font-weight: bold;\r\n    padding-right: 16px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-checkbox,\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-radio {\r\n    padding-right:16px;\r\n}",
												
				"displaypagerules"			=> '[{"action":1,"rule":"allpagesposts"}]',
				"displaydevicerules"		=> '[{"action":1,"rule":"alldevices"}]',
					
				"displayonpageload"			=> 0,
				"displayonpagescrollpercent"	=> 0,
				"displayonpagescrollpixels"		=> 0,
				"displayonpagescrollcssselector"	=> 0,
				"displayonuserinactivity"	=> 0,
				"displayonclosepage"		=> 0,
				"displaydelay"				=> 3,
				"displaypercent"			=> 80,
				"displaypixels"				=> 600,
				"displaycssselector"		=> "",
				"displayinactivity"			=> 60,
				"displaysensitivity"		=> 20,
					
				"retargetnoshowaction"		=> 0,
				"retargetnoshowactionunit"	=> "days",
				"retargetnoshowcancel"		=> 0,
				"retargetnoshowcancelunit"	=> "days",
				"retargetnoshowclose"		=> 0,
				"retargetnoshowcloseunit"	=> "days",
					
				"afteraction"				=> "display",
				"redirecturl"				=> "",
				"redirecturlpassparams"		=> "passpost",
				"afteractionmessage" 		=> "Thanks for signing up. You must confirm your email address before we can send you. Please check your email and follow the instructions.",
				"afteractionbutton"			=> "",
				"closeafterbutton"			=> 0,
				"redirectafterbutton"		=> 0,
				"redirectafterbuttonurl"	=> "",
				"redirectafterbuttonpassparams"	=> "passpost",
					
				"displayloading"			=> 1,
				"loadingimage"				=> WONDERPLUGIN_POPUP_URL . "skins/loading-0.gif",
					
				"invalidemailmessage"		=> "The email address is invalid.",
				"fieldmissingmessage"		=> "Please fill in the required field.",
				"alreadysubscribedmessage"	=> "The email address has already subscribed.",
				"generalerrormessage"		=> "Something went wrong. Please try again later.",
				"displaydetailedmessage"		=> 0,
					
				"subscription" 				=> "noservice",
				"mailchimpdoubleoptin"		=> 1,
				"icontactdoubleoptin"		=> 0,
				"infusionsoftdoubleoptin"	=> 0,
				"savetolocal"				=> 0,
				"emailnotify"				=> 0,
				"emailto"					=> "",
				"emailsubject"				=> "Your list has gained a new subscriber",
				"getresponseautoresponder"	=> 1,
					
				"mailpoet3sendconfirmationemail"	=> 1,
				"mailpoet3schedulewelcomeemail"		=> 1,
					
				"enablegoogleanalytics"		=> 0,
				"gaid"						=> "",
				"gaeventcategory"			=> "Popup",
				"gaeventlabel"				=> "",
					
				"enablelocalanalytics"		=> 0,
					
				"customcss"					=> "",
				"customjs"					=> ""
			),
			"bar" => array(
				"type"						=> "bar",
					
				"status"					=> 1,
				"skin"						=> "bluetopbar",
					
				"popupname"					=> "New Notification Bar",
				"width"						=> 300,
				"maxwidth"					=> 100,
				"mintopbottommargin"		=> 0,
				"radius"					=> 0,
				"bordershadow"				=> "0px 1px 4px 0px rgba(0, 0, 0, 0.2)",
				"overlaycolor"				=> "#333333",
				"overlayopacity"			=> 0.8,
				"overlayclose"				=> 0,
				"backgroundcolor"			=> "#4791d6",
				"backgroundimage"			=> "",
				"backgroundimagerepeat"		=> "repeat",
				"backgroundimageposition" 	=> "0px 0px",
				"backgroundtopcolor"		=> "",
				"backgroundbottomcolor"		=> "",
			
				"fullscreen"				=> 0,
				"fullscreenwidth"			=> 600,

				"slideinposition"			=> "bottom-right",
				"barposition"				=> "top",
				"barfloat"					=> 0,
						
				"showclose"					=> 1,
				"closecolor"				=> "#f0f0f0",
				"closehovercolor"			=> "#ffffff",
				"closebackgroundcolor"		=> "#ffffff",
				"closeshowshadow"			=> 1,
				"closeshadow"				=> "0px 2px 2px 0px rgba(0, 0, 0, 0.3)",
				"closeposition"				=> "top-right-inside",
				"leftwidth"					=> "30",
		
				"logo"						=> "",
				"heading"					=> "Subscribe To Our Newsletter",
				"headingcolor"				=> "#ffffff",
				"tagline"					=> "",
				"taglinecolor"				=> "#ffffff",
				"description"				=> "Subscribe to our email newsletter today to receive updates on the latest news, tutorials and special offers!",
				"descriptioncolor"			=> "#ffffff",
				"bulletedlist"				=> "",
				"bulletedlistcolor"			=> "#ffffff",
				"privacy"					=> "We respect your privacy. Your information is safe and will never be shared.",
				"privacycolor"				=> "#ffffff",
				"image"						=> "",
				"video"						=> "",
				"videoautoplay"				=> 0,
				"videoautoclose"			=> 0,
				"ribbon"					=> WONDERPLUGIN_POPUP_URL . "skins/ribbon-0.png",
				"ribboncss"					=> "top:-8px;left:-8px;",
				"customcontent"				=> "",
					
				"showprivacy"				=> 0,
				"showribbon"				=> 0,
				"showclosetip"				=> 0,
				"closetip"					=> "Don't miss out. Subscribe today.",
		
				"hidebarstyle"				=> "donotshow",
				"hidebartitle"				=> "Subscribe To Our Newsletter",
				"hidebarbgcolor"			=> "#4791d6",
				"hidebarcolor"				=> "#ffffff",
				"hidebarwidth"				=> "same",
				"hidebarpos"				=> "same",
				"hidebarnotshowafteraction"	=> 1,
					
				"showemail"					=> 1,
				"email"						=> "Enter your email address",
				"emailinputwidth"			=> 180,
				"emailfieldname"			=> "EMAIL",
				"showname"					=> 0,
				"name"						=> "Enter your name",
				"nameinputwidth"			=> 180,
				"namefieldname"				=> "NAME",
				"showfirstname"				=> 0,
				"firstname"					=> "Enter your first name",
				"firstnameinputwidth"		=> 180,
				"firstnamefieldname"		=> "FNAME",
				"showlastname"				=> 0,
				"lastname"					=> "Enter your last name",
				"lastnameinputwidth"		=> 180,
				"lastnamefieldname"			=> "LNAME",
				"showphone"					=> 0,
				"phone"						=> "Enter your phone number",
				"phoneinputwidth"			=> 180,
				"phonefieldname"			=> "PHONE",
				"showcompany"				=> 0,
				"company"					=> "Enter your company name",
				"companyinputwidth"			=> 180,
				"companyfieldname"			=> "COMPANY",
				"showzip"					=> 0,
				"zip"						=> "Enter your zip code",
				"zipinputwidth"				=> 180,
				"zipfieldname"				=> "ZIP",
				"showaction"				=> 1,
				"action"					=> "Subscribe Now",
				"actioncss"					=> "wonderplugin-popup-btn-green",
				"showcancel"				=> 1,
				"cancel"					=> "No Thanks",
				"cancelcss"					=> "wonderplugin-popup-btn-green",
				"cancelastext"				=> 1,
				"canceltextcolor"			=> "#f0f0f0",
				"showgrecaptcha"			=> 0,
				"grecaptchasitekey"			=> "",
				"grecaptchasecretkey"		=> "",

				"showmessage"				=> 0,
				"message"					=> "Enter your message",
				"messageinputwidth"			=> 240,
				"messageinputheight"		=> 120,
				"messagefieldname"			=> "MESSAGE",
															
				"inanimation"				=> "slideInDown",
				"outanimation"				=> "slideOutUp",
					
				"template"					=>	"<div class=\"wonderplugin-box-container\">\r\n\t<div class=\"wonderplugin-box-bg\"></div>\r\n\t<div class=\"wonderplugin-box-dialog\">\r\n\t\t<div class=\"wonderplugin-box-content\">\r\n\t\t\t<div class=\"wonderplugin-box-top\">\r\n\t\t\t\t<div class=\"wonderplugin-box-logo\"><img alt=\"\" src=\"__LOGO__\"></div>\r\n\t\t\t\t<div class=\"wonderplugin-box-heading\">__HEADING__</div>\r\n\t\t\t\t<div class=\"wonderplugin-box-formcontainer\">__FORM__</div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"wonderplugin-box-bottom\">\r\n\t\t\t\t<div class=\"wonderplugin-box-privacy\">__PRIVACY__</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t\t<div class=\"wonderplugin-box-ribbon\"><img src=\"__RIBBON__\"></div>\r\n\t\t<div class=\"wonderplugin-box-closetip\">__CLOSETIP__</div>\r\n\t\t<div class=\"wonderplugin-box-closebutton\">&#215;</div>\r\n\t</div>\r\n\t<div class=\"wonderplugin-box-fullscreenclosebutton\">&#215;</div>\r\n</div>",
				"css"						=>	"/* google fonts */\r\n@import url(https://fonts.googleapis.com/css?family=Open+Sans);\r\n\r\n/* DO NOT CHANGE, container */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-container {\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding-left: 0px;\r\n\tpadding-right: 0px;\r\n\ttext-align: center;\r\n\tfont-size: 12px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n}\r\n\r\n/* DO NOT CHANGE, the dialog, including content and close button,  */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-dialog {\r\n\t-webkit-overflow-scrolling: touch;\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmax-height: 100%;\r\n\tmargin: 0 auto;\r\n\tpadding: 0;\r\n}\r\n\r\n/* overlay background */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bg {\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tposition: absolute;\r\n\ttop: 0;\r\n\tleft: 0;\r\n\twidth: 100%;\r\n\theight: 100%;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton {\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tposition: absolute;\r\n\twidth: 28px;\r\n\theight: 28px;\r\n\tborder-radius: 14px;\r\n\tcursor: pointer;\r\n\tline-height: 30px;\r\n\tfont-size: 24px;\r\n\tfont-family: Arial, sans-serif;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closebutton:hover {\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip {\r\n\tbox-sizing: border-box;\r\n\tdisplay: none;\r\n\tposition: absolute;\r\n\tbottom: 100%;\r\n\tcolor: #fff;\r\n\tbackground-color: #dd3333;\r\n\tborder-radius: 4px;\r\n\tfont-size: 14px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\t -webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n\tmargin: 0;\r\n\tpadding: 12px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-closetip:after {\r\n\tposition: absolute;\r\n\tcontent: \" \";\r\n\twidth: 0;\r\n\theight: 0;\r\n\tborder-style: solid;\r\n\tborder-width: 6px 6px 0 6px;\r\n\tborder-color: #dd3333 transparent transparent transparent;\r\n\ttop: 100%;\r\n}\r\n\r\n/* close button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-fullscreenclosebutton {\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tdisplay: none;\r\n\tposition: fixed;\r\n\ttop: 18px;\r\n\tright: 18px;\r\n\tcursor: pointer;\r\n\tfont-size: 36px;\r\n\tfont-family: Arial, sans-serif;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n/* close button hover effect */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-fullscreenclosebutton:hover {\r\n}\r\n\r\n/* content */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-content {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tmax-height: 100%;\r\n\tbox-sizing: border-box;\r\n\toverflow: auto;\r\n\t-webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n\tcolor: #fff;\r\n}\r\n\r\n/* top part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-top {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 4px 12px 0px;\r\n\tclear:both;\r\n}\r\n\r\n/* bottom part of the content box */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-bottom {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 0px 12px 4px;\r\n\tclear:both;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-logo {\r\n\tdisplay: inline-block;\r\n\tbox-sizing: border-box;\r\n\tposition: relative;\r\n\tvertical-align: middle;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-logo img {\r\n\tdisplay: block;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tmargin: 0 auto;\r\n\tpadding: 0;\r\n\tmax-width: 100%;\r\n}\r\n\r\n/* heading */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-heading {\r\n\tposition: relative;\r\n\tdisplay: inline-block;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 16px;\r\n\tfont-weight: 400;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tline-height: 1.2em;\r\n\tmargin: 0 auto;\r\n\tpadding: 8px;\r\n}\r\n\r\n/* email form */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer {\r\n\tposition: relative;\r\n\tdisplay: inline-block;\r\n\tbox-sizing: border-box;\r\n\ttext-align: center;\r\n\tmargin: 0 auto;\r\n\tpadding: 0px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formbefore {\r\n\tdisplay: block;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formafter {\r\n\tdisplay: none;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formmessage {\r\n\tdisplay: none;\r\n\tcolor: #eeee22;\r\n\tfont-size: 14px;\r\n\ttext-align: left;\r\n\tmargin: 0px 4px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-afteractionmessage {\r\n\tcolor: #eeee22;\r\n\tfont-size: 14px;\r\n\tdisplay: inline-block;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer textarea {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 4px auto;\r\n\tborder-radius: 4px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-formcontainer input[type=text] {\r\n\tcolor: #333;\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tmax-width: 100%;\r\n\tfont-size: 12px;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tpadding: 8px;\r\n\tmargin: 8px 4px;\r\n\tborder-radius: 0px;\r\n}\r\n\r\n/* subscribe now button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-action {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tfont-size: 14px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\twidth: auto;\r\n\tmargin: 8px;\r\n\tpadding: 8px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-afteractionbutton {\r\n\tposition: relative;\r\n\tdisplay: inline-block;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 14px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\twidth: auto;\r\n\tmargin: 8px;\r\n\tpadding: 8px;\r\n}\r\n\r\n/* no thanks button */\r\n#wonderplugin-box-POPUPID .wonderplugin-box-cancel {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tdisplay: inline-block;\r\n\tfont-size: 12px;\r\n\tfont-weight: bold;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tcursor: pointer;\r\n\tmargin: 8px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-privacy {\r\n\tposition: relative;\r\n\tbox-sizing: border-box;\r\n\tfont-size: 12px;\r\n\tline-height: 1.2em;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tmargin: 0 auto;\r\n\tpadding: 6px 0px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon {\r\n\tbox-sizing: border-box;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n\tposition: absolute;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-ribbon img {\r\n\tposition: relative;\r\n\tdisplay: block;\r\n\tbox-sizing: border-box;\r\n\tmax-width: 100%;\r\n\tmargin: 0;\r\n\tpadding: 0;\r\n}\r\n\r\n#wonderplugin-box-hidebar-POPUPID {\r\n\tpadding: 4px 8px;\r\n\tbox-sizing: border-box;\r\n \t-webkit-font-smoothing: antialiased;\r\n\t-moz-osx-font-smoothing: grayscale;\r\n}\r\n\r\n#wonderplugin-box-hidebar-POPUPID:before {\r\n\tdisplay: inline-block;\r\n\tvertical-align: middle;\r\n\tfont-family: Arial, sans-serif;\r\n\tfont-size: 24px;\r\n\tfont-weight: 400;\r\n\tcontent: \"+\";\r\n\tmargin: 0px 8px 0px 0px;\r\n}\r\n\r\n#wonderplugin-box-hidebar-POPUPID .wonderplugin-box-hidebar-title {\r\n\tdisplay: inline-block;\r\n\tvertical-align: middle;\r\n\tfont-family: Open Sans, Helvetica, Lucida, Arial, sans-serif;\r\n\tfont-size: 13px;\r\n\tfont-weight: bold;\r\n\tline-height: 24px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-recaptcha {\r\n\tdisplay: inline-block;\r\n\tmargin: 0 auto;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-recaptcha-container {\r\n\tdisplay: inline-block;\r\n\tvertical-align: bottom;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-form-highlight {\r\n\tborder: 1px dashed #ff0000;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-group {\r\n    padding: 4px 0;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-title {\r\n    font-weight: bold;\r\n    padding-right: 16px;\r\n}\r\n\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-checkbox,\r\n#wonderplugin-box-POPUPID .wonderplugin-box-mailchimp-interest-radio {\r\n    padding-right:16px;\r\n}",
					
				"displaypagerules"			=> '[{"action":1,"rule":"allpagesposts"}]',
				"displaydevicerules"		=> '[{"action":1,"rule":"alldevices"}]',
					
				"displayonpageload"			=> 1,
				"displayonpagescrollpercent"	=> 0,
				"displayonpagescrollpixels"		=> 0,
				"displayonpagescrollcssselector"	=> 0,
				"displayonuserinactivity"	=> 0,
				"displayonclosepage"		=> 0,
				"displaydelay"				=> 3,
				"displaypercent"			=> 80,
				"displaypixels"				=> 600,
				"displaycssselector"		=> "",
				"displayinactivity"			=> 60,
				"displaysensitivity"		=> 20,
					
				"retargetnoshowaction"		=> 365,
				"retargetnoshowactionunit"	=> "days",
				"retargetnoshowcancel"		=> 60,
				"retargetnoshowcancelunit"	=> "days",
				"retargetnoshowclose"		=> 30,
				"retargetnoshowcloseunit"	=> "days",
					
				"afteraction"				=> "display",
				"redirecturl"				=> "",
				"redirecturlpassparams"		=> "passpost",
				"afteractionmessage" 		=> "Thanks for signing up. You must confirm your email address before we can send you. Please check your email and follow the instructions.",
				"afteractionbutton"			=> "Close",
				"closeafterbutton"			=> 1,
				"redirectafterbutton"		=> 0,
				"redirectafterbuttonurl"	=> "",
				"redirectafterbuttonpassparams"	=> "passpost",
					
				"displayloading"			=> 1,
				"loadingimage"				=> WONDERPLUGIN_POPUP_URL . "skins/loading-2.gif",
					
				"invalidemailmessage"		=> "The email address is invalid.",
				"fieldmissingmessage"		=> "Please fill in the required field.",
				"alreadysubscribedmessage"	=> "The email address has already subscribed.",
				"generalerrormessage"		=> "Something went wrong. Please try again later.",
				"displaydetailedmessage"		=> 0,
					
				"subscription" 				=> "noservice",
				"mailchimpdoubleoptin"		=> 1,
				"icontactdoubleoptin"		=> 0,
				"infusionsoftdoubleoptin"	=> 0,
				"savetolocal"				=> 0,
				"emailnotify"				=> 0,
				"emailto"					=> "",
				"emailsubject"				=> "Your list has gained a new subscriber",
				"getresponseautoresponder"	=> 1,
					
				"mailpoet3sendconfirmationemail"	=> 1,
				"mailpoet3schedulewelcomeemail"		=> 1,
					
				"enablegoogleanalytics"		=> 0,
				"gaid"						=> "",
				"gaeventcategory"			=> "Popup",
				"gaeventlabel"				=> "",
					
				"enablelocalanalytics"		=> 0,
					
				"customcss"					=> "",
				"customjs"					=> ""
			)
		);

		foreach($popup_defaults as $type => $defaults)
		{
			foreach($popup_comm_defaults as $key => $value)
			{
				$popup_defaults[$type][$key] = $value;
			}
		}
		
		if (empty($config))
		{
			$config = $popup_defaults['lightbox'];
		}
		else
		{
			if ($id >= 0)
			{
				if (!isset($config['enableretarget']))
					$config['enableretarget'] = 1;
				
				if (!isset($config['uniquevideoiframeid']))
					$config['uniquevideoiframeid'] = 0;

				if (!isset($config['removeinlinecss']))
					$config['removeinlinecss'] = 1;
			}
			
			$type = $config['type'];
			if ( isset($popup_defaults[$type]) )
			{
				foreach ($popup_defaults[$type] as $key => $value)
				{
					if (!isset($config[$key]))
						$config[$key] = $value;
				}	
			}
		}
		?>
		
		<form id="wonderplugin_popup_form" method="post" action="<?php echo admin_url('admin.php?page=wonderplugin_popup_edit_item') . ( ($id >= 0) ? '&itemid=' . $id : '') ?>">
		
		<?php wp_nonce_field('wonderplugin-popup', 'wonderplugin-popup-saveform'); ?>
		
		<input name="wonderplugin-popup-skin" type="hidden" id="wonderplugin-popup-skin" value="<?php echo $config['skin']; ?>"/>
		
		<div style="display:none;">

			<?php 
				$this->langlist = array();
				$this->default_lang = '';
				$this->currentlang = '';
				if (class_exists('SitePress'))
				{
					$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc');

					if ( !empty($languages) )
					{
						$this->default_lang = apply_filters('wpml_default_language', NULL );
						$this->currentlang = apply_filters('wpml_current_language', NULL );
						foreach($languages as $key => $lang)
						{
							$lang_item = array(
									'code' => $lang['code'],
									'translated_name' => $lang['translated_name']
							);
							if ($key == $this->default_lang)
								array_unshift($this->langlist, $lang_item);
							else
								array_push($this->langlist, $lang_item);
						}				
					}
				}
			?>
			<div id="wonderplugin-popup-langlist" style="display:none;"><?php echo json_encode($this->langlist); ?></div>
			<div id="wonderplugin-popup-defaultlang" style="display:none;"><?php echo $this->default_lang; ?></div>
			<div id="wonderplugin-popup-currentlang" style="display:none;"><?php echo $this->currentlang; ?></div>

			<input name="wonderplugin-popup-type" id="wonderplugin-popup-type" type="hidden" value="<?php echo $config['type']; ?>">
			<input name="wonderplugin-popup-id" id="wonderplugin-popup-id" type="hidden" value="<?php echo $id; ?>">
			<div id="wonderplugin-popup-pluginfolder" style="display:none;"><?php echo WONDERPLUGIN_POPUP_URL; ?></div>
			<div id="wonderplugin-popup-versiontype" style="display:none;"><?php echo WONDERPLUGIN_POPUP_VERSION_TYPE; ?></div>
			<div id="wonderplugin-popup-displaypagerules" style="display:none;"><?php echo $config['displaypagerules']; ?></div>
			<div id="wonderplugin-popup-displaydevicerules" style="display:none;"><?php echo $config['displaydevicerules']; ?></div>
			<?php if (!empty($this->langlist)) { ?>
				<div id="wonderplugin-popup-displaylangrules" style="display:none;"><?php echo $config['displaylangrules']; ?></div>
			<?php } ?>
			<?php 
				if (isset($options['keepstate'])) 
					echo '<div id="wonderplugin-popup-keepstate" style="display:none;"></div>'; 
			?>
			<?php 
				$pages = get_pages();
				$pagelist = array();
				foreach ( $pages as $page ) 
				{
					$pagelist[] = array(
						'ID' => $page->ID,
						'post_title' => $page ->post_title
					);
				}
			?>
			<div id="wonderplugin-popup-pagelist" style="display:none;"><?php echo htmlspecialchars(json_encode($pagelist)); ?></div>
			<?php 
				$cats = get_categories();
				$catlist = array();
				foreach ( $cats as $cat )
				{
					$catlist[] = array(
							'ID' => $cat->cat_ID,
							'cat_name' => $cat ->cat_name
					);
				}
			?>
			<div id="wonderplugin-popup-catlist" style="display:none;"><?php echo json_encode($catlist); ?></div>
			<?php
			$custom_post_types = get_post_types( array('_builtin' => false), 'objects' );
			$custom_post_list = array();
			foreach($custom_post_types as $custom_post)
			{
				$custom_post_list[] = array(
					'name' => $custom_post->name,
				);
			}
			?>
			<div id="wonderplugin-popup-custompostlist" style="display:none;"><?php echo json_encode($custom_post_list); ?></div>
			<?php
			$init_option = 'wonder-popup-init';
			$init = get_option($init_option);
			if ($init == false)
			{
				update_option($init_option, time());
				$init = time();
			}	
			?>
			<div id="<?php echo $init_option; ?>" style="display:none;"><?php echo $init; ?></div>

		</div>
		<div style="margin-top:24px;">
		<div class="wonderplugin-left-menu">
			<ul class="wonderplugin-tab-buttons wonderplugin-tab-buttons-vertical" id="wp-vertical-toolbar" data-panelsid="wonderplugin-popup-panels">
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical wonderplugin-tab-button-selected"><span class="wonderplugin-icon-count">1</span><?php _e( 'Design', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical"><span class="wonderplugin-icon-count">2</span><?php _e( 'Display Rules', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical"><span class="wonderplugin-icon-count">3</span><?php _e( 'Email Service', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical"><span class="wonderplugin-icon-count">4</span><?php _e( 'Autoresponder', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical"><span class="wonderplugin-icon-count">5</span><?php _e( 'Action', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical"><span class="wonderplugin-icon-count">6</span><?php _e( 'Analytics', 'wonderplugin_popup' ); ?></li>
				<li class="wonderplugin-tab-button wonderplugin-tab-button-vertical"><span class="dashicons dashicons-admin-tools" style="margin-right:8px;"></span><?php _e( 'Advanced Options', 'wonderplugin_popup' ); ?></li>
			</ul>
			<div style="margin:24px auto;"><input name='wonderplugin-popup-save' id='wonderplugin-popup-save' class="button button-primary button-hero" type="submit" value="<?php _e( 'Save & Publish', 'wonderplugin_popup' ); ?>"></input></div>
			<div style="margin:24px auto;"><label class="wonderplugin-switch <?php if ($config['status']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-status" value="1" <?php if ($config['status']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Active</span><span class="wonderplugin-switch-label-unchecked">Paused</span></label></div>
			<div style="margin:24px auto;"><label><input type="checkbox" id="wonderplugin-popup-loggedinonly" name="wonderplugin-popup-loggedinonly" value="1" <?php if ($config['loggedinonly']) echo 'checked'; ?>>Only enable for logged in users</label></div>
		</div>
		
		<div class="wonderplugin-right-content">
			<ul class="wonderplugin-tabs wonderplugin-tabs-vertical"  id="wonderplugin-popup-panels">
				<li class="wonderplugin-tab wonderplugin-tab-vertical wonderplugin-tab-selected">	
					
					<div class="wonderplugin-popup-preview-wrapper">
					<div class="wonderplugin-popup-preview-container">
						<div id="wonderplugin-popup-preview"></div>
					</div>
					<div style="position:absolute;top:8px;left:8px;">
					<input name='wonderplugin-popup-switchskin' id='wonderplugin-popup-switchskin' class="button wonderplugin-popup-btn-orange button-hero" type="button" value="<?php _e( 'Switch Template', 'wonderplugin_popup' ); ?>"></input>
					</div>
					<div id="wonderplugin-popup-preview-collapse" class="dashicons dashicons-arrow-up-alt2" data-open=1></div>
					</div>
					
					<div class="wonderplugin-design-options">
						
						<ul class="wonderplugin-tab-buttons wonderplugin-tab-buttons-horizontal wonderplugin-tab-buttons-horizontal-withrightbutton" id="wp-design-toolbar" data-panelsid="wonderplugin-popup-design-panels">
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal wonderplugin-tab-button-selected"><span class="dashicons dashicons-admin-generic" style="margin-right:8px;"></span><?php _e( 'General', 'wonderplugin_popup' ); ?></li>
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-edit" style="margin-right:8px;"></span><?php _e( 'Content', 'wonderplugin_popup' ); ?></li>
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-slides" style="margin-right:8px;"></span><?php _e( 'Background', 'wonderplugin_popup' ); ?></li>
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal" id="wonderplugin-tab-button-form"><span class="dashicons dashicons-email-alt" style="margin-right:8px;"></span><?php _e( 'Form', 'wonderplugin_popup' ); ?></li>
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal" style="display:<?php echo ($config['type'] != 'embed') ? 'block' : 'none'; ?>;"><span class="dashicons dashicons-controls-repeat" style="margin-right:8px;"></span><?php _e( 'Animation', 'wonderplugin_popup' ); ?></li>
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-align-left" style="margin-right:8px;"></span><?php _e( 'Template', 'wonderplugin_popup' ); ?></li>
							<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-admin-generic" style="margin-right:8px;"></span><?php _e( 'CSS', 'wonderplugin_popup' ); ?></li>
							<div class="wonderplugin-popup-toolbar"><div class="button button-primary wonderplugin-popup-refresh">Refresh Preview</div></div>
							<div style="clear:both;"></div>
						</ul>
						
						<ul class="wonderplugin-tabs wonderplugin-tabs-horizontal" id="wonderplugin-popup-design-panels">
							<li class="wonderplugin-tab wonderplugin-tab-horizontal wonderplugin-tab-selected">
								
								<table class="wonderplugin-form-table-noborder">
									
									<tr>
										<th>Display type:</th>
										<td><?php echo $popup_type[$config['type']]; ?></td>
									</tr>
									
									<tr>
										<th>Name (not displayed on the PopUp):</th>
										<td><input name="wonderplugin-popup-popupname" type="text" id="wonderplugin-popup-popupname" value="<?php echo $config['popupname']; ?>" class="wonderplugin-popup-option regular-text" /></td>
									</tr>
			
									<tr style="display:<?php echo ($config['type'] == 'bar') ? 'none': 'table-row'; ?>;">
										<th>Width (px):</th>
										<td>
										<label><input name="wonderplugin-popup-width" type="number" id="wonderplugin-popup-width" value="<?php echo $config['width']; ?>" class="wonderplugin-popup-option small-text" /></label>
										</td>
									</tr>
									
									<tr style="display:<?php echo ($config['type'] == 'bar') ? 'none': 'table-row'; ?>;">
										<th>Maximum width (percent):</th>
										<td>
										<label><input name="wonderplugin-popup-maxwidth" type="number" id="wonderplugin-popup-maxwidth" value="<?php echo $config['maxwidth']; ?>" class="wonderplugin-popup-option small-text" /></label>
										</td>
									</tr>
									
									<tr style="display:<?php echo ($config['type'] == 'embed') ? 'none' : 'table-row'; ?>;">
										<th>Margin on top and bottom of the Popup (px):</th>
										<td>
										<label><input name="wonderplugin-popup-mintopbottommargin" type="number" id="wonderplugin-popup-mintopbottommargin" value="<?php echo $config['mintopbottommargin']; ?>" class="wonderplugin-popup-option small-text" /></label>
										</td>
									</tr>
									
									<tr style="display:<?php echo ($config['type'] == 'slidein') ? 'table-row' : 'none'; ?>;">
									<th>Slide in position:</th>
										<td>
										<label><select name="wonderplugin-popup-slideinposition" id="wonderplugin-popup-slideinposition" class="wonderplugin-popup-option">
										<option value="bottom-right" <?php if ($config['slideinposition'] == "bottom-right") echo "selected"; ?>>Bottom Right</option>
										<option value="bottom-left" <?php if ($config['slideinposition'] == "bottom-left") echo "selected"; ?>>Bottom Left</option>
										<option value="bottom" <?php if ($config['slideinposition'] == "bottom") echo "selected"; ?>>Bottom</option>
										</select></label>
									</td>
									</tr>
										
									<tr style="display:<?php echo ($config['type'] == 'bar') ? 'table-row' : 'none'; ?>;">
									<th>Notification bar position:</th>
										<td>
										<label><select name="wonderplugin-popup-barposition" id="wonderplugin-popup-barposition" class="wonderplugin-popup-option">
										<option value="bottom" <?php if ($config['barposition'] == "bottom") echo "selected"; ?>>Bottom</option>
										<option value="top" <?php if ($config['barposition'] == "top") echo "selected"; ?>>Top</option>
										</select></label>
										<br><label><input type="checkbox" name="wonderplugin-popup-barfloat" id="wonderplugin-popup-barfloat" value="1" <?php if ($config['barfloat']) echo 'checked'; ?> class="wonderplugin-popup-option"> Float on top of the web page</label>
									</td>
									</tr>
									
									<tr style="display:<?php echo ($config['type'] == 'lightbox' || $config['type'] == 'slidein') ? 'table-row' : 'none'; ?>;">
										<th>Fullscreen mode:</th>
										<td>
										<label><input type="checkbox" name="wonderplugin-popup-fullscreen" id="wonderplugin-popup-fullscreen" value="1" <?php if ($config['fullscreen']) echo 'checked'; ?> class="wonderplugin-popup-option"> Go to full screen mode when the screen width is less than (px) </label>
										<label><input name="wonderplugin-popup-fullscreenwidth" type="number" id="wonderplugin-popup-fullscreenwidth" value="<?php echo $config['fullscreenwidth']; ?>" class="wonderplugin-popup-option small-text" /></label>
										</td>
									</tr>
									
								</table>
								
							</li>
						
							<li class="wonderplugin-tab wonderplugin-tab-horizontal">
								
								<table class="wonderplugin-form-table-noborder">
									
									<tr id="wonderplugin-popup-design-logo">
										<th>Logo image URL:</th>
										<td></td><td></td>
										<td><input name="wonderplugin-popup-logo" type="text" id="wonderplugin-popup-logo" value="<?php echo $config['logo']; ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option regular-text" />
										<input type='button' class='button wonderplugin-popup-select-image' data-textid='wonderplugin-popup-logo' id='wonderplugin-popup-logo-select-image' value='Upload' />
										<span data-textid='wonderplugin-popup-logo' class="wonderplugin-popup-clear-image">Clear</span>
										</td>
									</tr>
									
									<tr id="wonderplugin-popup-design-heading">
										<th>Heading (HTML allowed):</th>
										<td></td><td><input name="wonderplugin-popup-headingcolor" type="text" id="wonderplugin-popup-headingcolor" value="<?php echo $config['headingcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></td>
										<td><input name="wonderplugin-popup-heading" type="text" id="wonderplugin-popup-heading" value="<?php echo esc_html($config['heading']); ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" /></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-tagline">
										<th>Tagline (HTML allowed):</th>
										<td></td><td><input name="wonderplugin-popup-taglinecolor" type="text" id="wonderplugin-popup-taglinecolor" value="<?php echo $config['taglinecolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></td>
										<td><input name="wonderplugin-popup-tagline" type="text" id="wonderplugin-popup-tagline" value="<?php echo esc_html($config['tagline']); ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" /></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-description">
										<th>Description (HTML allowed):</th>
										<td></td><td><input name="wonderplugin-popup-descriptioncolor" type="text" id="wonderplugin-popup-descriptioncolor" value="<?php echo $config['descriptioncolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></td>
										<td><textarea name="wonderplugin-popup-description" id="wonderplugin-popup-description" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" rows="2"><?php echo esc_html($config['description']); ?></textarea></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-bulletedlist">
										<th>Bulleted list (one item per line):</th>
										<td></td><td><input name="wonderplugin-popup-bulletedlistcolor" type="text" id="wonderplugin-popup-bulletedlistcolor" value="<?php echo $config['bulletedlistcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></td>
										<td><textarea name="wonderplugin-popup-bulletedlist" id="wonderplugin-popup-bulletedlist" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" rows="4"><?php echo esc_html($config['bulletedlist']); ?></textarea></td>
									</tr>

									<tr id="wonderplugin-popup-design-image">
										<th>Content image URL:</th>
										<td></td><td></td>
										<td><input name="wonderplugin-popup-image" type="text" id="wonderplugin-popup-image" value="<?php echo $config['image']; ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option regular-text" />
										<input type='button' class='button wonderplugin-popup-select-image' data-textid='wonderplugin-popup-image' id='wonderplugin-popup-image-select-image' value='Upload' />
										<span data-textid='wonderplugin-popup-image' class="wonderplugin-popup-clear-image">Clear</span>
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-design-video">
										<th colspan="3">YouTube, Vimeo, Wistia or other iFrame video URL:</th>
										<td><input name="wonderplugin-popup-video" type="text" placeholder="YouTube, Vimeo, Wistia or other iFrame video URL" id="wonderplugin-popup-video" value="<?php echo $config['video']; ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" />
										</td>
									</tr>

									<tr class="wonderplugin-popup-design-video">
										<th colspan="3"></th>
										<td><p style="font-weight:bold;">OR</p></td>
									</tr>

									<tr class="wonderplugin-popup-design-video">
										<th colspan="3">HTML5 Video URL:</th>
										<td><input name="wonderplugin-popup-videohtml5" type="text" placeholder="HTML5 Video URL" id="wonderplugin-popup-videohtml5" value="<?php echo $config['videohtml5']; ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" />
										</td>
									</tr>

									<tr class="wonderplugin-popup-design-video">
										<th colspan="3">Video options</th>
										<td><label><input type="checkbox" name="wonderplugin-popup-videoautoplay" id="wonderplugin-popup-videoautoplay" value="1" <?php if (isset($config['videoautoplay']) && $config['videoautoplay']) echo 'checked'; ?> class="wonderplugin-popup-option"> Auto play video (Autoplay on page load only works when the video is muted)</label>
										<?php if ($config['type'] != 'embed') { ?>
										<p><label><input type="checkbox" name="wonderplugin-popup-videoautoclose" id="wonderplugin-popup-videoautoclose" value="1" <?php if (isset($config['videoautoclose']) && $config['videoautoclose']) echo 'checked'; ?> class="wonderplugin-popup-option"> Auto close the popup after the video has finished (Only supports YouTube, Vimeo and HTML5)</label></p>
										<?php } ?>
										<p><label><input type="checkbox" name="wonderplugin-popup-videomuted" id="wonderplugin-popup-videomuted" value="1" <?php if (isset($config['videomuted']) && $config['videomuted']) echo 'checked'; ?> class="wonderplugin-popup-option"> Mute video</label></p>
										<p><label><input type="checkbox" name="wonderplugin-popup-videocontrols" id="wonderplugin-popup-videocontrols" value="1" <?php if (isset($config['videocontrols']) && $config['videocontrols']) echo 'checked'; ?> class="wonderplugin-popup-option"> Show HTML5 controls</label></p>
										<p><label><input type="checkbox" name="wonderplugin-popup-videonodownload" id="wonderplugin-popup-videonodownload" value="1" <?php if (isset($config['videonodownload']) && $config['videonodownload']) echo 'checked'; ?> class="wonderplugin-popup-option"> Do not show HTML5 video download button</label></p>
										<p><a href="https://www.wonderplugin.com/wonderplugin-popup/youtube-lightbox-popup/" target="_blank">Tutorial: How to play YouTube, Vimeo, Wistia and HTML5 videos</a></p>
										</td>
									</tr>
									
									<tr id="wonderplugin-popup-design-privacy">
										<th>Privacy (HTML allowed):</th>
										<td>
										<label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showprivacy']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showprivacy" id="wonderplugin-popup-showprivacy" class="wonderplugin-popup-option" value="1" <?php if ($config['showprivacy']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label>
										</td>
										<td>
										<input name="wonderplugin-popup-privacycolor" type="text" id="wonderplugin-popup-privacycolor" value="<?php echo $config['privacycolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></td>
										<td><input name="wonderplugin-popup-privacy" type="text" id="wonderplugin-popup-privacy" value="<?php echo esc_html($config['privacy']); ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" /></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-customcontent">
										<th>Custom Content:</th>
										<td></td><td></td>
										<td><textarea name="wonderplugin-popup-customcontent" id="wonderplugin-popup-customcontent" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" rows="8"><?php echo esc_html($config['customcontent']); ?></textarea></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-ribbon">
										<th>Ribbon image URL:</th>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showribbon']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showribbon" id="wonderplugin-popup-showribbon" class="wonderplugin-popup-option" value="1" <?php if ($config['showribbon']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td></td><td><input name="wonderplugin-popup-ribbon" type="text" id="wonderplugin-popup-ribbon" value="<?php echo $config['ribbon']; ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option regular-text" />
										<input type='button' class='button wonderplugin-popup-select-image' data-textid='wonderplugin-popup-ribbon' id='wonderplugin-popup-ribbon-select-image' value='Upload' />
										<span data-textid='wonderplugin-popup-ribbon' class="wonderplugin-popup-clear-image">Clear</span>
										<br><label>Position CSS: <input name="wonderplugin-popup-ribboncss" type="text" id="wonderplugin-popup-ribboncss" value="<?php echo $config['ribboncss']; ?>" class="wonderplugin-popup-option medium-text" /></label>
										</td>
									</tr>
									
									<tr id="wonderplugin-popup-design-closetip">
										<th>Tip on close:</th>
										<td>
										<label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showclosetip']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showclosetip" id="wonderplugin-popup-showclosetip" class="wonderplugin-popup-option" value="1" <?php if ($config['showclosetip']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label>
										</td>
										<td>
										<td><input name="wonderplugin-popup-closetip" type="text" id="wonderplugin-popup-closetip" value="<?php echo esc_html($config['closetip']); ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" /></td>
									</tr>
								
									<tr id="wonderplugin-popup-design-hidebar" style="display:<?php echo ($config['type'] == 'slidein') ? 'table-row' : 'none'; ?>;">
										<th>Notification bar when the popup is hidden</th>
										<td>
										</td>
										<td>
										<label><select name="wonderplugin-popup-hidebarstyle" id="wonderplugin-popup-hidebarstyle" class="wonderplugin-popup-option">
										  <option value="textbar" <?php if ($config['hidebarstyle'] == "textbar") echo "selected"; ?>>Text Bar</option>
										  <option value="donotshow" <?php if ($config['hidebarstyle'] == "donotshow") echo "selected"; ?>>Do Not Show</option>
										</select></label>
										</td>
										<td>
										<input name="wonderplugin-popup-hidebartitle" type="text" id="wonderplugin-popup-hidebartitle" value="<?php echo $config['hidebartitle']; ?>" class="wonderplugin-popup-contentoption wonderplugin-popup-option large-text" />
										<br><label>Width: <select name="wonderplugin-popup-hidebarwidth" id="wonderplugin-popup-hidebarwidth" class="wonderplugin-popup-option">
										  <option value="same" <?php if ($config['hidebarwidth'] == "same") echo "selected"; ?>>Same as Popup</option>
										  <option value="auto" <?php if ($config['hidebarwidth'] == "auto") echo "selected"; ?>>Auto</option>
										</select></label> <label>Position: <select name="wonderplugin-popup-hidebarpos" id="wonderplugin-popup-hidebarpos" class="wonderplugin-popup-option">
										  <option value="same" <?php if ($config['hidebarpos'] == "same") echo "selected"; ?>>Same as Popup</option>
										  <option value="bottom-right" <?php if ($config['hidebarpos'] == "bottom-right") echo "selected"; ?>>Bottom Right</option>
										  <option value="bottom-left" <?php if ($config['hidebarpos'] == "bottom-left") echo "selected"; ?>>Bottom Left</option>
										  <option value="bottom" <?php if ($config['hidebarpos'] == "bottom") echo "selected"; ?>>Bottom</option>
										</select></label>
										<label class="withcolorpicker">Background color: <input name="wonderplugin-popup-hidebarbgcolor" type="text" id="wonderplugin-popup-hidebarbgcolor" value="<?php echo $config['hidebarbgcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" />
										Text color: <input name="wonderplugin-popup-hidebarcolor" type="text" id="wonderplugin-popup-hidebarcolor" value="<?php echo $config['hidebarcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></label>
										<br><label><input type="checkbox" name="wonderplugin-popup-hidebarnotshowafteraction" id="wonderplugin-popup-hidebarnotshowafteraction" value="1" <?php if ($config['hidebarnotshowafteraction']) echo 'checked'; ?> class="wonderplugin-popup-option"> Hide the bar after the Action button being clicked</label>
										<p style="font-style:italic;">To hide the notification bar after the Action (Subscribe) button being clicked, please goto step 2 Display Rules -> Retargeting, enable the Retargeting Rules.</p>
										</td>
									</tr>

								</table>
							</li>
							
							<li class="wonderplugin-tab wonderplugin-tab-horizontal">
								
								<table class="wonderplugin-form-table-noborder">
								
									<tr>
										<th>Border radius (px):</th>
										<td><label><input name="wonderplugin-popup-radius" type="number" id="wonderplugin-popup-radius" value="<?php echo $config['radius']; ?>" class="wonderplugin-popup-option small-text" />
										Shadow: <input name="wonderplugin-popup-bordershadow" type="text" id="wonderplugin-popup-bordershadow" value="<?php echo $config['bordershadow']; ?>" class="wonderplugin-popup-option regular-text" />
										</label></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-overlaycolor" style="display:<?php echo ($config['type'] == 'lightbox') ? 'table-row' : 'none'; ?>;">
										<th>Overlay background:</th>
										<td><label class="withcolorpicker"><input name="wonderplugin-popup-overlaycolor" type="text" id="wonderplugin-popup-overlaycolor" value="<?php echo $config['overlaycolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" />
										Opacity: <input name="wonderplugin-popup-overlayopacity" type="number" id="wonderplugin-popup-overlayopacity" value="<?php echo $config['overlayopacity']; ?>" step="0.1" min="0" max="1" class="wonderplugin-popup-option small-text" />
										</label><label><input type="checkbox" name="wonderplugin-popup-overlayclose" id="wonderplugin-popup-overlayclose" value="1" <?php if ($config['overlayclose']) echo 'checked'; ?> class="wonderplugin-popup-option"> Close popup when clicking on the overlay</label></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-close">
										<th>Close button:</th>
										<td>
										<div style="float:left;margin:4px 8px 0px 0px;"><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showclose']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showclose" id="wonderplugin-popup-showclose" class="wonderplugin-popup-option" value="1" <?php if ($config['showclose']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label>
										</div>
										<label class="withcolorpicker"><input name="wonderplugin-popup-closecolor" type="text" id="wonderplugin-popup-closecolor" value="<?php echo $config['closecolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" />
										Hover over color: <input name="wonderplugin-popup-closehovercolor" type="text" id="wonderplugin-popup-closehovercolor" value="<?php echo $config['closehovercolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" />
										Background color: <input name="wonderplugin-popup-closebackgroundcolor" type="text" id="wonderplugin-popup-closebackgroundcolor" value="<?php echo $config['closebackgroundcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></label>
										<br>
										<label>Position: <select name="wonderplugin-popup-closeposition" id="wonderplugin-popup-closeposition" class="wonderplugin-popup-option">
										  <option value="top-right-inside" <?php if ($config['closeposition'] == "top-right-inside") echo "selected"; ?>>top-right-inside</option>
										  <option value="top-left-inside" <?php if ($config['closeposition'] == "top-left-inside") echo "selected"; ?>>top-left-inside</option>
										  <option value="top-right-outside" <?php if ($config['closeposition'] == "top-right-outside") echo "selected"; ?>>top-right-outside</option>
										  <option value="top-left-outside" <?php if ($config['closeposition'] == "top-left-outside") echo "selected"; ?>>top-left-outside</option>
										</select></label>
										<label><input type="checkbox" name="wonderplugin-popup-closeshowshadow" id="wonderplugin-popup-closeshowshadow" value="1" <?php if ($config['closeshowshadow']) echo 'checked'; ?> class="wonderplugin-popup-option"> Show shadow</label> 
										<input name="wonderplugin-popup-closeshadow" type="text" id="wonderplugin-popup-closeshadow" value="<?php echo $config['closeshadow']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr id="wonderplugin-popup-design-backgroundcolor">
										<th>Background color:</th>
										<td><label class="withcolorpicker"><input name="wonderplugin-popup-backgroundcolor" type="text" id="wonderplugin-popup-backgroundcolor" value="<?php echo $config['backgroundcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></label>
										</td>
									</tr>
									
									<tr id="wonderplugin-popup-design-backgroundimage">
										<th>Background image URL:</th>
										<td><label><input name="wonderplugin-popup-backgroundimage" type="text" id="wonderplugin-popup-backgroundimage" value="<?php echo $config['backgroundimage']; ?>" class="wonderplugin-popup-option regular-text" />
										<input type='button' class='button wonderplugin-popup-select-image' data-textid='wonderplugin-popup-backgroundimage' id='wonderplugin-popup-backgroundimage-select-image' value='Upload' />
										<span data-textid='wonderplugin-popup-backgroundimage' class="wonderplugin-popup-clear-image">Clear</span>
										</label><br>
										<label>Repeat: <select name="wonderplugin-popup-backgroundimagerepeat" id="wonderplugin-popup-backgroundimagerepeat" class="wonderplugin-popup-option">
										  <option value="repeat" <?php if ($config['backgroundimagerepeat'] == "repeat") echo "selected"; ?>>repeat</option>
										  <option value="no-repeat" <?php if ($config['backgroundimagerepeat'] == "no-repeat") echo "selected"; ?>>no-repeat</option>
										  <option value="repeat-x" <?php if ($config['backgroundimagerepeat'] == "repeat-x") echo "selected"; ?>>repeat-x</option>
										  <option value="repeat-y" <?php if ($config['backgroundimagerepeat'] == "repeat-y") echo "selected"; ?>>repeat-y</option>
										</select>
										Position: <input name="wonderplugin-popup-backgroundimageposition" type="text" id="wonderplugin-popup-backgroundimageposition" value="<?php echo $config['backgroundimageposition']; ?>" class="wonderplugin-popup-option medium-text" /></label>
										</td>
									</tr>
									
									<tr id="wonderplugin-popup-design-upperlower">
										<th>Upper and lower:</th>
										<td><label class="withcolorpicker">
										Upper part: <input name="wonderplugin-popup-backgroundtopcolor" type="text" id="wonderplugin-popup-backgroundtopcolor" value="<?php echo $config['backgroundtopcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" />
										Bottom part: <input name="wonderplugin-popup-backgroundbottomcolor" type="text" id="wonderplugin-popup-backgroundbottomcolor" value="<?php echo $config['backgroundbottomcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" />
										</label></td>
									</tr>
									
									<tr id="wonderplugin-popup-design-leftright">
										<th>Left and right:</th>
										<td>
										<label>Left part width percentage: <input name="wonderplugin-popup-leftwidth" type="number" id="wonderplugin-popup-leftwidth" value="<?php echo $config['leftwidth']; ?>" class="wonderplugin-popup-option small-text" />%</label>
										</td>
									</tr>
									
								</table>
								
							</li>
							
							<li class="wonderplugin-tab wonderplugin-tab-horizontal" id="wonderplugin-tab-form">
							
								<h3>Fields</h3>
								<div style="display:none;">
								<input name="wonderplugin-popup-fieldorder" type="text" id="wonderplugin-popup-fieldorder" value="<?php echo esc_html($config['fieldorder']); ?>" class="wonderplugin-popup-option">
								<input name="wonderplugin-popup-customfields" type="text" id="wonderplugin-popup-customfields" value="<?php echo esc_html($config['customfields']); ?>" class="wonderplugin-popup-option">
								</div>
								<table class="wonderplugin-form-table-noborder wonderplugin-popup-formfields">
									
									<tr>
										<th></th>
										<td></<td>
										<td></<td>
										<td>Size (px)</td>
										<td>Field name</td>
										<td>Placeholder</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-email">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Email</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showemail']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showemail" id="wonderplugin-popup-showemail" class="wonderplugin-popup-option" value="1" <?php if ($config['showemail']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-emailinputwidth" type="number" id="wonderplugin-popup-emailinputwidth" value="<?php echo $config['emailinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-emailfieldname" type="text" id="wonderplugin-popup-emailfieldname" value="<?php echo $config['emailfieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-email" type="text" id="wonderplugin-popup-email" value="<?php echo $config['email']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
																		
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-name">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Name</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showname']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showname" id="wonderplugin-popup-showname" class="wonderplugin-popup-option" value="1" <?php if ($config['showname']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-nameinputwidth" type="number" id="wonderplugin-popup-nameinputwidth" value="<?php echo $config['nameinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-namefieldname" type="text" id="wonderplugin-popup-namefieldname" value="<?php echo $config['namefieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-name" type="text" id="wonderplugin-popup-name" value="<?php echo $config['name']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-firstname">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>First name</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showfirstname']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showfirstname" id="wonderplugin-popup-showfirstname" class="wonderplugin-popup-option" value="1" <?php if ($config['showfirstname']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-firstnameinputwidth" type="number" id="wonderplugin-popup-firstnameinputwidth" value="<?php echo $config['firstnameinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-firstnamefieldname" type="text" id="wonderplugin-popup-firstnamefieldname" value="<?php echo $config['firstnamefieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-firstname" type="text" id="wonderplugin-popup-firstname" value="<?php echo $config['firstname']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-lastname">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Last name</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showlastname']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showlastname" id="wonderplugin-popup-showlastname" class="wonderplugin-popup-option" value="1" <?php if ($config['showlastname']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-lastnameinputwidth" type="number" id="wonderplugin-popup-lastnameinputwidth" value="<?php echo $config['lastnameinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-lastnamefieldname" type="text" id="wonderplugin-popup-lastnamefieldname" value="<?php echo $config['lastnamefieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-lastname" type="text" id="wonderplugin-popup-lastname" value="<?php echo $config['lastname']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-phone">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Phone</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showphone']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showphone" id="wonderplugin-popup-showphone" class="wonderplugin-popup-option" value="1" <?php if ($config['showphone']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-phoneinputwidth" type="number" id="wonderplugin-popup-phoneinputwidth" value="<?php echo $config['phoneinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-phonefieldname" type="text" id="wonderplugin-popup-phonefieldname" value="<?php echo $config['phonefieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-phone" type="text" id="wonderplugin-popup-phone" value="<?php echo $config['phone']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-company">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Company name</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showcompany']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showcompany" id="wonderplugin-popup-showcompany" class="wonderplugin-popup-option" value="1" <?php if ($config['showcompany']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-companyinputwidth" type="number" id="wonderplugin-popup-companyinputwidth" value="<?php echo $config['companyinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-companyfieldname" type="text" id="wonderplugin-popup-companyfieldname" value="<?php echo $config['companyfieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-company" type="text" id="wonderplugin-popup-company" value="<?php echo $config['company']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-zip">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Zip code</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showzip']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showzip" id="wonderplugin-popup-showzip" class="wonderplugin-popup-option" value="1" <?php if ($config['showzip']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-zipinputwidth" type="number" id="wonderplugin-popup-zipinputwidth" value="<?php echo $config['zipinputwidth']; ?>" class="wonderplugin-popup-option small-text" /></td>
										<td><input name="wonderplugin-popup-zipfieldname" type="text" id="wonderplugin-popup-zipfieldname" value="<?php echo $config['zipfieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-zip" type="text" id="wonderplugin-popup-zip" value="<?php echo $config['zip']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
									
									<tr class="wonderplugin-popup-form-row" id="wonderplugin-popup-design-message">
										<th><span class="wonderplugin-form-sortup dashicons dashicons-arrow-up-alt2"></span><span class="wonderplugin-form-sortdown dashicons dashicons-arrow-down-alt2"></span></th>
										<td>Message</td>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showmessage']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showmessage" id="wonderplugin-popup-showmessage" class="wonderplugin-popup-option" value="1" <?php if ($config['showmessage']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-messageinputwidth" type="number" id="wonderplugin-popup-messageinputwidth" value="<?php echo $config['messageinputwidth']; ?>" class="wonderplugin-popup-option small-text" />
										by <input name="wonderplugin-popup-messageinputheight" type="number" id="wonderplugin-popup-messageinputheight" value="<?php echo $config['messageinputheight']; ?>" class="wonderplugin-popup-option small-text" />
										</td>
										<td><input name="wonderplugin-popup-messagefieldname" type="text" id="wonderplugin-popup-messagefieldname" value="<?php echo $config['messagefieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
										<td><input name="wonderplugin-popup-message" type="text" id="wonderplugin-popup-message" value="<?php echo $config['message']; ?>" class="wonderplugin-popup-option regular-text" />
										</td>
									</tr>
						</table>
						
						<div><input type="button" class="button button-primary" id="wonderplugin-popup-form-addcustom" value="Add Custom Field"></input></div>

						<h3>Checkbox</h3>
						<table class="wonderplugin-form-table-noborder">
						<tr>
							<th></th>
							<td></td>
							<td>Must checked</td>
							<td>Field name</td>
							<td>Caption (HTML allowed)</td>
						</tr>
						<tr id="wonderplugin-popup-design-terms">
						<th>Terms of Service</th>
						<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showterms']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showterms" id="wonderplugin-popup-showterms" class="wonderplugin-popup-option" value="1" <?php if ($config['showterms']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
						<td>Yes<label style="display:none;" class="wonderplugin-switch wonderplugin-switch-small wonderplugin-switch-checked"><input type="checkbox" name="wonderplugin-popup-termsrequired" id="wonderplugin-popup-termsrequired" class="wonderplugin-popup-option" value="1" checked><span class="wonderplugin-switch-label-checked">Yes</span><span class="wonderplugin-switch-label-unchecked">No</span></label></td>
						<td><input name="wonderplugin-popup-termsfieldname" type="text" id="wonderplugin-popup-termsfieldname" value="<?php echo $config['termsfieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
						<td><input name="wonderplugin-popup-terms" type="text" id="wonderplugin-popup-terms" value="<?php echo esc_html($config['terms']); ?>" class="wonderplugin-popup-option regular-text" />
						</tr>
						
						<tr id="wonderplugin-popup-design-privacyconsent">
						<th>Privacy Consent</th>
						<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showprivacyconsent']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showprivacyconsent" id="wonderplugin-popup-showprivacyconsent" class="wonderplugin-popup-option" value="1" <?php if ($config['showprivacyconsent']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
						<td>Yes<label style="display:none;" class="wonderplugin-switch wonderplugin-switch-small wonderplugin-switch-checked"><input type="checkbox" name="wonderplugin-popup-privacyconsentrequired" id="wonderplugin-popup-privacyconsentrequired" class="wonderplugin-popup-option" value="1" checked><span class="wonderplugin-switch-label-checked">Yes</span><span class="wonderplugin-switch-label-unchecked">No</span></label></td>
						<td><input name="wonderplugin-popup-privacyconsentfieldname" type="text" id="wonderplugin-popup-privacyconsentfieldname" value="<?php echo $config['privacyconsentfieldname']; ?>" class="wonderplugin-popup-option medium-text" /></td>
						<td><input name="wonderplugin-popup-privacyconsent" type="text" id="wonderplugin-popup-privacyconsent" value="<?php echo esc_html($config['privacyconsent']); ?>" class="wonderplugin-popup-option regular-text" />
						</tr>
						</table>
						
						<h3>Captcha</h3>
						<table class="wonderplugin-form-table-noborder">
						<tr id="wonderplugin-popup-design-grecaptcha">
						<th>Google Recaptcha</th>
						<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showgrecaptcha']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showgrecaptcha" id="wonderplugin-popup-showgrecaptcha" class="wonderplugin-popup-option" value="1" <?php if ($config['showgrecaptcha']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
						<td>
						<table>
						<tr><td>Site key</td><td><input name="wonderplugin-popup-grecaptchasitekey" type="text" id="wonderplugin-popup-grecaptchasitekey" value="<?php echo $config['grecaptchasitekey']; ?>" class="wonderplugin-popup-option regular-text" /></td></tr>
						<tr><td>Secret key</td><td><input name="wonderplugin-popup-grecaptchasecretkey" type="text" id="wonderplugin-popup-grecaptchasecretkey" value="<?php echo $config['grecaptchasecretkey']; ?>" class="wonderplugin-popup-option regular-text" /></td></tr>
						</table>
						</td>
						</tr>
						</table>
						
						<h3>Buttons</h3>			
						<table class="wonderplugin-form-table-noborder">
						
									<tr id="wonderplugin-popup-design-action">
										<th>Action</th>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showaction']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showaction" id="wonderplugin-popup-showaction" class="wonderplugin-popup-option" value="1" <?php if ($config['showaction']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-action" type="text" id="wonderplugin-popup-action" value="<?php echo $config['action']; ?>" class="wonderplugin-popup-option regular-text" />
										<input type='button' class='button wonderplugin-popup-select-button' data-textid='wonderplugin-popup-actioncss' value='Select a button style' />
										<input type='hidden' id='wonderplugin-popup-actioncss' name='wonderplugin-popup-actioncss' class="wonderplugin-popup-option" value='<?php echo $config['actioncss']; ?>'>
										</td>
									</tr>
									
									<?php if ($config['type'] != 'embed') { ?>
									<tr id="wonderplugin-popup-design-cancel">
										<th>Cancel</th>
										<td><label class="wonderplugin-switch wonderplugin-switch-small <?php if ($config['showcancel']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-showcancel" id="wonderplugin-popup-showcancel" class="wonderplugin-popup-option" value="1" <?php if ($config['showcancel']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Show</span><span class="wonderplugin-switch-label-unchecked">Hide</span></label></td>
										<td><input name="wonderplugin-popup-cancel" type="text" id="wonderplugin-popup-cancel" value="<?php echo $config['cancel']; ?>" class="wonderplugin-popup-option regular-text" />
										<input type='button' class='button wonderplugin-popup-select-button' data-textid='wonderplugin-popup-cancelcss' value='Select a button style' />
										<input type='hidden' id='wonderplugin-popup-cancelcss' name='wonderplugin-popup-cancelcss' class="wonderplugin-popup-option" value='<?php echo $config['cancelcss']; ?>'>
										<div>
										<div style="display:inline-block;"><label><input type="checkbox" name="wonderplugin-popup-cancelastext" id="wonderplugin-popup-cancelastext" value="1" <?php if ($config['cancelastext']) echo 'checked'; ?> class="wonderplugin-popup-option"> Display as text</label></div>
										<div style="display:inline-block; line-height:40px;"><input name="wonderplugin-popup-canceltextcolor" type="text" id="wonderplugin-popup-canceltextcolor" value="<?php echo $config['canceltextcolor']; ?>" placeholder="Enter Hex color" class="wonderplugin-popup-option wonderplugin-color-field" /></div>
										</div>
										</td>
									</tr>
									<?php } ?>
									
								</table>
							</li>
							
							<li class="wonderplugin-tab wonderplugin-tab-horizontal" <?php if ($config['type'] == 'embed') echo 'style="display:none;"'; ?>>
							
								<table class="wonderplugin-form-table-noborder">
									<tr>
									<td>Show animation:</td>
									<td><select name="wonderplugin-popup-inanimation" id="wonderplugin-popup-inanimation" class="wonderplugin-popup-option" style="min-width:200px;">
									<?php 
										$inanimation = array("noAnimation", "bounce", "flash", "pulse", "rubberBand", "shake", "swing", "tada", "wobble", "jello", "bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp", "fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig", "flipInX", "flipInY", "lightSpeedIn", "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight", "hinge", "rollIn", "zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp", "slideInDown", "slideInLeft", "slideInRight", "slideInUp");
										foreach($inanimation as $anim)
											echo '<option value="' . $anim . '"' . (($config['inanimation'] == $anim) ? ' selected' : '') . '>' . $anim . '</option>';
									?>
									</select>
									<td>
									<input name='wonderplugin-popup-inanimationpreview' id='wonderplugin-popup-inanimationpreview' class="button button-primary" type="button" value="<?php _e( 'Preview Animation', 'wonderplugin_popup' ); ?>"></input>
									</td>
									</tr>
									
									<tr>
									<td>Hide animation:</td>
									<td><select name="wonderplugin-popup-outanimation" id="wonderplugin-popup-outanimation" class="wonderplugin-popup-option" style="min-width:200px;">
									<?php 
										$outanimation = array("noAnimation", "bounceOut", "bounceOutDown", "bounceOutLeft", "bounceOutRight", "bounceOutUp", "fadeOut", "fadeOutDown", "fadeOutDownBig", "fadeOutLeft", "fadeOutLeftBig", "fadeOutRight", "fadeOutRightBig", "fadeOutUp", "fadeOutUpBig", "flipOutX", "flipOutY", "lightSpeedOut", "rotateOut", "rotateOutDownLeft", "rotateOutDownRight", "rotateOutUpLeft", "rotateOutUpRight", "rollOut", "zoomOut", "zoomOutDown", "zoomOutLeft", "zoomOutRight", "zoomOutUp", "slideOutDown", "slideOutLeft", "slideOutRight", "slideOutUp");	
										foreach($outanimation as $anim)
											echo '<option value="' . $anim . '"' . (($config['outanimation'] == $anim) ? ' selected' : '') . '>' . $anim . '</option>';
									?>
									</select>
									<td>
									<input name='wonderplugin-popup-outanimationpreview' id='wonderplugin-popup-outanimationpreview' class="button button-primary" type="button" value="<?php _e( 'Preview Animation', 'wonderplugin_popup' ); ?>"></input>
									</td>
									</tr>
								</table>
								<div>
								<p>The CSS3 animation is powered by Animation.css (<a href="https://daneden.github.io/animate.css/" target="_blank">https://daneden.github.io/animate.css/</a>). Animate.css is licensed under the MIT license. (<a href="http://opensource.org/licenses/MIT" target="_blank">http://opensource.org/licenses/MIT</a>)</p>	
								</div>	
							</li>
							
							<li class="wonderplugin-tab wonderplugin-tab-horizontal">
								<textarea name="wonderplugin-popup-template" id="wonderplugin-popup-template" class="wonderplugin-popup-option large-text" rows="16"><?php echo $config['template']; ?></textarea>
							</li>
							
							<li class="wonderplugin-tab wonderplugin-tab-horizontal">
								<textarea name="wonderplugin-popup-css" id="wonderplugin-popup-css" class="wonderplugin-popup-option large-text" rows="16"><?php echo $config['css']; ?></textarea>
							</li>
							
						</ul>
						
					</div>
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-vertical">
				
					<ul class="wonderplugin-tab-buttons wonderplugin-tab-buttons-horizontal" id="wp-rules-toolbar" data-panelsid="wonderplugin-popup-display-panels">
						<?php if ($config['type'] != 'embed') { ?>
						<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal wonderplugin-tab-button-selected"><span class="dashicons dashicons-clock" style="margin-right:8px;"></span><?php _e( 'Display Time', 'wonderplugin_popup' ); ?></li>
						<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-smartphone" style="margin-right:8px;"></span><?php if (!empty($this->langlist)) _e( 'Page, Device and Language Rules', 'wonderplugin_popup' ); else _e( 'Page and Device Rules', 'wonderplugin_popup' ); ?></li>
						<?php } ?>
						<li class="wonderplugin-tab-button wonderplugin-tab-button-horizontal <?php if ($config['type'] == 'embed') echo 'wonderplugin-tab-button-selected'; ?>"><span class="dashicons dashicons-controls-repeat" style="margin-right:8px;"></span><?php _e( 'Retargeting', 'wonderplugin_popup' ); ?></li>
						</ul>
					
					<ul class="wonderplugin-tabs wonderplugin-tabs-horizontal" id="wonderplugin-popup-display-panels">
						
						<?php if ($config['type'] != 'embed') { ?>
						<li class="wonderplugin-tab wonderplugin-tab-horizontal wonderplugin-tab-selected">
							
							<h3>Select one or multiple time triggers to show the popup.</h3>
							<h4>The popup will display when any one of the selected criteria is met and it will only display once.</h4>
							
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-displayonpageload" value="1" <?php if ($config['displayonpageload']) echo 'checked'; ?>> Show on page load after </label> <input name="wonderplugin-popup-displaydelay" type="number" id="wonderplugin-popup-displaydelay" value="<?php echo $config['displaydelay']; ?>" class="small-text" /> seconds
							</div>
							
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-displayonpagescrollpercent" value="1" <?php if ($config['displayonpagescrollpercent']) echo 'checked'; ?>> Show on page scroll after </label> <input name="wonderplugin-popup-displaypercent" type="number" id="wonderplugin-popup-displaypercent" value="<?php echo $config['displaypercent']; ?>" class="small-text" /> percent of the page is visible
							</div>
							
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-displayonpagescrollpixels" value="1" <?php if ($config['displayonpagescrollpixels']) echo 'checked'; ?>> Show on page scroll after </label> <input name="wonderplugin-popup-displaypixels" type="number" id="wonderplugin-popup-displaypixels" value="<?php echo $config['displaypixels']; ?>" class="small-text" /> pixels of the page is visible
							</div>
							
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-displayonpagescrollcssselector" value="1" <?php if ($config['displayonpagescrollcssselector']) echo 'checked'; ?>> Show on page scroll after the CSS selector</label> <input name="wonderplugin-popup-displaycssselector" type="text" id="wonderplugin-popup-displaycssselector" value="<?php echo $config['displaycssselector']; ?>" class="medium-text" placeholder=".class or #id" /> is visible
							</div>
							
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-displayonuserinactivity" value="1" <?php if ($config['displayonuserinactivity']) echo 'checked'; ?>> Show after </label> <input name="wonderplugin-popup-displayinactivity" type="number" id="wonderplugin-popup-displayinactivity" value="<?php echo $config['displayinactivity']; ?>" class="small-text" /> seconds user inactivity
							</div>
														
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-displayonclosepage" value="1" <?php if ($config['displayonclosepage']) echo 'checked'; ?>> Show when the visitor is going to close the page.</label>
							<div class="wonderplugin-tab-row-description">This trigger will fire when the visitor moves the mouse cursor close to the top of the web browser, where the address bar and the tabs are located. You can define how far the mouse has to be before the trigger fires. The higher value, the more sensitive. Define the sensitivity distance: <input name="wonderplugin-popup-displaysensitivity" type="number" min="0" id="wonderplugin-popup-displaysensitivity" value="<?php echo $config['displaysensitivity']; ?>" class="wonderplugin-popup-option small-text" /> px.</div>
							</div>
							
							<h3>Close the Popup</h3>
							<div class="wonderplugin-tab-row">
							<label><input type="checkbox" name="wonderplugin-popup-autoclose" value="1" <?php if ($config['autoclose']) echo 'checked'; ?>> Automatically close the popup after </label> <input name="wonderplugin-popup-autoclosedelay" type="number" id="wonderplugin-popup-autoclosedelay" value="<?php echo $config['autoclosedelay']; ?>" class="small-text" /> seconds
							</div>

						</li>
						<?php } ?>
						
						<?php if ($config['type'] != 'embed') { ?>
						<li class="wonderplugin-tab wonderplugin-tab-horizontal">	
						
							<h3>Page Rule</h3>
							<h4>The popup will show when any of the "Show" rule match AND none of the "Don't Show" rule match.</h4>
							<h4>There must be at least one "Show" rule.</h4>
							<ul id="wonderplugin-popup-display-pagerulelist" class="wonderplugin-popup-display-rulelist" data-ruletype="page"></ul>	
							<p><input name='wonderplugin-popup-addpagerule' id='wonderplugin-popup-addpagerule' class="wonderplugin-popup-addrule wonderplugin-popup-btn-orange" data-ruletype="page" type="button" value="<?php _e( 'Add Page Rule', 'wonderplugin_popup' ); ?>"></input></p>
							<p><a href="https://www.wonderplugin.com/wonderplugin-popup/setup-display-rules/" target="_blank">Tutorial: How to setup display rules</a></p>	
							
							<hr></hr>
							<h3>Device Rule</h3>
							<h4>The popup will show when any of the "Show" rule match AND none of the "Don't Show" rule match.</h4>
							<h4>There must be at least one "Show" rule.</h4>
							<ul id="wonderplugin-popup-display-devicerulelist" class="wonderplugin-popup-display-rulelist" data-ruletype="device"></ul>	
							<p><input name='wonderplugin-popup-adddevicerule' id='wonderplugin-popup-adddevicerule' class="wonderplugin-popup-addrule wonderplugin-popup-btn-orange" data-ruletype="device" type="button" value="<?php _e( 'Add Device Rule', 'wonderplugin_popup' ); ?>"></input></p>
							<p><a href="https://www.wonderplugin.com/wonderplugin-popup/setup-display-rules/" target="_blank">Tutorial: How to setup display rules</a></p>	
							
							<?php
							if (!empty($this->langlist))
							{
							?>
								<hr></hr>
								<h3>Language Rule</h3>
								<h4>The popup will show when any of the "Show" rule match AND none of the "Don't Show" rule match.</h4>
								<h4>There must be at least one "Show" rule.</h4>
								<ul id="wonderplugin-popup-display-langrulelist" class="wonderplugin-popup-display-rulelist" data-ruletype="lang"></ul>	
								<p><input name='wonderplugin-popup-addlangrule' id='wonderplugin-popup-addlangrule' class="wonderplugin-popup-addrule wonderplugin-popup-btn-orange" data-ruletype="lang" type="button" value="<?php _e( 'Add Language Rule', 'wonderplugin_popup' ); ?>"></input></p>
								<p><a href="https://www.wonderplugin.com/wonderplugin-popup/setup-display-rules/" target="_blank">Tutorial: How to setup display rules</a></p>	
							<?php
							}
							?>
						</li>
						<?php } ?>
						
						<li class="wonderplugin-tab wonderplugin-tab-horizontal <?php if ($config['type'] == 'embed') echo 'wonderplugin-tab-selected'; ?>">
						
							<h4><label><input type="checkbox" name="wonderplugin-popup-enableretarget" value="1" <?php if ($config['enableretarget']) echo 'checked'; ?>> Enable retargeting rules </label></h4>
							<p style="font-style:italic;margin-left:20px;">The retargeting is based on web browser cookies. If the above option is enabled, it will save the date and time when the popup being displayed and the action/close/cancel button being clicked to the web browser cookies. Please update your website cookie policy to let your visitors know the cookies are used if it's needed.</p>
							<p style="font-style:italic;margin-left:20px;">The retargeting rules will prevent the popup from appearing again. If the above option is enabled, please clear cookies of your web browser everytime you re-testing the popup on your webpage.</p>	
										
							<div class="wonderplugin-tab-row">
							After clicking the Action (Subscribe) button, do not show the popup to the same visitor for <input name="wonderplugin-popup-retargetnoshowaction" type="number" id="wonderplugin-popup-retargetnoshowaction" value="<?php echo $config['retargetnoshowaction']; ?>" class="wonderplugin-popup-option small-text" /> 
							<select name="wonderplugin-popup-retargetnoshowactionunit" id="wonderplugin-popup-retargetnoshowactionunit" class="wonderplugin-popup-option">
								<option value="minutes" <?php if ($config['retargetnoshowactionunit'] == "minutes") echo "selected"; ?>>minutes</option>
								<option value="hours" <?php if ($config['retargetnoshowactionunit'] == "hours") echo "selected"; ?>>hours</option>
								<option value="days" <?php if ($config['retargetnoshowactionunit'] == "days") echo "selected"; ?>>days</option>
								<option value="years" <?php if ($config['retargetnoshowactionunit'] == "years") echo "selected"; ?>>years</option>
							</select>.
							</div>
							
							<div class="wonderplugin-tab-row">
							After clicking the Close button, do not show the popup to the same visitor for  <input name="wonderplugin-popup-retargetnoshowclose" type="number" id="wonderplugin-popup-retargetnoshowclose" value="<?php echo $config['retargetnoshowclose']; ?>" class="wonderplugin-popup-option small-text" /> 
							<select name="wonderplugin-popup-retargetnoshowcloseunit" id="wonderplugin-popup-retargetnoshowcloseunit" class="wonderplugin-popup-option">
								<option value="minutes" <?php if ($config['retargetnoshowcloseunit'] == "minutes") echo "selected"; ?>>minutes</option>
								<option value="hours" <?php if ($config['retargetnoshowcloseunit'] == "hours") echo "selected"; ?>>hours</option>
								<option value="days" <?php if ($config['retargetnoshowcloseunit'] == "days") echo "selected"; ?>>days</option>
								<option value="years" <?php if ($config['retargetnoshowcloseunit'] == "years") echo "selected"; ?>>years</option>
							</select>.
							</div>
							
							<?php if ($config['type'] != 'embed') { ?>
							<div class="wonderplugin-tab-row">
							After clicking the Cancel (No Thanks) button, do not show the popup to the same visitor for <input name="wonderplugin-popup-retargetnoshowcancel" type="number" id="wonderplugin-popup-retargetnoshowcancel" value="<?php echo $config['retargetnoshowcancel']; ?>" class="wonderplugin-popup-option small-text" /> 
							<select name="wonderplugin-popup-retargetnoshowcancelunit" id="wonderplugin-popup-retargetnoshowcancelunit" class="wonderplugin-popup-option">
								<option value="minutes" <?php if ($config['retargetnoshowcancelunit'] == "minutes") echo "selected"; ?>>minutes</option>
								<option value="hours" <?php if ($config['retargetnoshowcancelunit'] == "hours") echo "selected"; ?>>hours</option>
								<option value="days" <?php if ($config['retargetnoshowcancelunit'] == "days") echo "selected"; ?>>days</option>
								<option value="years" <?php if ($config['retargetnoshowcancelunit'] == "years") echo "selected"; ?>>years</option>
							</select>.
							</div>
							<?php } ?>
							
						</li>
						
					</ul>		
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-vertical">
					
					
					<h3>Select an Email Subscription Service</h3>
					
					<div class="wonderplugin-tab-row">
					<select name="wonderplugin-popup-subscription" id="wonderplugin-popup-subscription" class="wonderplugin-popup-option">
						<option value="noservice" <?php if ($config['subscription'] == "noservice") echo "selected"; ?>>No Service</option>
						<option value="activecampaign" <?php if ($config['subscription'] == "activecampaign") echo "selected"; ?>>Active Campaign</option>
						<option value="campaignmonitor" <?php if ($config['subscription'] == "campaignmonitor") echo "selected"; ?>>Campaign Monitor</option>
						<option value="constantcontact" <?php if ($config['subscription'] == "constantcontact") echo "selected"; ?>>Constant Contact</option>
						<option value="icontact" <?php if ($config['subscription'] == "icontact") echo "selected"; ?>>iContact</option>
						<option value="infusionsoft" <?php if ($config['subscription'] == "infusionsoft") echo "selected"; ?>>Infusionsoft</option>
						<option value="getresponse" <?php if ($config['subscription'] == "getresponse") echo "selected"; ?>>GetResponse API v2</option>
						<option value="getresponsev3" <?php if ($config['subscription'] == "getresponsev3") echo "selected"; ?>>GetResponse API v3</option>
						<option value="mailchimp" <?php if ($config['subscription'] == "mailchimp") echo "selected"; ?>>MailChimp</option>
						<option value="mailpoet" <?php if ($config['subscription'] == "mailpoet") echo "selected"; ?>>MailPoet</option>
						<option value="mailpoet3" <?php if ($config['subscription'] == "mailpoet3") echo "selected"; ?>>MailPoet 3</option>
					</select>
					</div>
										
					<?php 
						if ( !empty($options['servicemessage']) )
							echo '<div class="wonderplugin-popup-service-msg">' . $options['servicemessage'] . '</div>';			
					?>
						
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-mailchimp">
						<h4>MainChimp Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Double opt-in:
						</td><td>
						<label><input type="checkbox" name="wonderplugin-popup-mailchimpdoubleoptin" id="wonderplugin-popup-mailchimpdoubleoptin" value="1" <?php if ($config['mailchimpdoubleoptin']) echo 'checked'; ?> class="wonderplugin-popup-option"> The subscribers must confirm their email address before being subscribed.</label>
						</td></tr>
						
						<tr><td>Your MailChimp API key:
						</td><td><input name="wonderplugin-popup-mailchimpapikey" type="text" id="wonderplugin-popup-mailchimpapikey" value="<?php echo !empty($config["mailchimpapikey"]) ? $config["mailchimpapikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-mailchimpapikeysave' id='wonderplugin-popup-mailchimpapikeysave' class="button button-primary" type="submit" value="Get Audience List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/mailchimp-wordpress-email-subscription-form/#step3" target="_blank">Where do I find the API key</a></td></tr>
						<?php 
						if ( !empty($config["mailchimplists"]) )
						{
							$lists = json_decode($config["mailchimplists"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select an Audience list:</td><td>
						<select name="wonderplugin-popup-mailchimplistid" id="wonderplugin-popup-mailchimplistid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["mailchimplistid"]) && $config["mailchimplistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td></tr>
						<tr id="mailchimplistgroups" style="display:none;"><td style="vertical-align:top;">List Groups:</td><td><div id="mailchimplistgroupscontent"></div></td></tr>
						<?php 
							}
						}
						?>
						
						</table>
						<div style="display:none">
						<?php 
						foreach($config as $key => $value)
						{
							if ((strpos($key, 'mailchimpgroupoption_') === 0)
									|| (strpos($key, 'mailchimpdefaultgroup_') === 0)
									|| (strpos($key, 'mailchimpinterest_') === 0))
							{
								echo '<div id="' . $key . '">' . $value . '</div>';
							}
						}
						?>
						</div>
						<input name="wonderplugin-popup-mailchimplists" type="hidden" id="wonderplugin-popup-mailchimplists" value='<?php echo !empty($config["mailchimplists"]) ? $config["mailchimplists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-getresponse">
						<h4>GetResponse API v2 Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your GetResponse API v2 key:
						</td><td><input name="wonderplugin-popup-getresponseapikey" type="text" id="wonderplugin-popup-getresponseapikey" value="<?php echo !empty($config["getresponseapikey"]) ? $config["getresponseapikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-getresponseapikeysave' id='wonderplugin-popup-getresponseapikeysave' class="button button-primary" type="submit" value="Retrieve Campaign List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/getresponse-wordpress-email-subscription-form/" target="_blank">Where do I find the API key</a></td></tr>
						<?php 
						if ( !empty($config["getresponsecampaigns"]) )
						{
							$lists = json_decode($config["getresponsecampaigns"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select a GetResponse campaign:</td><td>
						<select name="wonderplugin-popup-getresponsecampaignid" id="wonderplugin-popup-getresponsecampaignid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["getresponsecampaignid"]) && $config["getresponsecampaignid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td></tr>
						<?php 
							}
						}
						?>
						
						<tr><td>Autoresponder:
						</td><td>
						<label><input type="checkbox" name="wonderplugin-popup-getresponseautoresponder" id="wonderplugin-popup-getresponseautoresponder" value="1" <?php if (isset($config['getresponseautoresponder']) && ($config['getresponseautoresponder'] == '1'))  echo 'checked'; ?> class="wonderplugin-popup-option"> Enable autoresponder: insert the contact at the beginning of the autoresponder cycle.</label>
						</td></tr>
						
						</table>
						<input name="wonderplugin-popup-getresponsecampaigns" type="hidden" id="wonderplugin-popup-getresponsecampaigns" value='<?php echo !empty($config["getresponsecampaigns"]) ? $config["getresponsecampaigns"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>

					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-getresponsev3">
						<h4>GetResponse API v3 Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your GetResponse API v3 key:
						</td><td><input name="wonderplugin-popup-getresponsev3apikey" type="text" id="wonderplugin-popup-getresponsev3apikey" value="<?php echo !empty($config["getresponsev3apikey"]) ? $config["getresponsev3apikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-getresponsev3apikeysave' id='wonderplugin-popup-getresponsev3apikeysave' class="button button-primary" type="submit" value="Retrieve Campaign List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/getresponse-wordpress-popup/" target="_blank">Where do I find the API key</a></td></tr>
						<?php 
						if ( !empty($config["getresponsev3campaigns"]) )
						{
							$lists = json_decode($config["getresponsev3campaigns"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select a GetResponse campaign:</td><td>
						<select name="wonderplugin-popup-getresponsev3campaignid" id="wonderplugin-popup-getresponsev3campaignid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["getresponsev3campaignid"]) && $config["getresponsev3campaignid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td></tr>
						<?php 
							}
						}
						?>
						
						<tr><td>Autoresponder:
						</td><td>
						<label><input type="checkbox" name="wonderplugin-popup-getresponsev3autoresponder" id="wonderplugin-popup-getresponsev3autoresponder" value="1" <?php if (isset($config['getresponsev3autoresponder']) && ($config['getresponsev3autoresponder'] == '1'))  echo 'checked'; ?> class="wonderplugin-popup-option"> Enable autoresponder: insert the contact at the beginning of the autoresponder cycle.</label>
						</td></tr>

						</table>
						<input name="wonderplugin-popup-getresponsev3campaigns" type="hidden" id="wonderplugin-popup-getresponsev3campaigns" value='<?php echo !empty($config["getresponsev3campaigns"]) ? $config["getresponsev3campaigns"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-campaignmonitor">
						<h4>Campaign Monitor Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your Campaign Monitor API key:
						</td><td><input name="wonderplugin-popup-campaignmonitorapikey" type="text" id="wonderplugin-popup-campaignmonitorapikey" value="<?php echo !empty($config["campaignmonitorapikey"]) ? $config["campaignmonitorapikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						</td>
						</td></tr>
						
						<tr><td>Your Campaign Monitor client ID:
						</td><td><input name="wonderplugin-popup-campaignmonitorclientid" type="text" id="wonderplugin-popup-campaignmonitorclientid" value="<?php echo !empty($config["campaignmonitorclientid"]) ? $config["campaignmonitorclientid"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-campaignmonitorapikeysave' id='wonderplugin-popup-campaignmonitorapikeysave' class="button button-primary" type="submit" value="Retrieve Campaign List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/campaign-monitor-wordpress-email-subscription-form/" target="_blank">Where do I find the API key and client ID</a></td></tr>
						<?php 
						if ( !empty($config["campaignmonitorlists"]) )
						{
							$lists = json_decode($config["campaignmonitorlists"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select a Campaign Monitor list:</td><td>
						<select name="wonderplugin-popup-campaignmonitorlistid" id="wonderplugin-popup-campaignmonitorlistid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["campaignmonitorlistid"]) && $config["campaignmonitorlistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td><td></td></tr>
						<?php 
							}
						}
						?>
						
						</table>
						<input name="wonderplugin-popup-campaignmonitorlists" type="hidden" id="wonderplugin-popup-campaignmonitorlists" value='<?php echo !empty($config["campaignmonitorlists"]) ? $config["campaignmonitorlists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-constantcontact">
						<h4>Constant Contact Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your Constant Contact App API key:
						</td><td><input name="wonderplugin-popup-constantcontactapikey" type="text" id="wonderplugin-popup-constantcontactapikey" value="<?php echo !empty($config["constantcontactapikey"]) ? $config["constantcontactapikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						</td>
						</td></tr>
						
						<tr><td>Your Constant Contact Access Token:
						</td><td><input name="wonderplugin-popup-constantcontactaccesstoken" type="text" id="wonderplugin-popup-constantcontactaccesstoken" value="<?php echo !empty($config["constantcontactaccesstoken"]) ? $config["constantcontactaccesstoken"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-constantcontactapikeysave' id='wonderplugin-popup-constantcontactapikeysave' class="button button-primary" type="submit" value="Retrieve Campaign List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/constant-contact-wordpress-email-subscription-form/" target="_blank">Where do I get an API Key and the Access Token</a></td></tr>
						<?php 
						if ( !empty($config["constantcontactlists"]) )
						{
							$lists = json_decode($config["constantcontactlists"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select a Contacts List:</td><td>
						<select name="wonderplugin-popup-constantcontactlistid" id="wonderplugin-popup-constantcontactlistid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["constantcontactlistid"]) && $config["constantcontactlistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td><td></td></tr>
						<?php 
							}
						}
						?>
						
						</table>
						<input name="wonderplugin-popup-constantcontactlists" type="hidden" id="wonderplugin-popup-constantcontactlists" value='<?php echo !empty($config["constantcontactlists"]) ? $config["constantcontactlists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-activecampaign">
						<h4>Active Campaign Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your Active Campaign API Access URL:
						</td><td><input name="wonderplugin-popup-activecampaignapiurl" type="text" id="wonderplugin-popup-activecampaignapiurl" value="<?php echo !empty($config["activecampaignapiurl"]) ? $config["activecampaignapiurl"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						</td>
						</td></tr>
						
						<tr><td>Your Active Campaign API Access Key:
						</td><td><input name="wonderplugin-popup-activecampaignapikey" type="text" id="wonderplugin-popup-activecampaignapikey" value="<?php echo !empty($config["activecampaignapikey"]) ? $config["activecampaignapikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-activecampaignapikeysave' id='wonderplugin-popup-activecampaignapikeysave' class="button button-primary" type="submit" value="Retrieve Campaign List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/active-campaign-wordpress-email-subscription-form/" target="_blank">Where do I get the API Access URL and Key</a></td></tr>
						<?php 
						if ( !empty($config["activecampaignlists"]) )
						{
							$lists = json_decode($config["activecampaignlists"], true);
							if (!empty($lists)){ 
						?>	
							<tr><td>Select a Contacts List:</td><td>
							<select name="wonderplugin-popup-activecampaignlistid" id="wonderplugin-popup-activecampaignlistid" class="wonderplugin-popup-option">
							<?php 								
								foreach($lists as $list)
									echo '<option value="' . $list['id'] . '" ' . ( (isset($config["activecampaignlistid"]) && $config["activecampaignlistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
							?>
							</select>
							</td><td></td></tr>
							<tr><td>Subscription Form ID (Optional):</td><td>
							<input name="wonderplugin-popup-activecampaignformid" type="text" id="wonderplugin-popup-activecampaignformid" value="<?php echo !empty($config["activecampaignformid"]) ? $config["activecampaignformid"] : ""; ?>" class="wonderplugin-popup-option small-text" />
							</td><td></td></tr>
						<?php
							} 
						}
						?>
						
						</table>
						<input name="wonderplugin-popup-activecampaignlists" type="hidden" id="wonderplugin-popup-activecampaignlists" value='<?php echo !empty($config["activecampaignlists"]) ? $config["activecampaignlists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-mailpoet">
						<h4>MailPoet Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td><input name='wonderplugin-popup-mailpoetsave' id='wonderplugin-popup-mailpoetsave' class="button button-primary" type="submit" value="Retrieve MailPoet Campaign List"></input></td>
						</td><td>
						</td></tr>
						<?php 
						if ( !empty($config["mailpoetlists"]) )
						{
							$lists = json_decode($config["mailpoetlists"], true);
							if (!empty($lists)){ 
						?>	
							<tr><td>Select Email List:</td><td>
							<select name="wonderplugin-popup-mailpoetlistid" id="wonderplugin-popup-mailpoetlistid" class="wonderplugin-popup-option">
							<?php 								
								foreach($lists as $list)
									echo '<option value="' . $list['id'] . '" ' . ( (isset($config["mailpoetlistid"]) && $config["mailpoetlistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
							?>
							</select>
							</td><td></td></tr>
						<?php
							} 
						}
						?>
						
						</table>
						<input name="wonderplugin-popup-mailpoetlists" type="hidden" id="wonderplugin-popup-mailpoetlists" value='<?php echo !empty($config["mailpoetlists"]) ? $config["mailpoetlists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-mailpoet3">
						<h4>MailPoet 3 Options</h4>	
						<table class="wonderplugin-form-table-options">

						<tr><td><input name='wonderplugin-popup-mailpoet3save' id='wonderplugin-popup-mailpoet3save' class="button button-primary" type="submit" value="Retrieve MailPoet 3 Campaign List"></input></td>
						</td><td>
						</td></tr>
						<?php 
						if ( !empty($config["mailpoet3lists"]) )
						{
							$lists = json_decode($config["mailpoet3lists"], true);
							if (!empty($lists)){ 
						?>	
							<tr><td>Select Email List:</td><td>
							<select name="wonderplugin-popup-mailpoet3listid" id="wonderplugin-popup-mailpoet3listid" class="wonderplugin-popup-option">
							<?php 								
								foreach($lists as $list)
									echo '<option value="' . $list['id'] . '" ' . ( (isset($config["mailpoet3listid"]) && $config["mailpoet3listid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
							?>
							</select>
							</td><td></td></tr>
						<?php
							} 
						}
						?>
						
						<tr><td style="vertical-align:top;">Options:</td>
						<td>
						<label><input type="checkbox" name="wonderplugin-popup-mailpoet3sendconfirmationemail" id="wonderplugin-popup-mailpoet3sendconfirmationemail" value="1" <?php if ($config['mailpoet3sendconfirmationemail']) echo 'checked'; ?> class="wonderplugin-popup-option"> Send confirmation email</label>
						<p><label><input type="checkbox" name="wonderplugin-popup-mailpoet3schedulewelcomeemail" id="wonderplugin-popup-mailpoet3schedulewelcomeemail" value="1" <?php if ($config['mailpoet3schedulewelcomeemail']) echo 'checked'; ?> class="wonderplugin-popup-option"> Schedule welcome email</label></p>
						</td></tr>
						
						</table>
						<input name="wonderplugin-popup-mailpoet3lists" type="hidden" id="wonderplugin-popup-mailpoet3lists" value='<?php echo !empty($config["mailpoet3lists"]) ? $config["mailpoet3lists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-icontact">
						<h4>iContact Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your iContact Username (maybe your email address):
						</td><td><input name="wonderplugin-popup-icontactusername" type="text" id="wonderplugin-popup-icontactusername" value="<?php echo !empty($config["icontactusername"]) ? $config["icontactusername"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						</td>
						</td></tr>
						
						<tr><td>Your iContact Application ID:
						</td><td><input name="wonderplugin-popup-icontactappid" type="text" id="wonderplugin-popup-icontactappid" value="<?php echo !empty($config["icontactappid"]) ? $config["icontactappid"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						</td>
						</td></tr>
						
						<tr><td>Your iContact Application Password:
						</td><td><input name="wonderplugin-popup-icontactapppassword" type="text" id="wonderplugin-popup-icontactapppassword" value="<?php echo !empty($config["icontactapppassword"]) ? $config["icontactapppassword"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-icontactsave' id='wonderplugin-popup-icontactsave' class="button button-primary" type="submit" value="Retrieve Campaign List"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/icontact-wordpress-email-subscription-form/" target="_blank">Where do I get an Application ID and the Application Password</a></td></tr>
						<?php 
						if ( !empty($config["icontactlists"]) )
						{
							$lists = json_decode($config["icontactlists"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select a Contacts List:</td><td>
						<select name="wonderplugin-popup-icontactlistid" id="wonderplugin-popup-icontactlistid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["icontactlistid"]) && $config["icontactlistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td><td></td></tr>
						<tr><td>Double Opt-in:
						</td><td>
						<label><input type="checkbox" name="wonderplugin-popup-icontactdoubleoptin" id="wonderplugin-popup-icontactdoubleoptin" value="1" <?php if ($config['icontactdoubleoptin']) echo 'checked'; ?> class="wonderplugin-popup-option"> The subscribers must confirm their email address before being subscribed.</label>
						</td></tr>
						<tr><td>Conformation Message ID:
						</td><td><input name="wonderplugin-popup-icontactmessageid" type="text" id="wonderplugin-popup-icontactmessageid" value="<?php echo !empty($config["icontactmessageid"]) ? $config["icontactmessageid"] : ""; ?>" class="wonderplugin-popup-option medium-text" />
						</td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/icontact-wordpress-email-subscription-form/#doubleoptin" target="_blank">How to enable double opt-in in iContact</a></td></tr>
						<?php 
							}
						}
						?>
						
						</table>
						<input name="wonderplugin-popup-icontactlists" type="hidden" id="wonderplugin-popup-icontactlists" value='<?php echo !empty($config["icontactlists"]) ? $config["icontactlists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<div class="wonderplugin-popup-subscription-options" id="wonderplugin-popup-subscription-infusionsoft">
						<h4>Infusionsoft Options</h4>	
						<table class="wonderplugin-form-table-options">
						
						<tr><td>Your Infusionsoft Subdomain:
						</td><td><input name="wonderplugin-popup-infusionsoftsubdomain" type="text" id="wonderplugin-popup-infusionsoftsubdomain" value="<?php echo !empty($config["infusionsoftsubdomain"]) ? $config["infusionsoftsubdomain"] : ""; ?>" class="wonderplugin-popup-option medium-text" />.infusionsoft.com
						</td>
						</td></tr>
						
						<tr><td>Your Infusionsoft API Key:
						</td><td><input name="wonderplugin-popup-infusionsoftapikey" type="text" id="wonderplugin-popup-infusionsoftapikey" value="<?php echo !empty($config["infusionsoftapikey"]) ? $config["infusionsoftapikey"] : ""; ?>" class="wonderplugin-popup-option regular-text" />
						<input name='wonderplugin-popup-infusionsoftapikeysave' id='wonderplugin-popup-infusionsoftapikeysave' class="button button-primary" type="submit" value="Retrieve Campaign List by Key"></input></td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/infusionsoft-wordpress-email-subscription-form/" target="_blank">Where do I find the subdomain and the API Key</a></td></tr>
						<?php 
						if ( !empty($config["infusionsoftlists"]) )
						{
							$lists = json_decode($config["infusionsoftlists"], true);
							if (!empty($lists)){
						?>	
						<tr><td>Select a Tag:</td><td>
						<select name="wonderplugin-popup-infusionsoftlistid" id="wonderplugin-popup-infusionsoftlistid" class="wonderplugin-popup-option">
						<?php 
							foreach($lists as $list)
								echo '<option value="' . $list['id'] . '" ' . ( (isset($config["infusionsoftlistid"]) && $config["infusionsoftlistid"] == $list['id']) ? 'selected' : '' ) . '>ID: ' . $list['id'] . ', Name: ' . $list['name'] . '</option>';
						?>
						</select>
						</td><td></td></tr>
						<tr><td>Send Email:
						</td><td>
						<label><input type="checkbox" name="wonderplugin-popup-infusionsoftdoubleoptin" id="wonderplugin-popup-infusionsoftdoubleoptin" value="1" <?php if ($config['infusionsoftdoubleoptin']) echo 'checked'; ?> class="wonderplugin-popup-option"> Send the subscriber an email from a template.</label>
						&nbsp;&nbsp;Email template ID:
						</td><td><input name="wonderplugin-popup-infusionsofttemplateid" type="text" id="wonderplugin-popup-infusionsofttemplateid" value="<?php echo !empty($config["infusionsofttemplateid"]) ? $config["infusionsofttemplateid"] : ""; ?>" class="wonderplugin-popup-option medium-text" />
						</td>
						</td></tr>
						<tr><td></td><td><a href="https://www.wonderplugin.com/wordpress-popup/infusionsoft-wordpress-email-subscription-form/#doubleoptin" target="_blank">How to send the subscriber an email</a></td></tr>
						<?php 
							}
						}
						?>
						
						</table>
						<input name="wonderplugin-popup-infusionsoftlists" type="hidden" id="wonderplugin-popup-infusionsoftlists" value='<?php echo !empty($config["infusionsoftlists"]) ? $config["infusionsoftlists"] : ""; ?>' class="wonderplugin-popup-option regular-text" />
					</div>
					
					<h3>Save to the Local Database of this WordPress Website</h3>
					
					<p><label><input type="checkbox" name="wonderplugin-popup-savetolocal" id="wonderplugin-popup-savetolocal" value="1" <?php if ($config['savetolocal']) echo 'checked'; ?> class="wonderplugin-popup-option"> Save to the Local database of this WordPress website</label></p>
					<p style="font-style:italic;"> * You can access the saved data in WordPress dashboard, left menu, Wonder Popup -> Local Record.</p>
					
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-vertical">
					
					<h3>Email Notification to Website Owner</h3>
					<p style="font-style:italic;"> * You can configure the sending from email address, name and SMTP server in WordPress dashboard, left menu, Wonder Popup -> Settings.</p>

					<p><label><input type="checkbox" name="wonderplugin-popup-emailnotify" id="wonderplugin-popup-emailnotify" value="1" <?php if ($config['emailnotify']) echo 'checked'; ?> class="wonderplugin-popup-option"> Send a notification to the email address when someone subscribes: </label>
					<input name='wonderplugin-popup-emailto' type='text' id='wonderplugin-popup-emailto' value='<?php if (isset($config['emailto'])) echo $config['emailto']; ?>' class='regular-text' /></p>
					<p><label>Email Subject:</label></p>
					<p><input name='wonderplugin-popup-emailsubject' type='text' id='wonderplugin-popup-emailsubject' value='<?php if (isset($config['emailsubject'])) echo $config['emailsubject']; ?>' class="large-text" /></p>
					
					<h3>Autoresponder Email to Subscribers</h3>
					<p style="font-style:italic;"> * You can configure the sending from email address, name and SMTP server in WordPress dashboard, left menu, Wonder Popup -> Settings.</p>

					<p><label><input type="checkbox" name="wonderplugin-popup-emailautoresponder" id="wonderplugin-popup-emailautoresponder" value="1" <?php if ($config['emailautoresponder']) echo 'checked'; ?> class="wonderplugin-popup-option"> Automatically send an email to subscribers when they subscribe: </label></p>
					<p><label>Autoresponder Email Subject: </label></p>
					<p><input name='wonderplugin-popup-emailautorespondersubject' type='text' id='wonderplugin-popup-emailautorespondersubject' value='<?php if (isset($config['emailautorespondersubject'])) echo $config['emailautorespondersubject']; ?>' class='large-text' /></p>
					<p><label>Autoresponder Email Content: </label></p>
					<textarea name="wonderplugin-popup-emailautorespondercontent" type="text" id="wonderplugin-popup-emailautorespondercontent" class="wonderplugin-popup-option large-text" rows="8" ><?php echo $config['emailautorespondercontent']; ?></textarea>
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-vertical">
				
					<h3>Behaviour After Clicking Action Button</h3>
					
					<div class="wonderplugin-tab-row">
					<label><input type="radio" name="wonderplugin-popup-afteraction" value="close" class="wonderplugin-popup-option" <?php if ($config['afteraction'] == "close") echo 'checked'; ?>> Close the popup silently</label>
					</div>
					<div class="wonderplugin-tab-row">
					<label><input type="radio" name="wonderplugin-popup-afteraction" value="redirect" class="wonderplugin-popup-option" <?php if ($config['afteraction'] == "redirect") echo 'checked'; ?>> Redirect to the URL: </label>
					<input name="wonderplugin-popup-redirecturl" type="text" id="wonderplugin-popup-redirecturl" value="<?php echo $config['redirecturl']; ?>" class="wonderplugin-popup-option regular-text" />
					<select name="wonderplugin-popup-redirecturlpassparams" id="wonderplugin-popup-redirecturlpassparams" class="wonderplugin-popup-option">
						<option value="passget" <?php if ($config['redirecturlpassparams'] == "passget") echo "selected"; ?>>Pass parameters via GET</option>
						<option value="passpost" <?php if ($config['redirecturlpassparams'] == "passpost") echo "selected"; ?>>Pass parameters via POST</option>
					</select>
					</div>
					<div class="wonderplugin-tab-row">
					<label><input type="radio" name="wonderplugin-popup-afteraction" value="display" class="wonderplugin-popup-option" <?php if ($config['afteraction'] == "display") echo 'checked'; ?>> Display a message and a button inside the popup:</label>
					<div class="wonderplugin-tab-row-description">
					<textarea name="wonderplugin-popup-afteractionmessage" type="text" id="wonderplugin-popup-afteractionmessage" class="wonderplugin-popup-option large-text" ><?php echo $config['afteractionmessage']; ?></textarea>
					</div>
					<div class="wonderplugin-tab-row-description">
					Button caption: <input name="wonderplugin-popup-afteractionbutton" type="text" id="wonderplugin-popup-afteractionbutton" value="<?php echo $config['afteractionbutton']; ?>" class="wonderplugin-popup-option regular-text" />
					</div>
					<div class="wonderplugin-tab-row-description">
					<label><input type="checkbox" name="wonderplugin-popup-closeafterbutton" id="wonderplugin-popup-closeafterbutton" value="1" <?php if ($config['closeafterbutton']) echo 'checked'; ?> class="wonderplugin-popup-option"> Close the popup after clicking the button</label>
					<br><label><input type="checkbox" name="wonderplugin-popup-redirectafterbutton" id="wonderplugin-popup-redirectafterbutton" value="1" <?php if ($config['redirectafterbutton']) echo 'checked'; ?> class="wonderplugin-popup-option"> Redirect to the URL after clicking the button:</label>
						<input name="wonderplugin-popup-redirectafterbuttonurl" type="text" id="wonderplugin-popup-redirectafterbuttonurl" value="<?php echo $config['redirectafterbuttonurl']; ?>" class="wonderplugin-popup-option regular-text" />
						<select name="wonderplugin-popup-redirectafterbuttonpassparams" id="wonderplugin-popup-redirectafterbuttonpassparams" class="wonderplugin-popup-option">
							<option value="passget" <?php if ($config['redirectafterbuttonpassparams'] == "passget") echo "selected"; ?>>Pass parameters via GET</option>
							<option value="passpost" <?php if ($config['redirectafterbuttonpassparams'] == "passpost") echo "selected"; ?>>Pass parameters via POST</option>
						</select>
					</div>
					</div>
					
					<h3>Loading</h3>
					<div class="wonderplugin-tab-row">
					<label><input type="checkbox" name="wonderplugin-popup-displayloading" id="wonderplugin-popup-displayloading" value="1" <?php if ($config['displayloading']) echo 'checked'; ?> class="wonderplugin-popup-option"> Display a loading image while connecting to email service: </label>
					
					<input name="wonderplugin-popup-loadingimage" type="text" id="wonderplugin-popup-loadingimage" value="<?php echo $config['loadingimage']; ?>" class="wonderplugin-popup-option regular-text" />
					<input type='button' class='button wonderplugin-popup-select-image' data-textid='wonderplugin-popup-loadingimage' id='wonderplugin-popup-loading-select-image' value='Upload' />
					<span data-textid='wonderplugin-popup-loadingimage' class="wonderplugin-popup-clear-image">Clear</span>
										
					</div>
					<h3>Error Messages</h3>
					<table class="wonderplugin-form-table-noborder">
					<tr>
					<td>Invalid Email address:</td>
					<td><input name="wonderplugin-popup-invalidemailmessage" type="text" id="wonderplugin-popup-invalidemailmessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['invalidemailmessage']); ?>"></td>
					</tr>
					<tr>
					<td>Required field missing:</td>
					<td><input name="wonderplugin-popup-fieldmissingmessage" type="text" id="wonderplugin-popup-fieldmissingmessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['fieldmissingmessage']); ?>"></td>
					</tr>
					<tr>
					<td>Terms field not checked:</td>
					<td><input name="wonderplugin-popup-termsnotcheckedmessage" type="text" id="wonderplugin-popup-termsnotcheckedmessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['termsnotcheckedmessage']); ?>"></td>
					</tr>
					<tr>
					<td>Privacy Consent field not checked:</td>
					<td><input name="wonderplugin-popup-privacyconsentnotcheckedmessage" type="text" id="wonderplugin-popup-privacyconsentnotcheckedmessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['privacyconsentnotcheckedmessage']); ?>"></td>
					</tr>
					<tr>
					<td>Already subscribed:</td>
					<td><input name="wonderplugin-popup-alreadysubscribedmessage" type="text" id="wonderplugin-popup-alreadysubscribedmessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['alreadysubscribedmessage']); ?>"></td>
					</tr>
					<tr>
					<td>Already subscribed and updated:</td>
					<td><input name="wonderplugin-popup-alreadysubscribedandupdatedmessage" type="text" id="wonderplugin-popup-alreadysubscribedandupdatedmessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['alreadysubscribedandupdatedmessage']); ?>"></td>
					</tr>
					<tr>
					<td>General error:</td>
					<td><input name="wonderplugin-popup-generalerrormessage" type="text" id="wonderplugin-popup-generalerrormessage" class="wonderplugin-popup-option large-text" value="<?php echo esc_html($config['generalerrormessage']); ?>"></td>
					</tr>
					<tr>
					<td></td>
					<td>
					<label><input type="checkbox" name="wonderplugin-popup-displaydetailedmessage" id="wonderplugin-popup-displaydetailedmessage" value="1" <?php if ($config['displaydetailedmessage']) echo 'checked'; ?> class="wonderplugin-popup-option"> Display detailed error message</label>
					</td>
					</tr>
					</table>
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-vertical">
					
					<table class="wonderplugin-form-table-options"><tr>
					<td><h3>Google Analytics</h3></td>
					<td><label class="wonderplugin-switch <?php if ($config['enablegoogleanalytics']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-enablegoogleanalytics" value="1" <?php if ($config['enablegoogleanalytics']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Enabled</span><span class="wonderplugin-switch-label-unchecked">Disabled</span></label></td>
					</tr></table>
					
					<table class="wonderplugin-form-table-options">
								
						<tr>
							<th>Google Analytics ID</th>
							<td><input name="wonderplugin-popup-gaid" type="text" id="wonderplugin-popup-gaid" placeholder="UA-XXXX-Y" value="<?php echo $config['gaid']; ?>" class="wonderplugin-popup-option regular-text" /></td>
						</tr>
						
						<tr>
							<th>Google Analytics Event Category</th>
							<td><input name="wonderplugin-popup-gaeventcategory" type="text" id="wonderplugin-popup-gaeventcategory" value="<?php echo $config['gaeventcategory']; ?>" class="wonderplugin-popup-option regular-text" /></td>
						</tr>
						
						<tr>
							<th>Google Analytics Event Label</th>
							<td><input name="wonderplugin-popup-gaeventlabel" type="text" id="wonderplugin-popup-gaeventlabel" value="<?php echo $config['gaeventlabel']; ?>" class="wonderplugin-popup-option regular-text" /></td>
						</tr>

					</table>
									
					<table class="wonderplugin-form-table-options"><tr>
					<td><h3>Local Analytics</h3></td>
					<td><label class="wonderplugin-switch <?php if ($config['enablelocalanalytics']) echo 'wonderplugin-switch-checked'; ?>"><input type="checkbox" name="wonderplugin-popup-enablelocalanalytics" value="1" <?php if ($config['enablelocalanalytics']) echo 'checked'; ?>><span class="wonderplugin-switch-label-checked">Enabled</span><span class="wonderplugin-switch-label-unchecked">Disabled</span></label></td>
					</tr></table>
					
				</li>
				
				<li class="wonderplugin-tab wonderplugin-tab-vertical">
					
					<table class="wonderplugin-form-table-options" style="width:95%;">
					
					<tr>
					<td style="width:180px;vertical-align:top;"><h3>Options</h3></td>
					<td><div style="margin:24px auto;">
					<label><input type="checkbox" id="wonderplugin-popup-uniquevideoiframeid" name="wonderplugin-popup-uniquevideoiframeid" value="1" <?php if ($config['uniquevideoiframeid']) echo 'checked'; ?>>Use a unique ID for video iframe</label>
					<p><label><input type='checkbox' name='wonderplugin-popup-removeinlinecss' id='wonderplugin-popup-removeinlinecss' <?php if ($config['removeinlinecss']) echo 'checked'; ?> /> Do not add CSS to HTML source code</label></p>
					</div></td>
					</tr>
					
					<tr>
					<td style="width:180px;vertical-align:top;"><h3>Custom CSS</h3></td>
					<td><textarea name="wonderplugin-popup-customcss" id="wonderplugin-popup-customcss" class="large-text" rows="10"><?php echo $config['customcss']; ?></textarea></td>
					</tr>
					
					<tr>
					<td style="width:180px;vertical-align:top;"><h3>Data Options</h3></td>
					<td><textarea name="wonderplugin-popup-dataoptions" id="wonderplugin-popup-dataoptions" class="large-text" rows="10"><?php echo $config['dataoptions']; ?></textarea></td>
					</tr>
					
					<?php if (current_user_can('manage_options')) { ?>
					<tr>
					<td style="width:180px;vertical-align:top;"><h3>Custom JavaScript</h3></td>
					<td><textarea name="wonderplugin-popup-customjs" id="wonderplugin-popup-customjs" class="large-text" rows="10"><?php echo $config['customjs']; ?></textarea></td>
					</tr>
					<?php } ?>
					
					</table>
				</li>
			</ul>
			<div style="clear:both;"></div>
		</div>
		</div>
		
		<input type="hidden" name="wonderplugin-popup-creator" id="wonderplugin-popup-creator" value=""/>
		</form>
		
		<?php
	}
}