<?php
/**
* Get supported taxonomies
*
* @since 0.4.0
*/
function of_cme_supported_taxonomies() {

        $taxes = get_taxonomies();
        $results = array();

        foreach ( $taxes as $tax ) {
                if ( is_taxonomy_hierarchical( $tax ) ) {
                        $results[] = $tax;
                }
        }

        return $results;
}

/**
 * Get default settings
 *
 * @since 0.5.0
 */
function of_cme_get_defaults() {

        $defaults = array(
                        'type' => 'checkbox',
                        'context' => 'side',
                        'priority' => 'default',
                        'metabox_title' => '',
                        'indented' => 1,
                        'allow_new_terms' => 1
                );

        return $defaults;
}
