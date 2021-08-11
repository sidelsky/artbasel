'use strict';
jQuery(document).ready(function ($) {

  jQuery('input.dzsaptog').on('change',function () {
    jQuery('#dzsap_permalink_structure').val(jQuery(this).val());
  });

  jQuery('#dzsap_permalink_structure').on('focus',function () {
    jQuery('#dzsap_custom_selection').click();
  });
})