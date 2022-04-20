<?php
     /**
     * Custom taxonomies
     */
 
    function create_custom_taxonomy($args) {
 
        //Empty array to store the labels and arguments
        $taxVars = array();
 
        //Labels for the new taxonomy
        $taxLabels = array(
            'name'              =>   _x( $args['name'], 'taxonomy general name' ),
            'singular_name'     =>   _x( $args['singular_name'], 'taxonomy singular name' ),
            'search_items'      =>   __( 'Search' . ' ' . $args['name'] ),
            'all_items'         =>   __( 'All' . ' ' . $args['name'] ),
            'parent_item'       =>   __( 'Parent' . ' ' . $args['singular_name'] ),
            'parent_item_colon' =>   __( 'Parent' . ' ' . $args['singular_name'] . ':' ),
            'edit_item'         =>   __( 'Edit' . ' ' . $args['singular_name'] ),
            'update_item'       =>   __( 'Update' . ' ' . $args['singular_name'] ),
            'add_new_item'      =>   __( 'Add New' . ' ' . $args['singular_name'] ),
            'new_item_name'     =>   __( 'New' . ' ' . $args['singular_name'] . ' ' .  'Name' ),
            'menu_name'         =>   __( $args['singular_name'] )
        );
 
        //Arguments
        $taxVars['args'] = array(
            'hierarchical'      =>   true,
            'labels'            =>   $taxLabels,
            'show_ui'           =>   true,
            'show_admin_column' =>   true,
            'query_var'         =>   true,
            'rewrite'           =>   array( 'slug' => $args['slug'] )
        );
 
        //Return the array
        return $taxVars;
 
    }
 
?>