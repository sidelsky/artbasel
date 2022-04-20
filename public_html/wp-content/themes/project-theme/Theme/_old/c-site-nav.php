<nav class="c-site-nav">
    <?php
    
    if( isset($menu_args) && is_array($menu_args) ){

        $args = $menu_args;
            } else {
                $args = [];
            }

        $defaults = [
            'menu' => NULL, 
            'class' => NULL, 
        ];

        $args = wp_parse_args( $args, $defaults );

        wp_nav_menu([
            'menu' => $args['menu'],
            'container' => '',
            'echo' => true,
            'items_wrap' => '<ul class="c-site-nav__menu">%3$s</ul>'
        ]);
    ?>
</nav>
