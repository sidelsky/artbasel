<?php

/// --  important: settings must have the class mainsetting
$dzsap->sliderstructure = '<div class="slider-con" style="display:none;">

        <div class="settings-con">
        <h4>' . esc_html__('General Options', 'dzsap') . '</h4>
        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('ID', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">' . esc_html__('Choose an unique id.', 'dzsap') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Gallery Skin', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-galleryskin">
                <option>skin-wave</option>
                <option>skin-default</option>
                <option>skin-aura</option>
            </select>
        </div>
        <div class="setting type_all vpconfig-wrapper">
            <div class="setting-label">' . esc_html__('ZoomSounds Player Configuration', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme vpconfig-select" name="0-settings-vpconfig">
                <option value="default">' . esc_html__('default', 'dzsap') . '</option>
                ' . $dzsap->vpconfigsstr . '
            </select>
            <div class="sidenote" style="">' . esc_html__('setup these inside the <strong>ZoomSounds Player Configs</strong> admin', 'dzsap') . ' <a rel="nofollow" id="quick-edit" class="quick-edit-vp" href="' . admin_url('admin.php?page=' . DZSAP_ADMIN_PAGENAME_LEGACY_SLIDERS_ADMIN_VPCONFIGS . '&currslider=0&from=shortcodegenerator') . '" class="sidenote" style="cursor:pointer;">' . esc_html__("Quick Edit ") . '</a></div>
            <div class="edit-link-con"></div>
        </div>';


$lab = 'mode';
$dzsap->sliderstructure .= '<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Mode', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme dzs-dependency-field" name="0-settings-' . $lab . '">
                <option value="mode-normal">' . esc_html__("Default") . '</option>
                <option value="mode-showall">' . esc_html__("Show All") . '</option>
            </select>
            <div class="sidenote">' . sprintf(esc_html__('%sshow all%s lists the players one below the other ', 'dzsap'), '<strong>', '</strong>') . '</div>
        </div>';


$dependency = array(

  array(
    'element' => '0-settings-mode',
    'value' => array('mode-normal'),
  ),
);

$aux = json_encode($dependency);
$aux_dependency_for_mode_normal = str_replace('"', '{quotquot}', $aux);


$dependency = array(

  array(
    'element' => '0-settings-mode',
    'value' => array('mode-showall'),
  ),
);

$aux = json_encode($dependency);
$aux_dependency_for_mode_show_all = str_replace('"', '{quotquot}', $aux);







$dzsap->sliderstructure .= '
        
        
        <div class="setting type_all" data-dependency=&quot;' . $aux_dependency_for_mode_show_all . '&quot;>
            <div class="setting-label">' . esc_html__('Enable number indicator', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_mode_showall_show_number">
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('Disable arrows for gallery navigation on the player ', 'dzsap') . '</div>
        </div>
        
        
        
        <div class="setting">
            <div class="setting-label">' . esc_html__('Background', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting with-colorpicker" name="0-settings-bgcolor" value="transparent"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
        </div>
        
        
        
        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Linking', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_enable_linking">
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('when selecting a track in the menu the link will update to reflect the new track selected', 'dzsap') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Order by', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-orderby">
                <option value="custom">' . esc_html__('default', 'dzsap') . '</option>
                <option value="rand">' . esc_html__('random', 'dzsap') . '</option>
                <option value="ratings">' . esc_html__('ratings', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('random or drag and drop', 'dzsap') . '</div>
        </div>
        
        
        
        
        
        <br>
        <div class="dzstoggle toggle1" rel=""  data-dependency=&quot;' . $aux . '&quot;>
<div class="toggle-title" style="">' . esc_html__('Menu Options', 'dzsap') . '</div>
<div class="toggle-content">


<div class="setting type_all" data-dependency=&quot;' . $aux . '&quot;>
            <div class="setting-label">' . esc_html__('Menu Position', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menuposition">
                <option>bottom</option>
                <option>none</option>
                <option>top</option>
            </select>
        </div>
        <div class="setting type_all" data-dependency=&quot;' . $aux . '&quot; >
            <div class="setting-label">' . esc_html__('Menu State', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_menu_state">
                <option value="open">' . esc_html__("Open") . '</option>
                <option value="closed">' . esc_html__("Closed") . '</option>
            </select>
            <div class="sidenote">' . esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ', 'dzsap') . '</div>
        </div>
        <div class="setting type_all" data-dependency=&quot;' . $aux . '&quot;>
            <div class="setting-label">' . esc_html__('Menu State Button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_menu_show_player_state_button">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all" >
            <div class="setting-label">' . esc_html__('Facebook Share', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menu_facebook_share">
                <option>auto</option>
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . esc_html__('enable a facebook share button in the menu ', 'dzsap') . '</div>
        </div>
        <div class="setting type_all" >
            <div class="setting-label">' . esc_html__('Like Button', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menu_like_button">
                <option>auto</option>
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . esc_html__('enable a like button in the menu ', 'dzsap') . '</div>
        </div>


</div>
</div>


        <div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . esc_html__('Autoplay Options', 'dzsap') . '</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Cue First Media', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-cuefirstmedia">
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
            </select>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Autoplay', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay">
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
            </select>
        </div>
        
        
        
        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Autoplay Next', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplaynext">
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
            </select>
        </div>
</div>
</div>
        
        
        <div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . esc_html__('Play / Like Settings', 'dzsap') . '</div>
<div class="toggle-content">


<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Enable Play Count', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_views">
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('enable play count - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>


<div class="setting type_all">
            <div class="setting-label">' . esc_html__('Enable Downloads Counter', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_downloads_counter">
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('enable download count - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Enable Like Count', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_likes">
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('enable like count - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>


        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Enable Rating', 'dzsap') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_rates">
                <option value="off">' . esc_html__('off', 'dzsap') . '</option>
                <option value="on">' . esc_html__('on', 'dzsap') . '</option>
            </select>
            <div class="sidenote">' . esc_html__('enable rating - warning: the media file has to be attached to a library item ( the Link To Media field .. ) ', 'dzsap') . '</div>
        </div>



</div>
</div>





        
        
        <div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . esc_html__('Force Dimensions', 'dzsap') . '</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Force Width', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-width" value=""/>
            <div class="sidenote">' . esc_html__('Force a fix width, leave blank for responsive mode ', 'dzsap') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . esc_html__('Force Height', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-height" value=""/>
            <div class="sidenote">' . esc_html__('Force a fix height, leave blank for default mode ', 'dzsap') . '</div>
        </div>
        
        
        
        <div class="setting type_all" data-dependency=&quot;' . $aux . '&quot;>
            <div class="setting-label">' . esc_html__('Menu Maximum Height', 'dzsap') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-design_menu_height" value="default"/>
        </div>
        
</div>
</div>

        

        </div><!--end settings con-->

        <div class="master-items-con mode_all">
        <div class="items-con "></div>
        <a rel="nofollow" href="#" class="add-item"></a>
        </div><!--end master-items-con-->
        <div class="clear"></div>
        </div>';

