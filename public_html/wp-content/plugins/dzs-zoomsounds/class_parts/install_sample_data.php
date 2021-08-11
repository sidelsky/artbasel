<?php

$arr_posts = array();


$post_type = 'dzsrst_items';
$taxonomy = 'dzsrst_items_cat';

$arr_cats = array();

$args = array('cat_name' => 'Feature','category_description' => '=','category_nicename' => 'feature','taxonomy' => $taxonomy);
$sample_cat_id = wp_insert_category($args);
array_push($arr_cats,$sample_cat_id);

$args = array('cat_name' => 'Simple','category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ','category_nicename' => 'simple','taxonomy' => $taxonomy);
$sample_cat_id = wp_insert_category($args);
array_push($arr_cats,$sample_cat_id);




// -- start adding posts

$args = array(
'post_title' => 'Salat au Faiche',
'post_content' => 'Sample post.',
'post_status' => 'publish',
'post_author' => 1,
'post_type' => $post_type,
);

$sample_post_id = wp_insert_post($args);
wp_set_post_terms($sample_post_id,$arr_cats[0],$taxonomy);
update_post_meta($sample_post_id,'dzsrst_meta_item_thumb','https://i.imgur.com/TQMA86e.jpg');
update_post_meta($sample_post_id,'dzsrst_meta_item_price','$19.99');

array_push($arr_posts, $sample_post_id);


$args = array(
'post_title' => 'Steak a la Bon',
'post_content' => 'Sample post.',
'post_status' => 'publish',
'post_author' => 1,
'post_type' => $post_type,
);

$sample_post_id = wp_insert_post($args);
wp_set_post_terms($sample_post_id,$arr_cats[0],$taxonomy);
update_post_meta($sample_post_id,'dzsrst_meta_item_thumb','https://i.imgur.com/JnKet6U.jpg');
update_post_meta($sample_post_id,'dzsrst_meta_item_price','$12.99');

array_push($arr_posts, $sample_post_id);






$args = array(
    'post_title' => 'Default 1',
    'post_content' => 'Sample post.',
    'post_status' => 'publish',
    'post_author' => 1,
    'post_type' => $post_type,
);

$sample_post_id = wp_insert_post($args);
wp_set_post_terms($sample_post_id,$arr_cats[1],$taxonomy);
update_post_meta($sample_post_id,'dzsrst_meta_item_thumb','');
update_post_meta($sample_post_id,'dzsrst_meta_item_ingredients','Pasta di pomodor, mozarella, prosciuto crudo, mushrooms');
update_post_meta($sample_post_id,'dzsrst_meta_item_price','$12.99');

array_push($arr_posts, $sample_post_id);

$args = array(
    'post_title' => 'Default 2',
    'post_content' => 'Sample post.',
    'post_status' => 'publish',
    'post_author' => 1,
    'post_type' => $post_type,
);

$sample_post_id = wp_insert_post($args);
wp_set_post_terms($sample_post_id,$arr_cats[1],$taxonomy);
update_post_meta($sample_post_id,'dzsrst_meta_item_thumb','');
update_post_meta($sample_post_id,'dzsrst_meta_item_ingredients','Pasta di pomodor, mozarella, prosciuto crudo, mushrooms');
update_post_meta($sample_post_id,'dzsrst_meta_item_price','$12.99');

array_push($arr_posts, $sample_post_id);

$args = array(
    'post_title' => 'Default 3',
    'post_content' => 'Sample post.',
    'post_status' => 'publish',
    'post_author' => 1,
    'post_type' => $post_type,
);

$sample_post_id = wp_insert_post($args);
wp_set_post_terms($sample_post_id,$arr_cats[1],$taxonomy);
update_post_meta($sample_post_id,'dzsrst_meta_item_thumb','');
update_post_meta($sample_post_id,'dzsrst_meta_item_ingredients','Pasta di pomodor, mozarella, prosciuto crudo, mushrooms');
update_post_meta($sample_post_id,'dzsrst_meta_item_price','$12.99');

array_push($arr_posts, $sample_post_id);

$args = array(
    'post_title' => 'Default 4',
    'post_content' => 'Sample post.',
    'post_status' => 'publish',
    'post_author' => 1,
    'post_type' => $post_type,
);

$sample_post_id = wp_insert_post($args);
wp_set_post_terms($sample_post_id,$arr_cats[1],$taxonomy);
update_post_meta($sample_post_id,'dzsrst_meta_item_thumb','');
update_post_meta($sample_post_id,'dzsrst_meta_item_ingredients','Pasta di pomodor, mozarella, prosciuto crudo, mushrooms');
update_post_meta($sample_post_id,'dzsrst_meta_item_price','$12.99');

array_push($arr_posts, $sample_post_id);






echo $arr_cats[0].','.$arr_cats[1];

$demo_data = array('cats' => $arr_cats,'posts' => $arr_posts);

update_option(DZSAP_DBNAME_SAMPLEDATA,$demo_data);
