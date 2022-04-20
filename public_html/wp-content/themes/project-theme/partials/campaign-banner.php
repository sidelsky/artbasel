<?php if( is_front_page() ) : ?>
    <div class="c-campaign-banner">
        <section class="u-section">
            <div class="u-l-container u-l-container--large" data-in-viewport>
                <div class="u-l-container u-l-container--row u-l-horizontal-padding">
                <?php
                    $args = [
                        'post_type' => 'campaign',
                        'posts_per_page' => -1,
                    ];
                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post();
                    ?>
                    <div class="c-campaign-banner__column">
                        <p><?php the_title(); ?></p>
                    </div>
                    <div class="c-campaign-banner__column">
                        <a href="<?php the_permalink(); ?>" class="u-button u-button--small">Join</a>
                    </div>
                    <?php
                    endwhile; 
                    wp_reset_query();
                    ?>
                </div>
            </div>
        </section>
    </div>
<?php endif; ?>