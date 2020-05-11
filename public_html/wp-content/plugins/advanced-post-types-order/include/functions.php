<?php


    add_action( 'wp_ajax_update-custom-type-order', 'apto_saveAjaxOrder' );
    function apto_saveAjaxOrder() 
        {
            global $wpdb, $blog_id;
            
            set_time_limit(0);
            
            //avoid using parse_Str due to the max_input_vars for large amount of data
            $_data = explode("&", $_POST['order']);
            $_data_parsed = array();
            foreach ($_data as $_data_item)
                {
                    list($key, $value) = explode("=", $_data_item);
                    $key = str_replace("item[", "", $key);
                    $key = str_replace("]", "", $key);
                    
                    $_data_parsed[$key] = trim($value);
                }
            
            $data = '';
            if(count($_data_parsed) > 0)
                $data['item'] = $_data_parsed;
            
            $post_type  = $_POST['post_type'];
            $term_id    = $_POST['term_id'];
            $taxonomy   = $_POST['taxonomy'];
            $lang       = $_POST['lang'];
            
            $is_hierarhical = FALSE;
            //check if is hierarhical scenario
            $post_type_data = get_post_type_object($post_type);
            if ($post_type_data->hierarchical === TRUE && $taxonomy == '_archive_')
                $is_hierarhical = TRUE;
            
            if (is_array($data))
                {
                    //remove the old order
                    if($is_hierarhical === FALSE)
                        {
                            $query = "DELETE FROM `". $wpdb->base_prefix ."apto`
                                        WHERE `term_id` = '".$term_id."' AND `post_type` = '".$post_type."' AND `taxonomy` = '".$taxonomy."' AND `blog_id` = " . $blog_id . " AND `lang` = '" . $lang ."'";
                            $results = $wpdb->get_results($query);
                        }
                        
                    //prepare the var which will hold the item childs current order
                    $childs_current_order = array();
                    
                    $current_item_menu_order = 0;
                    
                    foreach($data['item'] as $post_id => $parent_id ) 
                        {
                            if($is_hierarhical === TRUE)
                                {
                                    $current_item_menu_order = '';
                                    if($parent_id != 'null')
                                        {
                                            if(!isset($childs_current_order[$parent_id]))
                                                $childs_current_order[$parent_id] = 1;
                                                else
                                                $childs_current_order[$parent_id] = $childs_current_order[$parent_id] + 1;
                                                
                                            $current_item_menu_order    = $childs_current_order[$parent_id];
                                            $post_parent                = $parent_id;
                                        }
                                        else
                                            {
                                                if(!isset($childs_current_order['root']))
                                                    $childs_current_order['root'] = 1;
                                                    else
                                                    $childs_current_order['root'] = $childs_current_order['root'] + 1;
                                                    
                                                $current_item_menu_order    = $childs_current_order['root'];
                                                $post_parent                = 0;
                                            }
                                        
                                    //update the menu_order
                                    $wpdb->update( $wpdb->posts, array('menu_order' => $current_item_menu_order, 'post_parent' => $post_parent), array('ID' => $post_id) );
                                    do_action('apto_order_update_hierarchical', array('post_id' =>  $post_id, 'position' =>  $current_item_menu_order, 'page_parent'    =>  $post_parent));
                                    
                                    continue;
                                }
                                
                                                                
                            //maintain the simple order if is archive
                            if($taxonomy == '_archive_')
                                $wpdb->update( $wpdb->posts, array('menu_order' => $current_item_menu_order), array('ID' => $post_id) ); 
                                 
                            $query = "INSERT INTO `". $wpdb->base_prefix ."apto` 
                                        (`post_id`, `term_id`, `post_type`, `taxonomy`, `blog_id`, `lang`) 
                                        VALUES ('".$post_id."', '".$term_id."', '".$post_type."', '".$taxonomy."', ".$blog_id.", '".$lang."');";
                            $results = $wpdb->get_results($query);
                            
                            do_action('apto_order_update', array('post_id' => $post_id, 'position' => $current_item_menu_order, 'term_id' => $term_id, 'taxonomy' => $taxonomy, 'language' => $lang));
                            
                            $current_item_menu_order++;
        
                        }
                }
                
            die();                    
        }
          



    /**
    * @desc 
    * 
    * Return UserLevel
    * 
    */
    function userdata_get_user_level($return_as_numeric = FALSE)
        {
            global $userdata;
            
            $user_level = '';
            for ($i=10; $i >= 0;$i--)
                {
                    if (current_user_can('level_' . $i) === TRUE)
                        {
                            $user_level = $i;
                            if ($return_as_numeric === FALSE)
                                $user_level = 'level_'.$i; 
                            break;
                        }    
                }        
            return ($user_level);
        }    
        
    
    /**
    * @desc 
    * 
    * Reset Order for given post type
    * 
    */
    function reset_post_order($post_type, $cat, $current_taxonomy)
        {
            global $wpdb, $blog_id;
            
            $post_type_info = get_post_type_object($post_type);
            
            if ($post_type_info->hierarchical === TRUE && $cat == '-1' && $current_taxonomy == '_archive_')
                {
                    //this is a hirarhical which is being saved as default wp_posts order
                    $query = "UPDATE `". $wpdb->base_prefix ."posts`
                                SET menu_order = 0
                                WHERE `post_type` = '".$post_type ."'";
                     $result = $wpdb->get_results($query);   
                }
                else
                {
                    $lang = apto_get_blog_language(); 
                    
                    $query = "DELETE FROM `". $wpdb->base_prefix ."apto`
                                WHERE `post_type` = '".$post_type ."' AND `term_id` = '". $cat ."' AND `taxonomy` = '". $current_taxonomy ."' AND `blog_id` = ".$blog_id . " AND `lang` = '".$lang."'";
                    $result = $wpdb->get_results($query); 
                }          
        } 
    
    /**
    * 
    * bbPress filter function 
    * 
    */
    function apto_bbp_before_has_replies_parse_args($args)
        {
            $args['order'] = 'DESC';  
            
            return $args;   
        }
    
    /**
    * @desc 
    * 
    * Check the latest plugin version
    * 
    */
    function cpto_create_plugin_tables()
        {
            $options = get_option('cpto_options');
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            global $wpdb;
            
            $query = "CREATE TABLE ". $wpdb->base_prefix ."apto (
                          id int(11) NOT NULL AUTO_INCREMENT,
                          blog_id int(11) NOT NULL default '1',
                          post_id int(11) NOT NULL,
                          term_id int(11) NOT NULL,
                          post_type varchar(128) NOT NULL,
                          taxonomy varchar(128) NOT NULL,
                          lang varchar(3) NOT NULL default 'en',
                          PRIMARY KEY  (id),
                          KEY term_id (term_id),
                          KEY post_type (post_type),
                          KEY taxonomy (taxonomy)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
            dbDelta($query);
            
            $options['apto_tables_created'] = TRUE;
            update_option('cpto_options', $options); 
        }
    
    /**
    * 
    * return the order by
    * 
    */
    function apto_get_orderby($orderBy, $query)
        {
            global $wpdb;
            
            $options = get_option('cpto_options');
            
            list($post_type, $taxonomy) = apto_get_query_post_type_taxonomy($query);
            
            $order_type = apto_get_order_type($query);
            
            //if auto run the following code then return and leave manual code to run
            if($order_type == 'auto')
                {
                    $term_id = apto_get_query_taxonomy_term_id($taxonomy, $query);
                    
                    $new_orderBy    = '';
                    $new_order      = '';
                    
                    if(isset($options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['order_by']))
                        $new_orderBy    = $options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['order_by'];
                    
                    if(isset($options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['order']))
                        $new_order    = $options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['order'];
                    
                    //check for valid values or use default
                    if(empty($new_orderBy) || empty($new_order))
                        return $orderBy;
                    
                    switch ($new_orderBy)
                        {
                            case '_default_'        :
                                                        
                                                        break;
                            
                            case '_random_'         :
                                                        $orderBy = "RAND()";
                                                        
                                                        break;
                            
                            case '_custom_field_'   :
                                                        if(isset($options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['custom_field_name']))
                                                            $custom_field_name    = $options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['custom_field_name'];
                                                        //if empty no need to continue
                                                        if(empty($custom_field_name))
                                                            break;
                                                        
                                                        $order_list = array();
                                                            
                                                        $query = "SELECT DISTINCT ". $wpdb->posts .".* FROM ". $wpdb->posts ."  
                                                                        JOIN ". $wpdb->postmeta ." as pm1 ON (". $wpdb->posts .".ID = pm1.post_id)";
                                                                    
                                                        if($term_id > 0)
                                                            {
                                                                $query .= "INNER JOIN ". $wpdb->term_relationships ." tr1 ON (". $wpdb->posts .".ID = tr1.object_id)";
                                                            }
                                                            
                                                        $query .= "WHERE 1=1";
                                                        
                                                        if($term_id > 0)
                                                            {
                                                                $all_terms[] = $term_id;
                                                                $all_terms = array_merge( $all_terms, get_term_children($term_id, $taxonomy));
                                                                
                                                                //retrieve the taxonomy ids
                                                                $tax_query = "SELECT term_taxonomy_id FROM ". $wpdb->term_taxonomy ."
                                                                                    WHERE term_id IN (". implode(",", $all_terms ) .")";
                                                                $results = $wpdb->get_results($tax_query);
                                                                
                                                                $term_taxonomy_id_list = array();
                                                                foreach ($results as $term_taxonomy)
                                                                    $term_taxonomy_id_list[] = $term_taxonomy->term_taxonomy_id;
                                                                
                                                                $query .= " AND ( tr1.term_taxonomy_id IN (". implode(",", $term_taxonomy_id_list) .") )";
                                                            }
                                                            
                                                        $query .= " AND pm1.meta_key = '". $wpdb->escape($custom_field_name) ."'
                                                                    AND ". $wpdb->posts .".post_type = '".$post_type."' 
                                                                    AND (". $wpdb->posts .".post_status = 'publish') 
                                                                    ORDER BY pm1.meta_value ". $new_order;
                                                        $results = $wpdb->get_results($query);    
                                                        
                                                        //retrieve the list of posts which contain this custom field
                                                        foreach ($results as $result)
                                                            $order_list[] = $result->ID;
                                                            
                                                        if (count($order_list) > 0 )
                                                            {
                                                                    
                                                                $counter = 1;  
                                                                
                                                                $orderBy = "CASE ";
                                                                foreach ($order_list as $post_id)
                                                                    {
                                                                        $orderBy .= " WHEN ". $wpdb->posts .".ID = ".$post_id."  THEN  ". $counter;   
                                                                        $counter++;   
                                                                    }
                                                                
                                                                $orderBy .= " ELSE ". $counter ." END, ". $wpdb->posts .".post_date ". $new_order;
                                                            }
                                                        
                                                        break;
                                                    
                            default: 
                                                        $orderBy = $wpdb->posts .".". $new_orderBy . " " . $new_order;
                                                        
                                                        break;
                            
                        }
                        
                                           
                    return $orderBy;   
                }

            //check if the current taxonmy isn't restricted by filters
            if($taxonomy != '_archive_')
                {
                    $object_taxonomies = get_object_taxonomies($post_type);
                    $object_taxonomies = apply_filters('apto_object_taxonomies', $object_taxonomies, $post_type);
                    if(count($object_taxonomies) > 0 && !in_array($taxonomy, $object_taxonomies))            
                        return $orderBy;
                }
            
            //pageline fix
            if ($post_type != '' && !post_type_exists($post_type))
                return $orderBy;

            if ($post_type != '')
                $post_type_info = get_post_type_object($post_type);
            
            //check if it's hiearhical and _archive_ to use the default in wp_posts in that case
            if ($taxonomy == '_archive_' && $post_type_info->hierarchical)
                {
                    return  "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date ASC";
                }

            $term_id = apto_get_query_taxonomy_term_id($taxonomy, $query);
            
            //build the order list
            $order_list  = apto_get_order_list($post_type, $term_id, $taxonomy, $query);
                        
            if (count($order_list) > 0 )
                $orderBy = "FIELD(".$wpdb->posts.".ID, ". implode(",", $order_list) ."), ".$wpdb->posts.".post_date DESC";
                  
            return($orderBy);   
            
        }
    
    
    function apto_get_order_type($query)
        {
            global $wpdb;
            
            $options = get_option('cpto_options');
            
            list($post_type, $taxonomy) = apto_get_query_post_type_taxonomy($query);
            
            if ($post_type != '' && !post_type_exists($post_type))
                return 'manual';
            
            $term_id = apto_get_query_taxonomy_term_id($taxonomy, $query);
                    
            //check against the saved settings
            if(isset($options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['order_type']))
                $order_type = $options['taxonomy_settings'][$post_type][$taxonomy][$term_id]['order_type'];
                else
                $order_type = 'manual';
                 
            return $order_type;
            
        }
    
    
    /**
    * 
    * Identify the post type and taxonomy for the query
    * 
    */
    function apto_get_query_post_type_taxonomy($query)
        {
            $options = get_option('cpto_options');
            
            if (isset($query->query_vars['post_type']))
                {
                    if (is_array($query->query_vars['post_type']))
                        {
                            //check if there is at least one post type within the array
                            if (count($query->query_vars['post_type']) > 0)
                                {
                                    $allow_post_types = array_intersect($options['allow_post_types'], $query->query_vars['post_type']);
                                    if(count($allow_post_types) > 0)
                                        {
                                            reset($allow_post_types);
                                            $post_type = current($allow_post_types);   
                                        }
                                        else
                                        $post_type = '';
                                        
                                    unset($allow_post_types);
                                }
                                else
                                $post_type = '';
                        }
                        else
                            {
                                $post_type = $query->query_vars['post_type'];
                            }
                }
                else $post_type = '';
            $taxonomy   = isset($query->query_vars['taxonomy']) ? $query->query_vars['taxonomy'] : '';

            if ($taxonomy == '')
                {
                    $taxonomy = apto_get_query_taxonomy($query);
                }
            
        
            if ($taxonomy != '' && $post_type == '')
                {
                    //try to identify the post_type, get the first assigned to that taxonomy
                    $post_types = get_post_types();
                    foreach( $post_types as $post_type_name ) 
                        {
                            if (is_object_in_taxonomy($post_type_name, $taxonomy) === TRUE)
                                {
                                    //use only if is not in the ignore list
                                    if (isset($options['allow_post_types']) && !in_array($post_type_name, $options['allow_post_types']))
                                        continue; 
                                        
                                    $post_type = $post_type_name;
                                    break;
                                }
                        }   
                }
        
                
            if ($post_type == '')
                $post_type = 'post';

            if ($taxonomy == '')
                $taxonomy = '_archive_';   
            
            
            return array($post_type, $taxonomy);
        }
    
    
    /**
    * 
    * Identify the term id of current query
    * 
    */
    function apto_get_query_taxonomy_term_id($taxonomy, $query)
        {
            $term_id = -1;
            if ($taxonomy != '' && $taxonomy != '_archive_' && isset($query->tax_query->queries[0]['terms']))
                {
                    //cat update
                    if (isset($query->query['cat']) && $query->query['cat'] > 0)
                        {
                            $term_id = $query->query['cat'];
                        }
                         
                    else if (count($query->tax_query->queries[0]['terms']) === 1)
                        {
                            //check for exclude in which case we use archive instead
                            if(isset($query->tax_query->queries[0]['operator']) && $query->tax_query->queries[0]['operator'] == 'NOT IN')
                                {
                                    $taxonomy   =   '_archive_';
                                    $term_id    =   '';   
                                }
                                else
                                {
                                    if(isset($query->tax_query->queries[0]['field']))
                                        {
                                            switch ($query->tax_query->queries[0]['field'])
                                                {
                                                    case 'term_id':
                                                    case 'id':
                                                                $term_id    = $query->tax_query->queries[0]['terms'][0];
                                                                break;
                                                    case 'slug':
                                                                $term_data  = get_term_by('slug', $query->tax_query->queries[0]['terms'][0], $taxonomy);    
                                                                if (is_object($term_data))
                                                                    $term_id    = $term_data->term_id;
                                                                break;
                                                }
                                        }
                                        else
                                        {
                                            //try to identify   
                                            preg_match("|\d+|",$query->tax_query->queries[0]['terms'][0], $_found);
                                            if (is_array($_found) && count($_found) > 0)
                                                {
                                                    $_terms_0 = $_found[0];
                                                    if ($_terms_0 == $query->tax_query->queries[0]['terms'][0])
                                                        $term_id    = $query->tax_query->queries[0]['terms'][0];
                                                        else
                                                            {
                                                                $term_data  = get_term_by('slug', $query->tax_query->queries[0]['terms'][0], $taxonomy);    
                                                                $term_id    = $term_data->term_id;
                                                            }
                                                }
                                                else
                                                    {
                                                        //unable to identify
                                                        $taxonomy   =   '_archive_';
                                                        $term_id    =   '';   
                                                    }
                                        }
                                }
                        }
                     
                     //this needs multiple terms order, use _archive_instead   
                     else if (count($query->tax_query->queries[0]['terms']) > 1)
                        {
                            $taxonomy   =   '_archive_';
                            $term_id    =   '';
                        }
                    
                    
                }
                
            return $term_id;      
            
        }
    
    /**
    * 
    *  Try to identify if there is a taxonomy query
    */
    function apto_get_query_taxonomy($query)
        {
            //check for category tax
            if ($query->query_vars['cat'] != '' || $query->query_vars['category_name'] != '' || (is_array($query->query_vars['category__in']) && count($query->query_vars['category__in']) > 0) || (is_array($query->query_vars['category__and']) && count($query->query_vars['category__and']) > 0))
                return 'category';
            
            //check for tag tax
            if ($query->query_vars['tag'] != '' || (is_array($query->query_vars['tag__in']) && count($query->query_vars['tag__in']) > 0) || (is_array($query->query_vars['tag__and']) && count($query->query_vars['tag__and']) > 0) || (is_array($query->query_vars['tag_slug__in']) && count($query->query_vars['tag_slug__in']) > 0))
                return 'post_tag';
            
            if (isset($query->query_vars['tax_query'][0]['taxonomy']))
                {
                    $taxonomy = $query->query_vars['tax_query'][0]['taxonomy'];
                    return $taxonomy;
                }
                
            return '';
        }
    
    /**
    * 
    *  Get Order List
    * 
    */
    function apto_get_order_list($post_type, $term_id = '-1', $taxonomy = '_archive_', $wp_query = '')
        {
            $order_list = array();
            
            global $wpdb, $blog_id;
            
            if ($term_id == '' || $term_id === FALSE)
                {
                    $term_id = -1;
                    $taxonomy = '_archive_';   
                }
            
            if ($taxonomy == '' || $taxonomy === FALSE)
                $taxonomy = '_archive_';
                
            if ($post_type == '' && $taxonomy == "_archive_")
                {
                    $post_type = 'post';
                }
            
            $query = "SELECT post_id FROM `". $wpdb->base_prefix ."apto` WHERE `blog_id` = " . $blog_id;
            if ($post_type !== '')
                $query .= " AND `post_type` = '".$post_type."'";
            if ($term_id !== '')
                $query .= " AND `term_id` = '".$term_id."'";
            if ($taxonomy !== '')
                $query .= " AND `taxonomy` = '".$taxonomy."'";
                
            //apply language if WPML deployed
            $lang = apto_get_blog_language();
            if ($lang != '')
                $query .= " AND `lang` = '".$lang."'";
               
            $query .= " ORDER BY id ASC";
            
            $results = $wpdb->get_results($query);
            
            foreach ($results as $result)
                $order_list[] = $result->post_id;
            
            
            $order_list = apply_filters('apto_get_order_list', $order_list, $wp_query);
            
            return $order_list;   
        }
        
    /**
    * 
    * Apply the sticky order if exists
    * 
    */
    function sticky_posts_apto_get_order_list($order_list, $query)
        {
            if(!is_array($order_list) || count($order_list) < 1)
                return $order_list;
            
            //check for ignore
            $options = get_option('cpto_options');
            
            if ($options['autosort'] == "1")
                {
                    //check for ignore_sticky_posts is on
                    if($options['ignore_sticky_posts'] == "1")
                        return $order_list;
                        
                    $sticky_list = get_option('sticky_posts');
                    if(!is_array($sticky_list) || count($sticky_list) < 0)
                        return $order_list;
                }   
                else
                {
                    //check for ignore_sticky_posts  query param
                    if(is_object($query) && isset($query->query) && isset($query->query['ignore_sticky_posts']) && ($query->query['ignore_sticky_posts'] == '1' || $query->query['ignore_sticky_posts'] === TRUE))
                        return $order_list; 
                } 
            
            return $order_list;
        }
        
        
    /**
    * 
    * Get first term of a category
    * 
    */
    function cpto_get_first_term($taxonomy)
        {
            $argv = array(
                            'hide_empty'        => 0, 
                            'hierarchical'      => 1,
                            'show_count'        => 1, 
                            'orderby'           => 'name', 
                            'taxonomy'          =>  $taxonomy
                            );
            
            $terms = get_terms( $taxonomy, $argv );
            
            if(count($terms) < 1)
                return FALSE;
            
            //find first term with parent = 0
            for ($i = 0; $i <= count($terms); $i++)
                {
                    if ($terms[$i]->parent == 0)
                        return $terms[$i]->term_id;       
                } 
            
            return FALSE;
        }
    
    /**
    * 
    * Filter to return the posts for the given interval
    * 
    * timestamp in unix format
    * 
    */
    function apto_filter_posts_where_interval($where)
        {
            global $_apto_filter_posts_where_interval_after_time, $_apto_filter_posts_where_interval_before_time;
            
            if ($_apto_filter_posts_where_interval_after_time == '')
                return $where;
            
            if ($_apto_filter_posts_where_interval_before_time == '')
                $_apto_filter_posts_where_interval_before_time = strtotime('+1 day');
                
            $where .= " AND post_date >= '" . date('Y-m-d', $_apto_filter_posts_where_interval_after_time) . "' AND post_date <= '" . date('Y-m-d', $_apto_filter_posts_where_interval_before_time) . "'";
            
            return $where;   
        }
    
    /**
    * 
    * Return the burrent blog language
    * This check for WPMU install
    * 
    */
    function apto_get_blog_language()
        {
            $lang = '';
            
            //check if WPML is active
            if (defined('ICL_LANGUAGE_CODE'))
                {
                    $lang = ICL_LANGUAGE_CODE;
                }
            
            if ($lang == '')
                $lang = 'en';
            
            return $lang;   
        }
    
        
    /**
    * @desc 
    * 
    * Check the latest plugin version
    * 
    */
    function cpto_check_plugin_version($plugin)
        {
            if( strpos( CPTPATH . '/advanced-post-types-order.php', $plugin ) !== FALSE )
                {
                    //check last update check attempt
                    $last_check = get_option('acpto_last_version_check');
                    if (is_numeric($last_check) && (time() - 60*60*12) > $last_check)
                        {
                            $last_version_data = wp_remote_fopen(CPT_VERSION_CHECK_URL);
                            update_option('acpto_last_version_check_data', $last_version_data);    
                        }
                        else
                            {
                                $last_version_data = get_option('acpto_last_version_check_data');  
                            }
                    
                    if($last_version_data !== FALSE && $last_version_data != '') 
                        {
                            $info_raw = explode( '/',$last_version_data );
                            $info = array();
                            foreach ($info_raw as $line)
                                {
                                    list($name, $value)= explode("=", $line);
                                    $info[$name] = $value;
                                }
                                
                            if( ( version_compare( strval( $info['version'] ), CPTVERSION , '>' ) == 1 ) ) 
                                {
                                    ?>
                                        <tr class="plugin-update-tr">
                                            <td colspan="3" class="plugin-update colspanchange">
                                                <div class="update-message"><?php _e( "There is a new version of Advanced Post Types Order. Use your purchase link to update or contact us if you lost it.", 'apto' ) ?></div>
                                            </td>
                                        </tr>
                                    <?php
                                } 
                        }
                        
                    //update last version check attempt
                    update_option('acpto_last_version_check', time());
                }   
            
        }
        
        
        
    function cpto_get_previous_post_where($where, $in_same_cat, $excluded_categories)
        {
            global $post;
            
            //fetch the order for the current post type 
            add_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            $posts_order = apto_get_order_list($post->post_type);
            remove_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            
            //check if there is no defined order
            if (count($posts_order) == 0)
                return $where;
                
            $where = '';
            
            return $where;
        }
        
    function cpto_get_previous_post_sort($sort)
        {
            add_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            $sort = apto_get_adjacent_post_sort(TRUE, $sort);
            remove_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            
            return $sort;
        }

    function cpto_get_next_post_where($where, $in_same_cat, $excluded_categories)
        {
            global $post;
            
            //fetch the order for the current post type
            add_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter'); 
            $posts_order = apto_get_order_list($post->post_type);
            remove_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            
            //check if there is no defined order
            if (count($posts_order) == 0)
                return $where; 
            
            $where = '';
            
            return $where;
        }

    function cpto_get_next_post_sort($sort)
        {
            add_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            $sort = apto_get_adjacent_post_sort(FALSE, $sort);
            remove_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
            
            return $sort;    
        }
        
    function apto_get_adjacent_post_sort($previous = TRUE, $sort)
        {
            global $post, $wpdb, $blog_id;
            
            $options = get_option('cpto_options');
                
            //check if the current post_type is active in the setings
            if (isset($options['allow_post_types']) && !in_array($post->post_type, $options['allow_post_types']))
                return $sort;

            $post_type = $post->post_type;
                
            //fetch the order for the current post type 
            $term_id = -1;
            $taxonomy = '_archive_';
            $posts_order = apto_get_order_list($post->post_type, $term_id, $taxonomy);
            
            //check if there is no defined order
            if (count($posts_order) == 0)
                return $sort; 
            
            //get the current element key
            $current_position_key = array_search($post->ID, $posts_order);
            
            if ($previous === TRUE)
                $required_index = $current_position_key + 1;
                else
                $required_index = $current_position_key - 1;
            
            //check if there is another position after the current in the list
            if (isset($posts_order[ ($required_index) ]))
                {
                    //found
                    $sort = 'ORDER BY FIELD(p.ID, "'. $posts_order[ ($required_index) ] .'") DESC LIMIT 1 ';   
                }
                else
                {
                    //not found 
                    $sort = 'ORDER BY p.post_date DESC LIMIT 0';  
                }
   
            return $sort;
        
        
        } 
    
    /**
    * Enhanced post adjancent links
    * 
    * @param mixed $format
    * @param mixed $link
    * @param mixed $use_custom_sort
    * Ignore the custom order if defined
    * 
    * @param mixed $in_term
    * Provide a term_id if the links should be returned for other post types in the same term_id. The $in_taxonomy is require along with
    * 
    * @param mixed $in_taxonomy
    * Provide a taxonomy name if the links should be returned for other post types in the same term_id. The $in_taxonomy is require along with
    * 
    * @return mixed
    */
    function previous_post_type_link($format='&laquo; %link', $link='%title', $use_custom_sort = FALSE, $term_id = '', $taxonomy = '')
        {
            adjacent_post_type_link($format, $link, $use_custom_sort, $term_id, $taxonomy, TRUE);   
        }
        
    /**
    * Enhanced post adjancent links
    * 
    * @param mixed $format
    * @param mixed $link
    * @param mixed $use_custom_sort
    * Ignore the custom order if defined
    * 
    * @param mixed $in_term
    * Provide a term_id if the links should be returned for other post types in the same term_id. The $in_taxonomy is require along with
    * 
    * @param mixed $in_taxonomy
    * Provide a taxonomy name if the links should be returned for other post types in the same term_id. The $in_taxonomy is require along with
    * 
    * @return mixed
    */
    function next_post_type_link($format='&laquo; %link', $link='%title', $use_custom_sort = FALSE, $term_id = '', $taxonomy = '')
        {
            adjacent_post_type_link($format, $link, $use_custom_sort, $term_id, $taxonomy, FALSE);   
        }
    
    
    function adjacent_post_type_link($format, $link, $use_custom_sort = FALSE, $term_id = '', $taxonomy = '', $previous = TRUE) 
        {
            if ( $previous && is_attachment() )
                $post = & get_post($GLOBALS['post']->post_parent);
                else
                $post = apto_get_adjacent_post($use_custom_sort, $term_id, $taxonomy, $previous);

            if ( !$post )
                return;

            $title = $post->post_title;

            if ( empty($post->post_title) )
            $title = $previous ? __('Previous Post') : __('Next Post');

            $title = apply_filters('the_title', $title, $post->ID);
            $date = mysql2date(get_option('date_format'), $post->post_date);
            $rel = $previous ? 'prev' : 'next';

            $string = '<a href="'.get_permalink($post).'" rel="'.$rel.'">';
            $link = str_replace('%title', $title, $link);
            $link = str_replace('%date', $date, $link);
            $link = $string . $link . '</a>';

            $format = str_replace('%link', $link, $format);

            $adjacent = $previous ? 'previous' : 'next';
            echo apply_filters( "{$adjacent}_post_link", $format, $link );
        }
        
        
    function apto_get_adjacent_post( $use_custom_sort = FALSE, $term_id = '', $taxonomy = '', $previous = TRUE ) 
        {
            global $post, $wpdb;
            
            if ( empty( $post ) )
                return null;

            $posts_order = array();
            
            if ($use_custom_sort === TRUE)
                {
                    //fetch the order for the current post type 
                    add_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
                    $posts_order = apto_get_order_list($post->post_type, $term_id, $taxonomy);
                    remove_filter('apto_get_order_list', 'apto_get_order_list_post_status_filter');
                }
            
            if ($use_custom_sort !== TRUE || count($posts_order) == 0)
                {
    
                    $current_post_date = $post->post_date;
                    
                    $in_same_cat = false;
                    $excluded_categories = '';
                    
                    $adjacent = $previous ? 'previous' : 'next';
                    $op = $previous ? '<' : '>';
                    $order = $previous ? 'DESC' : 'ASC';
                    
                    $join = $where = $sort = $group = '';
                    
                    $where = $wpdb->prepare(" WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish'", array($current_post_date, $post->post_type));
                    
                    if ($term_id != '' && $taxonomy != '')
                        {
                            if(!taxonomy_exists($taxonomy))
                                return null;
                            
                            $join  = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id "; 
                            
                            $children = array();
                            $children = array_merge( $children, get_term_children( $term_id, $taxonomy ) );
                            $children[] = $term_id;
                            
                            $where .= " AND ( tr.term_taxonomy_id IN (". implode(",", $children) .") ) ";
                            $group = " GROUP BY p.ID";
                        }

                    $sort =  " ORDER BY p.post_date $order LIMIT 1";
                    
                    $join  = apply_filters( "get_{$adjacent}_post_type_join", $join, $term_id, $taxonomy );
                    $where = apply_filters( "get_{$adjacent}_post_type_where", $where, $term_id, $taxonomy);
                    $group = apply_filters( "get_{$adjacent}_post_type_group", $group, $term_id, $taxonomy);
                    $sort  = apply_filters( "get_{$adjacent}_post_type_sort", $sort, $term_id, $taxonomy);
 
                    $query = "SELECT p.* FROM $wpdb->posts AS p $join $where $group $sort";
                    $query_key = 'adjacent_post_type_' . md5($query);
                    $result = wp_cache_get($query_key, 'counts');
                    if ( false !== $result )
                        return $result;

                    $result = $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $group $sort");
                    if ( null === $result )
                        $result = '';

                    wp_cache_set($query_key, $result, 'counts');
                    return $result;
                }

            //get the current element key
            $current_position_key = array_search($post->ID, $posts_order);
            
            if ($previous === TRUE)
                $required_index = $current_position_key + 1;
                else
                $required_index = $current_position_key - 1;
            
            //check if there is another position after the current in the list
            if (isset($posts_order[ ($required_index) ]))
                {
                    //found
                    $sort = 'ORDER BY FIELD(p.ID, "'. $posts_order[ ($required_index) ] .'") DESC LIMIT 1 ';   
                }
                else
                {
                    //not found 
                    $sort = 'ORDER BY p.post_date DESC LIMIT 0';  
                }

            $adjacent = $previous ? 'previous' : 'next';
            $join = $where = '';
            
            $join  = apply_filters( "get_{$adjacent}_post_type_join",   $join,  $term_id, $taxonomy );
            $where = apply_filters( "get_{$adjacent}_post_type_where",  $where, $term_id, $taxonomy );
            $sort  = apply_filters( "get_{$adjacent}_post_type_sort",   $sort,  $term_id, $taxonomy);

            $query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
            $query_key = 'adjacent_post_type_' . md5($query);
            $result = wp_cache_get($query_key, 'counts');
            if ( false !== $result )
                return $result;

            $result = $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $sort");
            if ( null === $result )
                $result = '';

            wp_cache_set($query_key, $result, 'counts');
            return $result;
        }
        
    
    function apto_get_order_list_post_status_filter($order_list)
        {
            if (count($order_list) == 0)
                return $order_list;
            
            global $wpdb;
                
            $allow_post_status = array (
                                        'publish'
                                            );
            
            $query = "SELECT ID FROM " . $wpdb->posts ." 
                        WHERE ID IN (".implode(",", $order_list).") AND post_status IN ('". implode("','", $allow_post_status) ."')
                        ORDER BY FIELD(".$wpdb->posts.".ID, ". implode(",", $order_list) .")";
            $results = $wpdb->get_results($query); 
            
            $order_list = array();
            
            foreach ($results as $result)
                $order_list[] = $result->ID;
            
            return $order_list;   
        }
    
    /**
    * Update the order of the archive/taxonomy for this post type to make sure it's always in top of the list as is the latest
    * 
    * @param mixed $post_ID
    * @param mixed $post
    */
    function apto_wp_insert_post($post_ID, $post)
        {
            if (wp_is_post_revision($post_ID))
                return;   
            
            if ($post->post_status != 'publish')
                return;
            
            global $wpdb, $blog_id;
            
            $lang = apto_get_blog_language();
            $post_type_data = get_post_type_object($post->post_type);

            //put the post type in the top of the archive list if thre is a custom order defined list
            $posts_order = apto_get_order_list($post->post_type, "-1", "_archive_");
            if (count($posts_order) > 0 && array_search($post_ID, $posts_order) === FALSE && $post_type_data->hierarchical === FALSE)
                {
                    //remove the current order
                    $query = "DELETE FROM `". $wpdb->base_prefix ."apto`
                                WHERE `post_type` = '".$post->post_type ."' AND `term_id` = '-1' AND `taxonomy` = '_archive_' AND `blog_id` = ".$blog_id . " AND `lang` = '".$lang."'";
                    $result = $wpdb->get_results($query);
                    
                    array_unshift($posts_order, $post_ID);
                    
                    //add the list
                    $position = 0;
                    foreach( $posts_order as $list_post_ID ) 
                        {
                            //maintain the simple order 
                            $wpdb->update( $wpdb->posts, array('menu_order' => ($position + 1)), array('ID' => $post_ID) );
                            
                            $query = "INSERT INTO `". $wpdb->base_prefix ."apto` 
                                        (`post_id`, `term_id`, `post_type`, `taxonomy`, `blog_id`, `lang`) 
                                        VALUES ('".$list_post_ID."', '-1', '".$post->post_type."', '_archive_', ".$blog_id.", '".$lang."');"; 
                            $results = $wpdb->get_results($query);
                        }
                }
            
            $object_taxonomies = get_object_taxonomies($post->post_type);
            if (count($object_taxonomies) === 0)
                return;
            
            //retrieve the terms for each taxonomy that the current post belong
            foreach ($object_taxonomies as $object_taxonomy)
                {
                    $object_terms = wp_get_object_terms($post_ID, $object_taxonomy);
                    if (count($object_terms) > 0)
                        {
                            foreach ($object_terms as $main_object_term)
                                {
                                    //we need to process all child terms too
                                    $children = array();
                                    $children = array_merge( $children, get_term_children( $main_object_term->term_id, $object_taxonomy ) );
                                    $children[] = $main_object_term->term_id;
                                    
                                    foreach ($children as $object_term)
                                    //put the post type in the top of the archive list if thre is a custom order defined list
                                    $posts_order = apto_get_order_list($post->post_type, $object_term, $object_taxonomy);
                                    if (count($posts_order) > 0 && array_search($post_ID, $posts_order) === FALSE)
                                        {
                                            //remove the current order
                                            $query = "DELETE FROM `". $wpdb->base_prefix ."apto`
                                                        WHERE `post_type` = '".$post->post_type ."' AND `term_id` = '".$object_term."' AND `taxonomy` = '".$object_taxonomy."' AND `blog_id` = ".$blog_id . " AND `lang` = '".$lang."'";
                                            $result = $wpdb->get_results($query);
                                            
                                            array_unshift($posts_order, $post_ID);
                                            
                                            //add the list
                                            $position = 0;
                                            foreach( $posts_order as $list_post_ID ) 
                                                {
                                                    //maintain the simple order 
                                                    $wpdb->update( $wpdb->posts, array('menu_order' => ($position + 1)), array('ID' => $post_ID) );
                                                    
                                                    $query = "INSERT INTO `". $wpdb->base_prefix ."apto` 
                                                                (`post_id`, `term_id`, `post_type`, `taxonomy`, `blog_id`, `lang`) 
                                                                VALUES ('".$list_post_ID."', '".$object_term."', '".$post->post_type."', '".$object_taxonomy."', ".$blog_id.", '".$lang."');"; 
                                                    $results = $wpdb->get_results($query);
                                                }
                                        }
                                } 
                        }
                }
        }
        
    /**
    * 
    * Show the sticky info when in re-order interface
    * 
    */
    add_filter('apto_reorder_item_additional_details', 'apto_showsticky_info', 10, 2);
    function apto_showsticky_info($additiona_details, $post_data)
        {
            $sticky_list = get_option('sticky_posts');
            
            if(!is_array($sticky_list) || count($sticky_list) < 0)
                return $additiona_details;
                
            if(in_array($post_data->ID, $sticky_list))
                $additiona_details .= ' <span>Sticky</span>';
            
            return $additiona_details;   
        }
    
  
?>