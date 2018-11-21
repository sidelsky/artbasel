<?php

use App\Helper\Render;

$render = new Render;

include("header.php"); ?>

<?php 
    
    if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    
    $title = get_the_title();
    $image = get_the_post_thumbnail_url();
    $date = get_field('date');
    $medium = get_field('medium');
    $dimensions = get_field('dimensions');
    $price = get_field('price');
    $learn_more = get_the_permalink();
    ?>

    <section class="u-section light-grey ">
        <div class="u-l-container--center" data-in-viewport>
            <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
            
                <article class="c-work-single">
                    <div class="c-work-single__column">
                        <figure class="c-work-single__figure"><img src="<?= $image ?>" alt="<?= $title ?>" class="c-work-single__image materialboxed"></figure>
                    </div>
                    <div class="c-work-single__column">
                        <h2 class="c-works__title"><?= $title ?></h2>
                        <div class="c-works__date"><?= $date ?></div>
                        <div class="c-works__medium"><?= $medium ?></div>
                        <div class="c-works__dimensions"><?= $dimensions ?></div>
                        <div class="c-works__price">$<?= $price ?></div>
                        <span class="c-works__href-wrap"><a href="mailto:errol@squie.com?subject=Inquire to purchase - <?= $title ?>&body=Hello, I'd to inquire about '<?= $title ?>'..." class="c-works__href">Inquire to purchase</a><svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use></svg></span>

                        <a href="/works" class="c-button">View all available works</a>
                    </div>
                </article>

            </div>
        </div>
    </section>

<?php endwhile; endif; ?>


<?php include("footer.php"); ?>


