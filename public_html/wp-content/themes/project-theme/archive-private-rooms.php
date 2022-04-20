<?php
/**
 * Header
 */
include("header.php");
require('classes/Users.php');

//$users = new Users();
$currentUser = wp_get_current_user();
$currentUserId = $currentUser->ID;

if( $currentUserId ) {
      $currentUser = true;
} else {
      $currentUser = false;
}

?>

<?php if( $currentUserId) : ?>
   <section class="u-l-horizontal-padding--small u-l-horizontal-padding--top-small background-color--dark c-online-exhibitions--border-bottom">
      <div class="u-l-container c-online-exhibitions c-online-exhibitions--border-bottom u-l-horizontal-margin--bottom-small">
         <h1 class="c-site-headings c-site-headings--h1--private-sales">About Private Sales</h1>
      </div>

      <div class="u-l-container c-online-exhibitions">  
      <?php
            //$showContent = $users->getUsers();
            $args = array(
               'post_type' => 'private-rooms',
               'posts_per_page' => -1,
               'orderby' => 'menu_order',
               'order' => 'ASC'
            );
            $loop = new WP_Query( $args );

            $onlineExhibitionsCardData = [
               'isCarousel' => false,
               'isFooterNavigation' => true,
               'altFont' => true,
            ];
                  
            while ( $loop->have_posts() ) : $loop->the_post();
            
            $rows = get_field('current_viewing_rooms');
            $thumbnail = $rows[0]['current_viewing_room_image']['sizes']['medium'];
            $alt = $rows[0]['current_viewing_room_image']['alt'];
            $title = $rows[0]['current_viewing_room_title'];
            //$postTitle = get_the_title();
            $url = get_the_permalink();
            $users = get_field('users');

            foreach ( $users as $user ) {
               if( in_array($currentUserId, $user) ) {
                  $showContent = true;
               } else {
                  $showContent = false;
               }
            }
         ?>

         <?php if( $showContent ) {
            include("partials/online-exhibitions-card.php");
         } ?>
         
      <?php endwhile; ?>
      </div>

   </section>
<?php else : ?>
   <script>
      // setTimeout(function(){ 
      // }, 2000);
      window.location.replace("/wordpress/wp-admin");
   </script>
   <?php /* redirect('http://example.com/', true); */?>
   <?php include("partials/non-page-content.php"); ?>
 
<?php endif; ?>

<?php
/**
 * Footer
 */
include("footer.php"); 
?>
