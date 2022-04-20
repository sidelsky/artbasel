<section class="u-section u-bg-color--bw-white">
    <div class="u-l-container u-l-container--shallow" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding">
            <div class="c-additional-text">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; else : ?>
                    <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>
