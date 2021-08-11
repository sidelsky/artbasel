export function nag_intro_tooltip(main_settings) {


  jQuery(document).on('click','.dzs--close-btn',function(){

    var $t = jQuery(this);
    $t.parent().parent().remove();
  })
  jQuery(document).on('click','.dzs-ajax--hide-tips',function(){

    var $t = jQuery(this);
    jQuery.ajax({
      method: 'POST',
      url: window.ajaxurl,
      data: {
        action: 'dzsap_admin_nag_disable_all'
      },
      success: (response)=>{
      }
    })
    $t.parent().parent().parent().parent().remove();
  })

  // -- nag

  if (main_settings && main_settings.nag_intro_data && localStorage.getItem(main_settings.prefix+'_nag_intro_seen') !== 'on') {

    if (main_settings.nag_intro_data == 'on') {
      jQuery('#toplevel_page_dzsap_menu .wp-menu-name').wrap(`<span class="dzs--nag-intro-tooltip dzstooltip-con js " style="z-index: 555555"><span class="tooltip-indicator" style="color: inherit;"></span><span class=" dzstooltip active talign-end arrow-left style-rounded color-dark-light  dims-set transition-slidedown " style="width: 530px"><span class="dzstooltip--inner text-align-center">
<h4>${main_settings.translate_nag_intro_title}</h4>
<p>${main_settings.translate_nag_intro_1}</p>
<span class="dzs--close-btn"><span class="dashicons dashicons-no"></span></span>
<span class="dzs-row">
<span class="dzs-col-md-6"><h6>${main_settings.translate_nag_intro_title_1}</h6>
<p>${main_settings.translate_nag_intro_col1}</p>
<div style="background-image:url(https://i.imgur.com/Ec6Tpf5.jpg);"  class="fullwidth divimage"></div>
</span>

<span class="dzs-col-md-6"><h6>${main_settings.translate_nag_intro_title_2}</h6>
<p>${main_settings.translate_nag_intro_col2}</p>
<div style="background-image:url(https://i.imgur.com/LorVNVf.jpg);"  class="fullwidth divimage"></div>
</span>
</span>
</span> </span></span>`);
      jQuery('.toplevel_page_dzsap_menu').css({
        display: 'flex',
        alignItems: 'center',
      })
      jQuery('.toplevel_page_dzsap_menu .wp-menu-name').css({
        paddingLeft: 0,
      })
    }

    jQuery(document).on('click', '.dzs--close-btn', function () {
      var _t = jQuery(this);


      _t.parent().parent().hide();


      var data = {
        action: 'dzsap_hide_intro_nag'
        , postdata: 'on'
      };


      jQuery.ajax({
        type: "POST",
        url: window.ajaxurl,
        data: data,
      });
      return false;
    });

    localStorage.setItem(main_settings.prefix+'_nag_intro_seen', 'on');


  }

}