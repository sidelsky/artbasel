<?php
/**
 * Template name: FAQs
 */

include("header.php");
?>

<section class="u-section">
    <div class="u-l-container--center">   
        <div class="u-l-container u-l-horizontal-padding">

        	<?php
            $args = [
                'post_type' => 'faqs',
                'posts_per_page' => -1,
                'orderby' => NULL,
                'order' => NULL
            ];
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();
            ?>
                <div class="c-faqs">
                    <a class="c-faqs__title accordion">
                        <?php
                        the_title();
                        echo '<span class="chevron"></span>'; ?>
                    </a>
                    <div class="c-faqs__details">
                        <div class="c-faqs__content">
                            <?php the_content(); ?>
                        </div>     
                    </div>     
                </div>

            <?php
			endwhile; 
			?>

        </div>
    </div>
</section>

<?php include("footer.php"); ?>