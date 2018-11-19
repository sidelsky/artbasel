<?php

    use App\Helper\Render;

    $render = new Render;

    include("header.php");

?>

<section class="u-section ">
    <div class="u-l-container--center" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
            
            <!-- START: Meta Column -->
            <div class="c-meta">
                <div class="c-meta__data">
                    <form action="">
                        <div class="c-meta__section">
                            <h3 class="c-meta__title">Sort By</h3>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="artist-a-z"><span>Artist A-Z</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="price-low-to-high"><span>Price: low to high</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="price-high-to-low"><span>Price: high to low</span></label></p>
                        </div>

                        <div class="c-meta__section">
                            <h3 class="c-meta__title">By Medium</h3>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Painting"><span>Painting</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Sculpture"><span>Sculpture</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Work on Paper"><span>Work on Paper</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Mixed Media"><span>Mixed Media</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Collage"><span>Collage</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Installation"><span>Installation</span></label></p>
                        </div>

                        <div class="c-meta__section">
                            <h3 class="c-meta__title">By Price</h3>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="Under $50k"><span>Under $50k</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$50 – 100k"><span>$50 – 100k</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$100 – 150k"><span>$100 – 150k</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$150 – 200k"><span>$150 – 200k</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$200 – 250k"><span>$200 – 250k</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$250 – 500k"><span>$250 – 500k</span></label></p>
                            <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="Over $500k"><span>Over $500k</span></label></p>
                        </div>

                        <div class="c-meta__section">
                        <h3 class="c-meta__title">By Decade</h3>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="Pre-1900"><span>Pre-1900</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1900 – 1945"><span>1900 – 1945</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1945 – 1960"><span>1945 – 1960</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1960 – 1970"><span>1960 – 1970</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1970 – 1980"><span>1970 – 1980</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1980 – 1990"><span>1980 – 1990</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1990 – 2000"><span>1990 – 2000</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="2000 – 2010"><span>2000 – 2010</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="2010 – Present"><span>2010 – Present</span></label></p>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END: Meta Column -->

            <!-- START: Works Column -->
            <div class="c-works">
                <?php

                    $loop = new WP_Query( 
                        [
                            'post_type' => 'works',
                            'posts_per_page' => -1,
                            'orderby' => 'post_date',
                            'order' => 'ASC'
                        ]
                     );
                    while ( $loop->have_posts() ) : $loop->the_post();

                    $title = get_the_title();
                    $image = get_the_post_thumbnail_url();
                    $date = get_field('date');
                    $medium = get_field('medium');
                    $dimensions = get_field('dimensions');
                    $price = get_field('price');
                    $learn_more = get_the_permalink();
                ?>
                
                <!-- START: Work card -->
                <article class="c-works__card">
                    <figure class="c-works__figure"><a href="<?= $learn_more ?>"><img src="<?= $image ?>" alt="<?= $title ?>" class="c-works__image"></a></figure>
                    <h2 class="c-works__title"><?= $title ?></h2>
                    <div class="c-works__date"><?= $date ?></div>
                    <div class="c-works__medium"><?= $medium ?></div>
                    <div class="c-works__dimensions"><?= $dimensions ?></div>
                    <div class="c-works__price">$<?= $price ?></div>
                    <span class="c-works__href-wrap"><a href="<?= $learn_more ?>" class="c-works__href">Learn more</a><svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use></svg></span>
                    <span class="c-works__href-wrap"><a href="mailto:errol@squie.com?subject=Inquire to purchase - <?= $title ?>&body=Hello, I'd to inquire about '<?= $title ?>'..." class="c-works__href">Inquire to purchase</a><svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use></svg></span>
                </article>
                <!-- END: Work card -->
                    
                <?php endwhile; ?>
            </div>
            <!-- END: Works Column -->

        </div>
    </div>
</section>



<?php include("footer.php"); ?>