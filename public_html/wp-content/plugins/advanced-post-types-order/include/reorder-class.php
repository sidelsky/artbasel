<?php

class ACPTO
    {
        var $current_post_type = null;

        function ACPTO() 
            {
                add_action( 'admin_init', array(&$this, 'registerFiles'), 11 );
                add_action( 'admin_init', array(&$this, 'checkPost'), 10 );

                if (isset($_GET['page']) && $_GET['page'] == 'cpto-options')
                    {
                        add_action( 'admin_menu', 'cpt_optionsUpdate' );

                        if (isset($_POST['apto_form_submit']))
                            add_action( 'admin_head', 'cpt_optionsUpdateMessage', 10 );
                    }

                add_action( 'admin_menu', array(&$this, 'addMenu'), 99 );
            }

        function registerFiles()
            {
                if ( $this->current_post_type != null )
                    {
                        wp_enqueue_script('jQuery');
                        wp_enqueue_script('jquery-ui-sortable');
                    }

                wp_register_style('CPTStyleSheets', CPTURL . '/css/cpt.css');
                wp_enqueue_style( 'CPTStyleSheets');
            }

        function checkPost()
            {
                if ( isset($_GET['page']) && substr($_GET['page'], 0, 17) == 'order-post-types-' )
                    {
                        //check if there is chosed another post type which belong to the ui menu
                        if (isset($_GET['selected_post_type']))
                            {
                                $this->current_post_type = get_post_type_object($_GET['selected_post_type']);
                            }
                            else
                            {
                                $this->current_post_type = get_post_type_object(str_replace( 'order-post-types-', '', $_GET['page'] ));
                            }
                        if ( $this->current_post_type == null)
                            {
                                wp_die('Invalid post type');
                            }
                    }
            }

        function addMenu()
            {
                global $userdata;

                $options = get_option('cpto_options');

                if(isset($options['capability']) && !empty($options['capability']))
                    {
                        $capability = $options['capability'];
                    }
                else if (is_numeric($options['level']))
                    {
                        //maintain the old user level compatibility
                        $capability = userdata_get_user_level();
                    }
                    else
                        {
                            $capability = 'install_plugins';
                        }

                //apply a filter to allow capability overwrite; helpfull when in multisite environment
                $capability = apply_filters('apto_reorder_capability', $capability);

                //put a menu for all custom_type
                $post_types = get_post_types();
                $ignore = array (
                                    'revision',
                                    'nav_menu_item'
                                    );
                foreach( $post_types as $post_type_name )
                    {
                        if (in_array($post_type_name, $ignore))
                            continue;

                        //ignore bbpress
                        if ($post_type_name == 'reply' || $post_type_name == 'topic')
                            continue;

                        //check for exclusion
                        $exclude = FALSE;
                        if (isset($options['allow_post_types']) && !in_array($post_type_name, $options['allow_post_types']))
                            $exclude = TRUE;
                        $code_exclude = apply_filters('apto_restrict_reorder_interface',$post_type_name);
                        if (is_bool($code_exclude))
                            $exclude = $code_exclude;

                        if ($exclude === TRUE)
                            continue;

                        $post_type_details = get_post_type_object($post_type_name);
                        //check if belong to another menu ui
                        if (!is_bool($post_type_details->show_in_menu))
                            {
                                //no need to show
                                continue;
                            }

                        if ($post_type_name == 'post')
                            add_submenu_page('edit.php', 'Re-Order', 'Re-Order', $capability, 'order-post-types-'.$post_type_name, array(&$this, 'SortPage') );
                        elseif ($post_type_name == 'attachment')
                            add_submenu_page('upload.php', 'Re-Order', 'Re-Order', $capability, 'order-post-types-'.$post_type_name, array(&$this, 'SortPage') );
                        else
                            add_submenu_page('edit.php?post_type='.$post_type_name, 'Re-Order', 'Re-Order', $capability, 'order-post-types-'.$post_type_name, array(&$this, 'SortPage') );
                    }
            }


        function SortPage()
            {
                global $wpdb, $wp_locale;

                $options = get_option('cpto_options');

                $post_type = $this->current_post_type->name;

                $is_hierarchical = $this->current_post_type->hierarchical;

                $current_taxonomy   = isset($_GET['current_taxonomy']) ? $_GET['current_taxonomy'] : '';

                if ($current_taxonomy != "_archive_" && !taxonomy_exists($current_taxonomy))
                    $current_taxonomy = '';

                $m                  = isset($_GET['m']) ? $_GET['m'] : 0;
                $cat                = isset($_GET['cat']) ? (int)$_GET['cat'] : -1;
                $s                  = isset($_GET['s']) ? $_GET['s'] : '';

                //check for order reset
                if (isset($_POST['order_reset']) && $_POST['order_reset'] == '1' && $post_type != '')
                    {
                        $_reset_cat = trim($_POST['cat']);
                        $cat = $_reset_cat;
                        $current_taxonomy   = isset($_POST['current_taxonomy']) ? $_POST['current_taxonomy'] : '';
                        reset_post_order($post_type, $_reset_cat, $current_taxonomy);
                        echo '<div id="message" class="updated"><p>' . __('Reset Order Successfully', 'cpt') . '</p></div>';
                    }

                //hold the current_taxonomy selection to be restored on new access
                $cpto_taxonomy_selections = get_option('cpto_taxonomy_selections');
                if (!is_array($cpto_taxonomy_selections))
                    $cpto_taxonomy_selections = array();

                //save the current taxonomy selection
                if ($current_taxonomy != '' && ((taxonomy_exists($current_taxonomy)) || $current_taxonomy == "_archive_"))
                    {
                        if (!isset($cpto_taxonomy_selections[$post_type]) || !is_array($cpto_taxonomy_selections[$post_type]))
                            $cpto_taxonomy_selections[$post_type] = array();

                        $cpto_taxonomy_selections[$post_type]['taxonomy'] = $current_taxonomy;
                    }

                //save the current term selection
                if ($cat > -1)
                    {
                        if (!is_array($cpto_taxonomy_selections[$post_type]))
                            $cpto_taxonomy_selections[$post_type] = array();

                        $cpto_taxonomy_selections[$post_type]['term_id'] = $cat;
                    }

                //try to restore if it's emtpy
                if ($current_taxonomy == '')
                    {
                        if (array_key_exists($post_type, $cpto_taxonomy_selections) && is_array($cpto_taxonomy_selections[$post_type]) && array_key_exists('taxonomy', $cpto_taxonomy_selections[$post_type]))
                            $current_taxonomy   = $cpto_taxonomy_selections[$post_type]['taxonomy'];

                        //check if the taxonomy exists
                        if ($current_taxonomy != '' && $current_taxonomy != "_archive_" && taxonomy_exists($current_taxonomy) === FALSE)
                            $current_taxonomy = '';


                        //restore the term if it's not empty
                        if ($cat < 0 && $current_taxonomy != "_archive_")
                            {
                                if (array_key_exists($post_type, $cpto_taxonomy_selections) && is_array($cpto_taxonomy_selections[$post_type]) && array_key_exists('term_id', $cpto_taxonomy_selections[$post_type]))
                                    $cat   = $cpto_taxonomy_selections[$post_type]['term_id'];

                                //make sure the term actualy stil ecists
                                if ($current_taxonomy != '')
                                    {
                                        if (get_term_by('id', $cat, $current_taxonomy) === FALSE)
                                            $cat = -1;
                                    }
                                    else
                                        $cat = -1;
                            }
                    }

                if ($current_taxonomy != '' && $current_taxonomy != '_archive_' && ($cat == '' || $cat <=0))
                    {
                        $cat = cpto_get_first_term($current_taxonomy);
                    }

                //check if the restored term_id is available/valid
                if ($current_taxonomy != '' && $current_taxonomy != '_archive_' && $cat != '' && $cat > 0)
                    {
                        if (get_term_by('id', $cat, $current_taxonomy) === FALSE)
                            $cat = cpto_get_first_term($current_taxonomy);
                    }

                //use _archive_ if still not data
                if ($current_taxonomy == '')
                    $current_taxonomy = '_archive_';


                //set as default for auto
                $order_type = (isset($options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order_type'])) ? $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order_type'] : 'manual';

                //check for order type update
                if (isset($_GET['order_type']))
                    {
                        $new_order_type = $_GET['order_type'];
                        if ($new_order_type != 'auto' && $new_order_type != 'manual')
                            $new_order_type = '';

                        if ($new_order_type != '')
                            {
                                $order_type = $new_order_type;

                                //save the new order
                                $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order_type'] = $order_type;

                                //update the orde_by
                                if (isset($_GET['auto_order_by']))
                                    {
                                        $new_order_by = $_GET['auto_order_by'];
                                        if ($new_order_by != '')
                                            $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order_by'] = $new_order_by;
                                    }

                                //update the custom field name
                                if (isset($_GET['auto_custom_field_name']))
                                    {
                                        $new_custom_field_name = trim(stripslashes($_GET['auto_custom_field_name']));
                                        $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['custom_field_name'] = $new_custom_field_name;
                                    }

                                //update the orde_by
                                if (isset($_GET['auto_order']))
                                    {
                                        $new_order = $_GET['auto_order'];
                                        if ($new_order_by != '')
                                            $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order'] = $new_order;
                                    }

                                update_option('cpto_options', $options);
                            }
                    }

                ?>
                <div class="wrap">
                    <div class="icon32" id="icon-edit"><br></div>
                    <h2><?php echo $this->current_post_type->labels->singular_name ?><?php _e( " -  Re-order", 'apto' ) ?></h2>

                    <div id="ajax-response"></div>

                    <noscript>
                        <div class="error message">
                            <p><?php _e( "This plugin can't work without javascript, because it's use drag and drop and AJAX.", 'apto' ) ?></p>
                        </div>
                    </noscript>

                    <div class="clear"></div>

                    <?php

                        //cehck if there are more post types in the current menu
                        $site_post_types = get_post_types();
                        $site_post_types_menus = array();
                        $ignore = array (
                                            'revision',
                                            'nav_menu_item'
                                            );
                        foreach( $site_post_types as $site_post_type_name )
                            {
                                if (in_array($site_post_type_name, $ignore))
                                    continue;

                                //check for exclusion
                                if (isset($options['allow_post_types']) && !in_array($site_post_type_name, $options['allow_post_types']))
                                    continue;

                                $post_type_details = get_post_type_object($site_post_type_name);

                                if (is_bool($post_type_details->show_in_menu))
                                    {
                                        //this will appear in it's own ui menu
                                        $site_post_types_menus['edit.php?post_type='.$site_post_type_name][] = $site_post_type_name;
                                    }
                                    else
                                        $site_post_types_menus[$post_type_details->show_in_menu][] = $site_post_type_name;
                            }

                        //find if there's another post type root for this menu
                        $menu_root_post_type = $post_type;
                        if (count($site_post_types_menus) > 0)
                            {
                                $found_menu = '';
                                foreach ($site_post_types_menus as $key => $site_post_types_menu)
                                    {
                                        if (in_array($post_type, $site_post_types_menu))
                                            {
                                                $found_menu = $key;
                                                break;
                                            }
                                    }

                                //check all post types of this menu and get the first with boolean show_in_menu
                                if (isset($site_post_types_menus[$found_menu]) && count($site_post_types_menus[$found_menu]) > 1)
                                    {
                                        foreach ($site_post_types_menus[$found_menu] as $site_menu_post_types)
                                            {
                                                $post_type_details = get_post_type_object($site_menu_post_types);
                                                if (is_bool($post_type_details->show_in_menu))
                                                    {
                                                        $menu_root_post_type = $post_type_details->name;
                                                        break;
                                                    }
                                            }
                                    }
                            }

                    ?>

                    <form action="<?php echo admin_url('edit.php'); ?>" method="get" id="apto_form">
                         <?php
                            if ( !in_array( $post_type, array('post','attachment') ) )
                                {
                         ?>
                        <input id="apto_post_type" type="hidden" value="<?php echo $menu_root_post_type ?>" name="post_type" />
                        <?php } ?>
                        <input type="hidden" value="order-post-types-<?php echo $menu_root_post_type ?>" name="page" />

                    <?php

                        //check if there are more than a post type in this menu
                        if (count($site_post_types_menus) > 0)
                            {
                                $found_menu = '';
                                foreach ($site_post_types_menus as $key => $site_post_types_menu)
                                    {
                                        if (in_array($post_type, $site_post_types_menu))
                                            {
                                                $found_menu = $key;
                                                break;
                                            }
                                    }

                                //check this menu count
                                if (count($site_post_types_menus[$found_menu]) > 1)
                                    {
                                        ?><h2 class="subtitle">Your menu contain more than one custom post type</h2>
                                        <table cellspacing="0" class="wp-list-post-types widefat fixed">
                                            <?php

                                                foreach ($site_post_types_menus[$found_menu] as $site_menu_post_types)
                                                    {
                                                        $post_type_details = get_post_type_object($site_menu_post_types);

                                                        ?>
                                                        <tr valign="top" class="">
                                                            <th class="check-column" scope="row"><input type="radio" onclick="apto_change_post_type(this)" value="<?php echo $site_menu_post_types ?>" <?php if ($post_type == $site_menu_post_types) {echo 'checked="checked"';} ?> name="selected_post_type">&nbsp;</th>
                                                            <td class="categories column-categories"><?php echo $post_type_details->labels->singular_name ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                ?>
                                        </tbody>
                                        </table>
                                        <?php

                                    }
                            }

                        //check the post taxonomies.
                        $object_taxonomies = get_object_taxonomies($post_type);
                        $object_taxonomies = apply_filters('apto_object_taxonomies', $object_taxonomies, $post_type);
                        if(!is_array($object_taxonomies))
                            $object_taxonomies = array();

                        if($current_taxonomy != '_archive_' && !in_array($current_taxonomy, $object_taxonomies))
                            $current_taxonomy = '';

                        if ($current_taxonomy == '' && count($object_taxonomies) >= 1)
                            {
                                //use categories as default
                                if (in_array('category', $object_taxonomies))
                                    {
                                        $current_taxonomy = 'category';
                                    }
                                    else
                                        {
                                            reset($object_taxonomies);
                                            $current_taxonomy = current($object_taxonomies);
                                        }
                            }

                        if ($current_taxonomy == '' && count($object_taxonomies) < 1)
                            {
                                $current_taxonomy = '_archive_';
                            }

                        $cpto_taxonomy_selections[$post_type]['taxonomy']   = $current_taxonomy;
                        $cpto_taxonomy_selections[$post_type]['term_id']    = $cat;

                        update_option('cpto_taxonomy_selections', $cpto_taxonomy_selections);
                        $current_taxonomy_info = get_taxonomy($current_taxonomy);


                        if (count($object_taxonomies) > 0)
                            {

                                ?>

                                <h2 class="subtitle"><?php echo $this->current_post_type->labels->singular_name ?> <?php _e( "Archive & Taxonomies", 'apto' ) ?></h2>
                                <table cellspacing="0" class="wp-list-taxonomy widefat fixed">
                                    <thead>
                                    <tr>
                                        <th style="" class="column-cb check-column" id="cb" scope="col">&nbsp;</th><th style="" class="" id="author" scope="col"><?php _e( "Archive", 'apto' ) ?></th><th style="" class="manage-column" id="categories" scope="col"><?php _e( "Total", 'apto' ) ?> <?php echo $this->current_post_type->labels->singular_name ?> <?php _e( "Archive Posts", 'apto' ) ?></th>    </tr>
                                    </thead>
                                    <tr valign="top" class="">
                                            <th class="check-column" scope="row"><input type="radio" onclick="apto_change_taxonomy(this, true)" value="_archive_" <?php if ($current_taxonomy == '_archive_') {echo 'checked="checked"';} ?> name="current_taxonomy">&nbsp;</th>
                                            <td class="categories column-categories"><?php _e( "Archive", 'apto' ) ?></td>
                                            <td class="categories column-categories"><?php
                                                $count_posts = wp_count_posts($post_type);
                                                echo $count_posts->publish;
                                                ?></td>
                                    </tr>
                                </tbody>
                                </table>

                                <table cellspacing="0" class="wp-list-taxonomy widefat fixed">
                                    <thead>
                                    <tr>
                                        <th style="" class="column-cb check-column" id="cb" scope="col">&nbsp;</th><th style="" class="" id="author" scope="col"><?php _e( "Taxonomy Title", 'apto' ) ?></th><th style="" class="manage-column" id="categories" scope="col"><?php _e( "Total", 'apto' ) ?> <?php echo $this->current_post_type->labels->singular_name ?> <?php _e( "Posts", 'apto' ) ?></th>    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                        <th style="" class="column-cb check-column" id="cb" scope="col">&nbsp;</th><th style="" class="" id="author" scope="col"><?php _e( "Taxonomy Title", 'apto' ) ?></th><th style="" class="manage-column" id="categories" scope="col"><?php _e( "Total", 'apto' ) ?> <?php echo $this->current_post_type->labels->singular_name ?> <?php _e( "Posts", 'apto' ) ?></th>    </tr>
                                    </tfoot>

                                    <tbody id="the-list">
                                    <?php

                                        $alternate = FALSE;

                                        foreach ($object_taxonomies as $key => $taxonomy)
                                            {
                                                $alternate = $alternate === TRUE ? FALSE :TRUE;
                                                $taxonomy_info = get_taxonomy($taxonomy);

                                                $taxonomy_terms = get_terms($taxonomy);

                                                $taxonomy_terms_ids = array();
                                                foreach ($taxonomy_terms as $taxonomy_term)
                                                    $taxonomy_terms_ids[] = $taxonomy_term->term_id;

                                                if (count($taxonomy_terms_ids) > 0)
                                                    {
                                                        $term_ids = array_map('intval', $taxonomy_terms_ids );

                                                        $term_ids = "'" . implode( "', '", $term_ids ) . "'";

                                                        $query = "SELECT COUNT(DISTINCT tr.object_id) as count FROM $wpdb->term_relationships AS tr
                                                                        INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                                                                        INNER JOIN $wpdb->posts as posts ON tr.object_id = posts.ID
                                                                        WHERE tt.taxonomy IN ('$taxonomy') AND tt.term_id IN ($term_ids) AND  posts.post_type = '$post_type' AND posts.post_status = 'publish' AND posts.post_parent = '0'
                                                                        ORDER BY tr.object_id" ;
                                                        $count = $wpdb->get_var($query);
                                                    }
                                                    else
                                                        {
                                                            $count = 0;
                                                        }

                                                ?>
                                                    <tr valign="top" class="<?php if ($alternate === TRUE) {echo 'alternate ';} ?>" id="taxonomy-<?php echo $taxonomy  ?>">
                                                            <th class="check-column" scope="row"><input type="radio" onclick="apto_change_taxonomy(this, false)" value="<?php echo $taxonomy ?>" <?php if ($current_taxonomy == $taxonomy) {echo 'checked="checked"';} ?> name="current_taxonomy">&nbsp;</th>
                                                            <td class="categories column-categories"><p><span><?php echo $taxonomy_info->label ?></span>

                                                                <?php
                                                                    if ($current_taxonomy == $taxonomy)
                                                                        {

                                                                        if ( is_object_in_taxonomy($post_type, $current_taxonomy) )
                                                                            {
                                                                                //check if there are any terms in that taxonomy before ouptut the dropdown
                                                                                $argv = array(
                                                                                                'hide_empty'    =>   0
                                                                                                );
                                                                                $terms = get_terms($current_taxonomy, $argv);

                                                                                $dropdown_options = array(
                                                                                                            'echo'              =>  0,
                                                                                                            'hide_empty'        =>  0,
                                                                                                            'hierarchical'      =>  1,
                                                                                                            'show_count'        =>  1,
                                                                                                            'orderby'           =>  'name',
                                                                                                            'taxonomy'          =>  $current_taxonomy,
                                                                                                            'selected'          =>  $cat,
                                                                                                            'class'             =>  'taxonomy_terms'
                                                                                                            );

                                                                                if (count($terms) > 0)
                                                                                    {
                                                                                        $select_html = wp_dropdown_categories($dropdown_options);
                                                                                        if(!empty($select_html))
                                                                                            {
                                                                                                $select_html = str_replace("<select ", "<select onchange=\"jQuery(this).closest('form').submit();\"", $select_html);
                                                                                                echo $select_html;
                                                                                            }

                                                                                        $found_action = TRUE;
                                                                                    }
                                                                            }


                                                                        } ?></p></td>
                                                            <td class="categories column-categories"><?php echo $count ?></td>
                                                    </tr>

                                                <?php
                                            }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            }

                    ?></form><?php

                    if (count($site_post_types_menus[$found_menu]) > 1 || count($object_taxonomies) > 0)
                        {
                        ?>
                        <br />
                        <?php
                        }
                    ?>

                    <form action="<?php echo admin_url('edit.php'); ?>" method="get" id="apto_form_order">
                         <?php
                            if ( !in_array( $post_type, array('post','attachment') ) )
                                {
                         ?>
                        <input id="apto_post_type" type="hidden" value="<?php echo $menu_root_post_type ?>" name="post_type" />
                        <?php } ?>
                        <input type="hidden" value="order-post-types-<?php echo $menu_root_post_type ?>" name="page" />


                        <h2 class="subtitle"><input type="radio" <?php if ($order_type == 'auto') {echo 'checked="checked"';} ?> name="order_type" value="auto" onclick="jQuery(this).closest('form').submit();"> Automatic Order</h2>
                        <?php if ($order_type == 'auto')
                                {
                                   ?>
                                    <div id="order-post-type">

                                        <div id="nav-menu-header">
                                            <div class="major-publishing-actions">

                                                <div class="alignright actions">
                                                    <p class="actions">
                                                        <input type="submit" value="Update" class="button-primary" name="update">
                                                    </p>
                                                </div>

                                                <div class="clear"></div>

                                            </div><!-- END .major-publishing-actions -->
                                        </div><!-- END #nav-menu-header -->


                                        <div id="post-body">

                                            <table class="form-table">
                                                <tbody>
                                                    <tr valign="top">
                                                        <th scope="row"><b>Order By</b></th>
                                                        <td>
                                                            <?php

                                                                $auto_order_by          = isset($options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order_by']) ? $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order_by'] : '_default_';
                                                                $auto_custom_field_name = isset($options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['custom_field_name']) ? $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['custom_field_name'] : '';
                                                            ?>
                                                            <input type="radio" <?php if ($auto_order_by == '_default_') {echo 'checked="checked"'; } ?> onchange="apto_autosort_orderby_field_change(this)" value="_default_" name="auto_order_by" />
                                                            <label for="blog-public">Default</label><br>

                                                            <input type="radio" <?php if ($auto_order_by == 'ID') {echo 'checked="checked"'; } ?> onchange="apto_autosort_orderby_field_change(this)" value="ID" name="auto_order_by" />
                                                            <label for="blog-public">Creation Time / ID</label><br>

                                                            <input type="radio" <?php if ($auto_order_by == 'post_title') {echo 'checked="checked"'; } ?> onchange="apto_autosort_orderby_field_change(this)" value="post_title" name="auto_order_by" />
                                                            <label for="blog-norobots">Name</label><br>


                                                            <input type="radio" <?php if ($auto_order_by == 'post_name') {echo 'checked="checked"'; } ?> onchange="apto_autosort_orderby_field_change(this)" value="post_name" name="auto_order_by" />
                                                            <label for="blog-norobots">Slug</label><br>

                                                            <input type="radio" <?php if ($auto_order_by == '_random_') {echo 'checked="checked"'; } ?> onchange="apto_autosort_orderby_field_change(this)" value="_random_" name="auto_order_by" />
                                                            <label for="blog-norobots">Random</label><br>

                                                            <input type="radio" <?php if ($auto_order_by == '_custom_field_') {echo 'checked="checked"'; } ?> onchange="apto_autosort_orderby_field_change(this)" value="_custom_field_" name="auto_order_by" />
                                                            <label for="blog-norobots">Custom Field</label><br>
                                                            <div id="apto_custom_field_area" <?php
                                                                if ($auto_order_by != '_custom_field_')
                                                                    echo 'style="display: none"';
                                                            ?>>
                                                                <label for="blog-norobots">Custom Field Name</label><br>
                                                                <input type="text" class="regular-text" value="<?php echo $auto_custom_field_name ?>" name="auto_custom_field_name">
                                                            </div>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table class="form-table">
                                                <tbody>
                                                    <tr valign="top">
                                                        <th scope="row"><b>Order</b></th>
                                                        <td>
                                                            <?php

                                                                $auto_order = isset($options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order']) ? $options['taxonomy_settings'][$post_type][$current_taxonomy][$cat]['order'] : 'DESC';

                                                            ?>

                                                            <input type="radio" <?php if ($auto_order == 'DESC') {echo 'checked="checked"'; } ?> value="DESC" name="auto_order" />
                                                            <label for="blog-public">Descending</label><br>

                                                            <input type="radio" <?php if ($auto_order == 'ASC') {echo 'checked="checked"'; } ?> value="ASC" name="auto_order" />
                                                            <label for="blog-public">Ascending</label><br>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <br />
                                            <div class="clear"></div>
                                        </div>

                                        <div id="nav-menu-footer">
                                            <div class="major-publishing-actions">
                                                    <div class="alignright actions">
                                                        <input type="submit" value="Update" class="button-primary" name="update">
                                                    </div>

                                                    <div class="clear"></div>

                                            </div><!-- END .major-publishing-actions -->
                                        </div><!-- END #nav-menu-header -->

                                    </div>

                                    <?php
                                }
                        ?>

                        <h2 class="subtitle"><input type="radio" <?php if ($order_type == 'manual') {echo 'checked="checked"';} ?> name="order_type" value="manual" onclick="jQuery(this).closest('form').submit();"> Manual Order</h2>
                        <?php if ($order_type == 'manual')
                                {
                                   ?>

                        <div id="order-post-type">

                            <div id="nav-menu-header">
                                <div class="major-publishing-actions">

                                        <div class="alignleft actions">
                                        <?php

                                            $found_action = FALSE;

                                            if ($is_hierarchical === TRUE && $current_taxonomy == '_archive_')
                                                {
                                                }
                                                else
                                                {

                                                    $arc_query = $wpdb->prepare("SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE post_type = %s ORDER BY post_date DESC", array($post_type));

                                                    $arc_result = $wpdb->get_results( $arc_query );

                                                    $month_count = count($arc_result);

                                                    if ( $month_count && !( 1 == $month_count && 0 == $arc_result[0]->mmonth ) )
                                                    {

                                                    ?>
                                                    <select name='m'>

                                                    <option<?php selected( $m, 0 ); ?> value='0'><?php _e('Show all dates'); ?></option>
                                                    <option<?php selected( $m, 'today' ); ?> value='today'><?php _e('Today'); ?></option>
                                                    <option<?php selected( $m, 'yesterday' ); ?> value='yesterday'><?php _e('Yesterday'); ?></option>
                                                    <option<?php selected( $m, 'last_week' ); ?> value='last_week'><?php _e('Last Week'); ?></option>
                                                    <?php
                                                    foreach ($arc_result as $arc_row) {
                                                        if ( $arc_row->yyear == 0 )
                                                            continue;
                                                        $arc_row->mmonth = zeroise( $arc_row->mmonth, 2 );

                                                        if ( $arc_row->yyear . $arc_row->mmonth == $m )
                                                            $default = ' selected="selected"';
                                                        else
                                                            $default = '';

                                                        echo "<option$default value='" . esc_attr("$arc_row->yyear$arc_row->mmonth") . "'>";
                                                        echo $wp_locale->get_month($arc_row->mmonth) . " $arc_row->yyear";
                                                        echo "</option>\n";
                                                    }
                                                    ?>
                                                    </select>
                                                    <?php
                                                    }

                                                    $found_action = TRUE;
                                                }

                                            if($found_action === TRUE)
                                                {
                                        ?>
                                            <input type="submit" class="button-secondary" value="Filter" id="post-query-submit">
                                            <?php } ?>
                                        </div>

                                        <div class="alignright actions">
                                            <p class="actions">

                                                <a class="button-secondary alignleft toggle_thumbnails" title="Cancel" href="javascript:;" onclick="toggle_thumbnails(); return false;"><?php _e( "Toggle Thumbnails", 'apto' ) ?></a>

                                                <?php if ($is_hierarchical === FALSE)
                                                    {
                                                        ?>
                                                <input type="text" value="<?php if (isset($_GET['s'])) {echo $_GET['s'];} ?>" name="s" id="post-search-input" class="fl">
                                                <input type="submit" class="button fl" value="Search">
                                                <?php  } ?>
                                                <span class="img_spacer"><img alt="" src="<?php echo CPTURL ?>/images/wpspin_light.gif" class="waiting pto_ajax_loading" style="display: none;"></span>
                                                <a href="javascript:;" class="save-order button-primary"><?php _e( "Update", 'apto' ) ?></a>
                                            </p>
                                        </div>

                                        <div class="clear"></div>

                                </div><!-- END .major-publishing-actions -->
                            </div><!-- END #nav-menu-header -->


                            <div id="post-body">
                                <script type="text/javascript">
                                    var term_id     = '<?php echo $cat ?>';
                                    var post_type   = '<?php echo $post_type ?>';
                                    var taxonomy    = '<?php echo $current_taxonomy ?>';
                                    var lang        = '<?php echo apto_get_blog_language(); ?>';
                                </script>

                                <ul id="sortable">
                                    <?php
                                        $query_string = 's='. $s .'&m='.$m.'&cat='.$cat.'&hide_empty=0&title_li=&post_type='.$this->current_post_type->name;
                                        if ($current_taxonomy != '_archive_')
                                            $query_string .= '&taxonomy='.$current_taxonomy;
                                            else
                                            $query_string .= '&taxonomy=';

                                        if ($is_hierarchical === TRUE && $current_taxonomy == '_archive_')
                                            {
                                            }
                                            else
                                            $query_string .= '&depth=-1';

                                        $this->listPostType($query_string);
                                    ?>
                                </ul>

                                <div class="clear"></div>
                            </div>

                            <div id="nav-menu-footer">
                                <div class="major-publishing-actions">

                                        <div class="alignright actions">
                                            <p class="submit">
                                                <img alt="" src="<?php echo CPTURL ?>/images/wpspin_light.gif" class="waiting pto_ajax_loading" style="display: none;">
                                                <a href="javascript:;" class="save-order button-primary"><?php _e( "Update", 'apto' ) ?></a>
                                            </p>
                                        </div>

                                        <div class="clear"></div>

                                </div><!-- END .major-publishing-actions -->
                            </div><!-- END #nav-menu-header -->

                        </div>


                        <br />
                        <a id="order_Reset" class="button-primary" href="javascript: void(0)" onclick="confirmSubmit()"><?php _e( "Reset Order", 'apto' ) ?></a>

                        <script type="text/javascript">

                            function confirmSubmit()
                                {
                                    var agree=confirm("<?php _e( "Are you sure you want to reset the order??", 'apto' ) ?>");
                                    if (agree)
                                        {
                                            jQuery('#apto_form_order_reset').submit();
                                        }
                                        else
                                        {
                                            return false ;
                                        }
                                }

                            jQuery(document).ready(function() {

                                //jQuery( "#sortable" ).sortable();
                                jQuery('ul#sortable').nestedSortable({
                                        handle:             'div',
                                        tabSize:            30,
                                        listType:           'ul',
                                        items:              'li',
                                        toleranceElement:   '> div',
                                        placeholder:        'ui-sortable-placeholder',
                                        disableNesting:     'no-nesting'
                                        <?php

                                            if ($is_hierarchical === TRUE && $current_taxonomy == '_archive_')
                                                {
                                                }
                                                else
                                                {
                                                    ?>,disableNesting      :true<?php
                                                }
                                        ?>
                                    });



                                jQuery(".save-order").bind( "click", function() {
                                    jQuery(this).parent().find('img').show();

                                     var queryString = {
                                                            action:         'update-custom-type-order',
                                                            order:          jQuery("#sortable").nestedSortable("serialize"),
                                                            term_id:        term_id,
                                                            post_type:      post_type,
                                                            taxonomy:       taxonomy,
                                                            lang:           lang
                                                                };
                                    //send the data through ajax
                                    jQuery.ajax({
                                      type: 'POST',
                                      url: ajaxurl,
                                      data: queryString,
                                      cache: false,
                                      dataType: "html",
                                      success: function(data){
                                                        jQuery("#ajax-response").html('<div class="message updated fade"><p><?php _e( "Items Order Updated", 'apto' ) ?></p></div>');
                                                        jQuery("#ajax-response div").delay(3000).hide("slow");
                                                        jQuery('img.pto_ajax_loading').hide();

                                      },
                                      error: function(html){

                                          }
                                    });
                                });
                            });
                        </script>
                        <?php } ?>


                     </form>


                     <form action="" method="post" id="apto_form_order_reset">
                        <input type="hidden" name="order_reset" value="1" />
                        <input type="hidden" name="selected_post_type" value="<?php echo $post_type ?>" />
                        <input type="hidden" name="cat" value="<?php echo $cat ?>" />
                        <input type="hidden" name="current_taxonomy" value="<?php echo $current_taxonomy ?>" />
                    </form>

                </div>
                <?php
            }

        function listPostType($args = '')
            {
                $defaults = array(
                    'depth' => 0, 'show_date' => '',
                    'date_format' => get_option('date_format'),
                    'child_of' => 0,
                    'exclude' => '',
                    'title_li' => __('Pages'),
                    'echo' => 1,
                    'authors' => '',
                    'sort_column' => 'menu_order',
                    'link_before' => '',
                    'link_after' => '',
                    'walker' => ''
                );

                $r = wp_parse_args( $args, $defaults );
                extract( $r, EXTR_SKIP );

                $output = '';

                // Query pages.
                $args = array(
                            'post_type'         =>  $post_type,
                            'posts_per_page'    => -1,
                            'orderby'           => 'menu_order',
                            'order'             => 'ASC'

                );

                $_filter_posts_where_active = FALSE;

                //filter a taxonomy term
                $tax_query = array();
                if ($taxonomy != '')
                    {
                        global $wp_version;
                        //wp under 3.1 fix
                        if(version_compare( $wp_version, strval('3.1') , '<' ) )
                            {
                                if ($cat > 0)
                                    {
                                        $update_tax_name = $taxonomy;
                                        $term_data = get_term_by('id', $cat, $taxonomy);

                                        if ($taxonomy == 'category')
                                            {
                                                $args['cat'] = $term_data->term_id;
                                            }
                                            else
                                                {
                                                    $args[$taxonomy] = $term_data->name;
                                                }
                                    }
                            }
                            else
                            {
                                if ($cat > 0)
                                    {
                                        $tax_query = array(
                                                                    array(
                                                                            'taxonomy'  => $taxonomy,
                                                                            'field'     => 'id',
                                                                            'terms'     => $cat
                                                                                    )
                                                                    );

                                    }
                                    else
                                    {
                                        //retrieve all terms for this taxonomy
                                        $taxonomy_terms = get_terms($taxonomy);
                                        $terms_array = array();

                                        foreach ($taxonomy_terms as $taxonomy_term)
                                            $terms_array[] = $taxonomy_term->term_id;

                                        if(count($terms_array) < 1)
                                            $terms_array[] = -1;

                                        $tax_query = array(
                                                                    array(
                                                                            'taxonomy'  => $taxonomy,
                                                                            'field'     => 'id',
                                                                            'terms'     => $terms_array
                                                                                    )
                                                                    );
                                    }
                            }

                    }

                $args['tax_query'] = $tax_query;

                //filter a date
                if ($m != '0' )
                    {
                        if ($m == 'last_week')
                            {
                                $_filter_posts_where_active = TRUE;

                                global $_apto_filter_posts_where_interval_after_time, $_apto_filter_posts_where_interval_before_time;

                                $_apto_filter_posts_where_interval_after_time   = strtotime('-7 days');
                                $_apto_filter_posts_where_interval_before_time  = strtotime('+1 day');

                                add_filter( 'posts_where', 'apto_filter_posts_where_interval' );
                            }
                            else if ($m == 'today')
                            {
                                $time = current_time('timestamp');
                                $year               = date("Y", $time);
                                $month              = date("m", $time);
                                $day                = date("d", $time);
                                $args['year']       = $year;
                                $args['monthnum']   = $month;
                                $args['day']        = $day;
                            }
                            else if ($m == 'yesterday')
                            {
                                $time = current_time('timestamp');
                                $time = $time - 86400;
                                $year               = date("Y", $time);
                                $month              = date("m", $time);
                                $day                = date("d", $time);
                                $args['year']       = $year;
                                $args['monthnum']   = $month;
                                $args['day']        = $day;
                            }
                            else
                            {
                                $year   = substr($m, 0, 4);
                                $month  = substr($m, 4, 2);
                                $args['year'] = $year;
                                $args['monthnum'] = $month;
                            }
                    }

                //search filter
                if ($s != '')
                    {
                        $args['s'] = $s;
                    }

                //Post status for atatchments
                if ($post_type == 'attachment')
                    $args['post_status'] = 'any';

                $the_query = new WP_Query($args);

                //remove filters if aplied
                if ($_filter_posts_where_active === TRUE)
                    remove_filter( 'posts_where', 'apto_filter_posts_where_interval' );

                $post_types = $the_query->posts;

                if ($post_type == 'attachment')
                foreach ($post_types as $key => $post)
                    $post->post_parent = null;

                if ( !empty($post_types) )
                    {
                        $output = $this->walkTree($post_types, $r['depth'], $r);
                    }

                if ( $r['echo'] )
                    echo $output;
                else
                    return $output;
            }

        function walkTree($post_types, $depth, $r)
            {
                if ( empty($r['walker']) )
                    {
                        //include the custom walker
                        include(CPTPATH . '/include/post_types_walker.php');
                        $walker = new Post_Types_Order_Walker;
                    }
                else
                    $walker = $r['walker'];

                $args = array($post_types, $depth, $r);
                return call_user_func_array(array(&$walker, 'walk'), $args);
            }
    }





?>
