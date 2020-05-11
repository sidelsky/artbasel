<?php
/*
Plugin Name: Advanced Post Types Order
Plugin URI: http://www.nsp-code.com
Description: Order Post Types Objects using a Drag and Drop Sortable javascript capability
Author: Nsp Code
Author URI: http://www.nsp-code.com
Version: 2.5.4.7
*/

    define('CPTPATH',   plugin_dir_path(__FILE__));
    define('CPTURL',    plugins_url('', __FILE__));

    define('CPTVERSION', '2.5.4.7');
    define('CPT_VERSION_CHECK_URL', 'http://www.nsp-code.com/version-check/vcheck.php?app=advanced-post-types-order-v2');

    //load language files
    add_action( 'plugins_loaded', 'apto_load_textdomain');
    function apto_load_textdomain()
        {
            load_plugin_textdomain('apto', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang');
        }

    include_once(CPTPATH . '/include/functions.php');

    register_deactivation_hook(__FILE__, 'CPTO_deactivated');
    register_activation_hook(__FILE__, 'CPTO_activated');

    function CPTO_activated()
        {
            cpto_create_plugin_tables();

            //make sure the vars are set as default
            $options = get_option('cpto_options');
            if (!isset($options['autosort']))
                $options['autosort'] = '1';

            if (!isset($options['adminsort']))
                $options['adminsort'] = '1';

            if (!isset($options['capability']))
                $options['capability'] = 'install_plugins';

            if (!isset($options['code_version']))
                $options['code_version'] = CPTVERSION;

            update_option('cpto_options', $options);

        }

    function CPTO_deactivated()
        {

        }


    //WP E-Commerce Order Update
    add_filter('posts_orderby_request', 'apto_posts_orderby_request', 99, 2);
    function apto_posts_orderby_request($orderBy, $query)
        {
            //only for non-admin
            if (is_admin())
                return $orderBy;

            //check for WP E-Commerce Taxonomy
            if (!isset($query->query['taxonomy']) || $query->query['taxonomy'] != 'wpsc_product_category')
                return  $orderBy;

            //apply only if dragandrop
            $wpec_orderby = get_option( 'wpsc_sort_by' );
            if ($wpec_orderby != "dragndrop")
                return $orderBy;

            $options = get_option('cpto_options');

            //check if the current post_type is active in the setings
            if (isset($options['allow_post_types']))
                {
                    $_post_type = 'wpsc-product';

                    if (!in_array($_post_type, $options['allow_post_types']))
                        return $orderBy;
                    unset ($_post_type);
                }

            $orderby = CPTOrderPosts('', $query);

            return $orderby;
        }


    add_action('admin_print_scripts', 'CPTO_admin_scripts');
    function CPTO_admin_scripts()
        {
            wp_enqueue_script('jquery');

            if (!isset($_GET['page']))
                return;

            if (isset($_GET['page']) && strpos($_GET['page'], 'order-post-types-') === FALSE)
                return;

            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-widget');
            wp_enqueue_script('jquery-ui-mouse');

            $myJavascriptFile = CPTURL . '/js/touch-punch.min.js';
            wp_register_script('touch-punch.min.js', $myJavascriptFile, array(), '', TRUE);
            wp_enqueue_script( 'touch-punch.min.js');

            $myJavascriptFile = CPTURL . '/js/nested-sortable.js';
            wp_register_script('nested-sortable.js', $myJavascriptFile, array(), '', TRUE);
            wp_enqueue_script( 'nested-sortable.js');

            $myJavascriptFile = CPTURL . '/js/apto-javascript.js';
            wp_register_script('apto-javascript.js', $myJavascriptFile);
            wp_enqueue_script( 'apto-javascript.js');

        }


    add_filter('pre_get_posts', 'CPTO_pre_get_posts');
    function CPTO_pre_get_posts($query)
        {
            //check for the force_no_custom_order param
            if (isset($query->query_vars['force_no_custom_order']) && $query->query_vars['force_no_custom_order'] === TRUE)
                return $query;

            $options = get_option('cpto_options');
            if (is_admin())
                {
                    //no need if it's admin interface
                    return false;
                }
            //if auto sort
            if ($options['autosort'] > 0)
                {
                    //check if the current post_type is active in the setings
                    if (isset($options['allow_post_types']) && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] != '')
                        {
                            if (is_array($query->query_vars['post_type']))
                                {
                                    if (count($query->query_vars['post_type']) > 1)
                                        return $query;

                                    $_post_type = $query->query_vars['post_type'][0];
                                }
                                else
                                {
                                    $_post_type = $query->query_vars['post_type'];
                                }

                            if (!in_array($_post_type, $options['allow_post_types']))
                                return $query;
                            unset ($_post_type);
                        }

                    //remove the supresed filters;
                    if (isset($query->query['suppress_filters']))
                        $query->query['suppress_filters'] = FALSE;


                    //update the sticky if required or not
                    if (isset($options['ignore_sticky_posts']) && $options['ignore_sticky_posts'] == "1")
                        {
                            if (!isset($query->query_vars['ignore_sticky_posts']))
                                $query->query_vars['ignore_sticky_posts'] = TRUE;
                        }
                }

            return $query;
        }

    add_filter('posts_orderby', 'CPTOrderPosts', 99, 2);
    function CPTOrderPosts($orderBy, $query)
        {
            //check for the force_no_custom_order param
            if (isset($query->query_vars['force_no_custom_order']) && $query->query_vars['force_no_custom_order'] === TRUE)
                return $orderBy;

            //ignore the bbpress
            if (isset($query->query_vars['post_type']) && ((is_array($query->query_vars['post_type']) && in_array("reply", $query->query_vars['post_type'])) || ($query->query_vars['post_type'] == "reply")))
                return $orderBy;
            if (isset($query->query_vars['post_type']) && ((is_array($query->query_vars['post_type']) && in_array("topic", $query->query_vars['post_type'])) || ($query->query_vars['post_type'] == "topic")))
                return $orderBy;

            global $wpdb;

            $options = get_option('cpto_options');

            //check if menu_order provided
            if (isset($query->query['orderby']) && $query->query['orderby'] == 'menu_order')
                {
                    $orderBy = apto_get_orderby($orderBy, $query);

                    return($orderBy);
                }

            if (is_admin())
                    {
                        if (!isset($options['adminsort']) || (isset($options['adminsort']) && $options['adminsort'] == "1"))
                            {
                                //only return custom sort if there is not a column sort
                                if (!isset($_GET['orderby']))
                                    $orderBy = apto_get_orderby($orderBy, $query);

                                return($orderBy);
                            }
                    }
                else
                    {
                        //check if the current post_type is active in the setings
                         if (isset($options['allow_post_types']) && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] != '')
                            {
                                if (is_array($query->query_vars['post_type']))
                                    {
                                        //check if there is at least one post type within the array
                                        if (count($query->query_vars['post_type']) > 0)
                                            {
                                                if(count(array_intersect($options['allow_post_types'], $query->query_vars['post_type'])) < 1)
                                                    return $orderBy;
                                            }
                                    }
                                    else
                                        {
                                            $_post_type = $query->query_vars['post_type'];
                                            if (!in_array($_post_type, $options['allow_post_types']))
                                                return $orderBy;
                                            unset ($_post_type);
                                        }
                            }

                        //check if is feed
                        if (is_feed())
                            {
                                if ($options['feedsort'] != "1")
                                    return $orderBy;

                                //else use the set order
                                $orderBy = apto_get_orderby($orderBy, $query);

                                return($orderBy);
                            }


                        if ($options['autosort'] == "1")
                            {
                                //$orderBy = "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date DESC";

                                //use the custom order unless there is an auto sort
                                $orderBy = apto_get_orderby($orderBy, $query);

                                return($orderBy);
                            }
                        if ($options['autosort'] == "2")
                            {
                                //check if the user didn't requested another order
                                if (!isset($query->query['orderby']))
                                    {
                                        //$orderBy = "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date DESC";
                                        $orderBy = apto_get_orderby($orderBy, $query);
                                    }
                            }
                    }

            return($orderBy);
        }
    add_filter('posts_groupby', 'APTO_posts_groupby', 99, 2);
    function APTO_posts_groupby($groupby, $query)
        {
            //check for NOT IN taxonomy operator
            if(isset($query->tax_query->queries) && count($query->tax_query->queries) == 1 )
                {
                    if(isset($query->tax_query->queries[0]['operator']) && $query->tax_query->queries[0]['operator'] == 'NOT IN')
                        $groupby = '';
                }

            return($groupby);
        }

    add_filter('posts_distinct', 'APTO_posts_distinct', 99, 2);
    function APTO_posts_distinct($distinct, $query)
        {
            //check for NOT IN taxonomy operator
            if(isset($query->tax_query->queries) && count($query->tax_query->queries) == 1 )
                {
                    if(isset($query->tax_query->queries[0]['operator']) && $query->tax_query->queries[0]['operator'] == 'NOT IN')
                        $distinct = 'DISTINCT';
                }

            return($distinct);
        }

    add_action('wp_loaded', 'initCPTO' );
    add_action('admin_menu', 'cpto_plugin_menu', 1);
    add_action('plugins_loaded', 'cpto_load_textdomain', 2 );
    add_action('wp_insert_post', 'apto_wp_insert_post', 10, 2);


    function cpto_load_textdomain()
        {
            $locale = get_locale();
            $mofile = CPTPATH . '/lang/cpt-' . $locale . '.mo';
            if ( file_exists( $mofile ) ) {
                load_textdomain( 'cppt', $mofile );
            }
        }


    function cpto_plugin_menu()
        {
            include (CPTPATH . '/include/options.php');
            add_options_page('Post Types Order', '<img class="menu_pto" src="'. CPTURL .'/images/menu-icon.gif" alt="" />Post Types Order', 'manage_options', 'cpto-options', 'cpt_plugin_options');
        }

    function initCPTO()
        {
	        global $custom_post_type_order, $userdata;

            //make sure the vars are set as default
            $options = get_option('cpto_options');

            //compare if the version require update
            if (!isset($options['code_version']) || $options['code_version'] == '')
                {
                    $options['code_version'] = 0.1;
                    if (!isset($options['autosort']))
                        $options['autosort'] = '1';

                    if (!isset($options['adminsort']))
                        $options['adminsort'] = '1';

                    if (!isset($options['capability']))
                        $options['capability'] = 'install_plugins';

                    update_option('cpto_options', $options);
                }

            if (version_compare( strval( CPTVERSION ), $options['code_version'] , '>' ) === TRUE )
                {
                    //update the tables
                    cpto_create_plugin_tables();

                    //update the plugin version
                    $options['code_version'] = CPTVERSION;
                    update_option('cpto_options', $options);
                }

            if (is_admin())
                {
                    //check for new version once per day
                    add_action( 'after_plugin_row','cpto_check_plugin_version' );

                    if(isset($options['capability']) && !empty($options['capability']))
                        {
                            if(current_user_can($options['capability']))
                                {
                                    include(CPTPATH . '/include/reorder-class.php');
                                    $custom_post_type_order = new ACPTO();   
                                }
                        }
                    else if (is_numeric($options['level']))
                        {
                            if (userdata_get_user_level(true) >= $options['level'])
                                {
                                    include(CPTPATH . '/include/reorder-class.php');
                                $custom_post_type_order = new ACPTO();
                                }
                        }
                        else
                            {
                                include(CPTPATH . '/include/reorder-class.php');
                                $custom_post_type_order = new ACPTO();  
                            }

                    //backwards compatibility
                    if( !isset($options['apto_tables_created']))
                        {
                            cpto_create_plugin_tables();
                        }
                }

            //bbpress reverse option check
            if (isset($options['bbpress_replies_reverse_order']) && $options['bbpress_replies_reverse_order'] == "1")
                add_filter('bbp_before_has_replies_parse_args', 'apto_bbp_before_has_replies_parse_args' );


            if (isset($options['autosort']) &&  $options['autosort'] == '1')
                {
                    add_filter('get_next_post_where', 'cpto_get_next_post_where', 10, 3);
                    add_filter('get_next_post_sort', 'cpto_get_next_post_sort');

                    add_filter('get_previous_post_where', 'cpto_get_previous_post_where', 10, 3);
                    add_filter('get_previous_post_sort', 'cpto_get_previous_post_sort');
                }
        }


    add_filter('apto_get_order_list', 'sticky_posts_apto_get_order_list', 10, 2);

?>
