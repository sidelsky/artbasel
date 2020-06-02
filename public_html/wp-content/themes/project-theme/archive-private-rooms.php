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
               'orderby'=> 'post_date',
               'order' => 'DESC'
            );
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();
            
            $rows = get_field('current_viewing_rooms');
            $thumbnail = $rows[0]['current_viewing_room_image']['sizes']['medium'];
            $alt = $rows[0]['current_viewing_room_image']['alt'];
            $fieldTitle = $rows[0]['current_viewing_room_title'];
            $postTitle = get_the_title();
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

         <?php if( $showContent ) : ?>
            <div class="c-online-exhibitions__block--navigation" role="img" aria-label="<?= esc_attr( $alt ); ?>" style="background-image: url(<?= $thumbnail ?>)">
               <div class="canvas">
                  <div class="c-online-exhibitions__content">
                     <h3 class="c-online-exhibitions__title c-online-exhibitions--alt-font"><?= $fieldTitle ? $fieldTitle : $postTitle ?></h3>
                        <span class="c-works__href-wrap c-online-exhibitions__href">
                           <a href="<?= $url ?>" class="c-works__href">Explore now</a>
                           <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use></svg>
                        </span>
                  </div>
               </div>
            </div>
         <?php endif; ?>
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
