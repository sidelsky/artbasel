<?php
/**
 * Template Name: Thanks
 */
    include("header.php");
?>

<section class="u-section ">
    <div class="u-l-container--center" data-in-viewport>
        <div class="u-l-container--center u-l-horizontal-padding c-text-align-centre">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>    
            <h2 class="c-site-headings--h2--email c-text-align-centre">
                <?php the_content(); ?>
                <br>
                <script>
                    setTimeout(function(){
                        window.location.href = 'https://www.hauserwirth.com';
                    }, 5000);
                </script>
                <div style="font-size: 19px;">Redirecting to 'hauserwirth.com' in 5 seconds...</div>
            </h2>
            <?php endwhile; ?>
        <?php endif; ?>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>