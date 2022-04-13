(function() {
    tinymce.PluginManager.add('image_zoom_button', function( editor, url ) {

        /*
    var this_js = tinyMCEPreInit.mceInit.content.external_plugins.image_zoom_button;
    var this_png_button = this_js.replace('js/tinyMCE-button.js', 'images/tinyMCE_button.png');
        */
   function toggle_zoom() {
        var content = editor.selection.getContent();
        var zoom_class = 'zoooom';

        if ( content.indexOf('img ') < 0 ) {
            alert('First you have to select the image to which you want to add the zoom feature');
            return false;
        }

        if ( content.indexOf( zoom_class ) < 0 ) {
            if ( content.indexOf('size-full') > 0 ) {
                alert('You can add the zoom feature only to non full-size images');
                return false;
            }
            editor.dom.addClass( editor.selection.getNode(), zoom_class );
            this.active(true);
        } else {
            editor.dom.removeClass( editor.selection.getNode(), zoom_class );
            this.active(false);
        }
    }

    var this_button = {
        title: 'Image Zoom',
        stateSelector: 'img.zoooom',
        onClick: toggle_zoom,
    };
    if ( typeof jQuery('#toplevel_page_zoooom_settings img').attr('src') !== 'undefined' ) {
        this_button.image = jQuery('#toplevel_page_zoooom_settings img').attr('src').replace('icon.svg', 'tinyMCE_button.png');
    } else {
        this_button.text = 'Image Zoom';
    }

    editor.addButton('image_zoom_button', this_button);
    });
})();


