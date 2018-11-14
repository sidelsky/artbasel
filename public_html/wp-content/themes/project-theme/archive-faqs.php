
<?php
/**
 * Archive FAQs
 */

include("header.php");
?>

<section class="u-section">
    <div class="u-l-container--center">   
        <div class="u-l-container u-l-container--row u-l-vertical-padding">   
    
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php
            the_title(); 
            echo '<span class="chevron"></span>'
            ?>
        <?php endwhile; endif; ?>

        </div>
    </div>
</section>

<?php include("footer.php"); ?>