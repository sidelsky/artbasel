<?php

add_filter( 'manage_pages_columns', 'page_column_views' );
function page_column_views( $defaults )
{
    $defaults['page-layout'] = __('Template', 'textdomain');
    return $defaults;
}

add_action( 'manage_pages_custom_column', 'page_custom_column_views', 5, 2 );
function page_custom_column_views( $column_name, $id )
{
    if ( $column_name === 'page-layout' ) {
        $set_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

        if ( $set_template == 'default' ) {
            echo __('Default Template', 'textdomain');
        }

        $templates = get_page_templates();
        
        ksort( $templates );
        foreach ( array_keys( $templates ) as $template ) :
            if ( $set_template == $templates[$template] ) echo $template;
        endforeach;
    }
    
}