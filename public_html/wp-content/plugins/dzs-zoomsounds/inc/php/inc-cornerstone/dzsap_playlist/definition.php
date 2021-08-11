<?php

/**
 * Element Definition
 */


class CS_DZSAP_PLAYLIST  {



	public function ui() {
		return array(
      'title'       => esc_html__( 'ZoomSounds Playlist', 'dzsap' ),
      'autofocus' => array(
    		'heading' => 'h4.my-first-element-heading',
    		'content' => '.dzsap-element'
    	),
    	'icon_group' => 'dzsap'
    );
	}

	public function update_build_shortcode_atts( $atts ) {

		// This allows us to manipulate attributes that will be assigned to the shortcode
		// Here we will inject a background-color into the style attribute which is
		// already present for inline user styles
		if ( !isset( $atts['style'] ) ) {
			$atts['style'] = '';
		}


		if ( isset( $atts['background_color'] ) ) {
			$atts['style'] .= ' background-color: ' . $atts['background_color'] . ';';
			unset( $atts['background_color'] );
		}

		return $atts;

	}

    public function controls(){

        global $dzsap;





	    $options_array = array(

		    'id' => array(
			    'type'    => 'text',
			    'ui' => array(
				    'title'   => esc_html__( 'Gallery id', 'zoomtimeline' ),
				    'tooltip' => esc_html__( 'Edit in ZoomSounds', 'zoomtimeline' ),
			    ),
			    'context' => 'content',
			    'suggest' => esc_html__( '100', 'zoomtimeline' ),
		    ),


	    );

        return $options_array;


    }

    public function render( $atts ) {

		// This allows us to manipulate attributes that will be assigned to the shortcode
		// Here we will inject a background-color into the style attribute which is
		// already present for inline user styles



	}





}






