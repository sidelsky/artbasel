<?php


    /**
    * 
    * Post Types Order Walker Class
    * 
    */
    class Post_Types_Order_Walker extends Walker 
        {

            var $db_fields = array ('parent' => 'post_parent', 'id' => 'ID');


            function start_lvl(&$output, $depth, $args) 
                {
                    extract($args, EXTR_SKIP);
                      
                    $indent = str_repeat("\t", $depth);
                    $output .= "\n$indent<ul class='children'>\n";
                }


            function end_lvl(&$output, $depth, $args) 
                {
                    extract($args, EXTR_SKIP);
                           
                    $indent = str_repeat("\t", $depth);
                    $output .= "$indent</ul>\n";
                }


            function start_el(&$output, $post_data, $depth, $args) 
                {
                    if ( $depth )
                        $indent = str_repeat("\t", $depth);
                    else
                        $indent = '';

                    extract($args, EXTR_SKIP);

                    $options = get_option('cpto_options');
                    
                    //check post thumbnail
                    if (function_exists('get_post_thumbnail_id'))
                            {
                                $image_id = get_post_thumbnail_id( $post_data->ID , 'post-thumbnail' );
                            }
                        else
                            {
                                $image_id = NULL;    
                            }
                    if ($image_id > 0)
                        {
                            $image = wp_get_attachment_image_src( $image_id , array(64,64)); 
                            $image_html =  '<img src="'. $image[0] .'" width="64" alt="" />';
                        }
                        else
                            {
                                $image_html =  '<img src="'. CPTURL .'/images/nt.gif" width="64" alt="" />';    
                            }
                    
                    
                    //allow the thumbnail image to be changed through a filter
                    $image_html = apply_filters( 'apto_reorder_item_thumbnail', $image_html, $post_data->ID );
                    
                    $noNestingClass = '';
                    $post_type_data = get_post_type_object($post_data->post_type);
                    if ($post_type_data->hierarchical !== TRUE)
                        $noNestingClass = ' no-nesting';
                    
                    $output .= $indent . '<li class="post_type_li'.$noNestingClass.'" id="item_'.$post_data->ID.'"><div class="item"><div class="post_type_thumbnail"';
                    
                    if (isset($options['always_show_thumbnails']) && $options['always_show_thumbnails'] == "1")
                        $output .= ' style="display: block"';
                        
                    $output .= '>'. $image_html .'</div><span>'.apply_filters( 'the_title', $post_data->post_title, $post_data->ID );
                    
                    $additiona_details = ' ('.$post_data->ID.')';
                    $additiona_details = apply_filters('apto_reorder_item_additional_details', $additiona_details, $post_data);
                    $output .= $additiona_details;
                    
                    if ($post_data->post_status != 'publish')
                        $output .= ' <span>'.$post_data->post_status.'</span>';
                     
                    $output .= '</span><span class="edit"><a href="'. get_bloginfo('wpurl') .'/wp-admin/post.php?post='.$post_data->ID.'&action=edit">Edit</a></span></div>';
                }


            function end_el(&$output, $post_data, $depth) 
                {
                    $output .= "</li>\n";
                }

        }





?>