
    function toggle_thumbnails()
        {
            jQuery('#sortable .post_type_thumbnail').toggle();        
        }
        
    function apto_change_taxonomy(element, is_archive)
        {
            //select the default category (0)
            if (is_archive === true)
                {
                    jQuery('#apto_form').find('#cat').remove();   
                }
                else
                {
                    jQuery('#apto_form').find('#cat').remove();
                    //jQuery('#apto_form #cat').val(jQuery("#apto_form #cat option:first").val());        
                }
            jQuery('#apto_form').submit();
        }
        
    function apto_change_post_type(element)
        {
            var menu_post_type = jQuery('#apto_form').find('input#apto_post_type').val();
            var post_type = jQuery(element).val();
            
            var new_url = 'edit.php?post_type='+menu_post_type+'&page=order-post-types-'+menu_post_type+'&selected_post_type='+post_type;
            
            window.location = new_url;
        }
        
    function apto_autosort_orderby_field_change(element)
        {
            var element_value = jQuery(element).val();
            
            if(element_value == '_custom_field_')
                jQuery('#apto_custom_field_area').show('fast');
                else
                jQuery('#apto_custom_field_area').hide('fast');
            
        }
