<section class="u-section">
	<div class="u-l-container--center">
		<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
			<div class="c-works">
				<div class="c-works__list">

            <?php $postContent = $data['works_content']; ?>

            <?php foreach( $postContent  as $content ) :
                  $key = get_the_id($content->ID);
                  $title = get_the_title($content->ID);
                  $link = get_the_permalink($content->ID);
                  $subPostTitle = get_field('sub_post_title', $content->ID);
                  $surname = get_field('surname', $content->ID);
                  $fullName = get_field('full_name', $content->ID);
                  $image = get_the_post_thumbnail_url($content->ID);
                  $date = get_field('date', $content->ID);
                  $description = get_field('description', $content->ID);
                  $medium = get_field('medium', $content->ID);
                  $mediumText = get_field('medium_free_text', $content->ID);
                  $decade = get_field('decade', $content->ID);
                  $dimensions = get_field('dimensions', $content->ID);
                  $price = get_field('price', $content->ID);
                  $priceRange = get_field('price_range', $content->ID);
                  $sold = get_field('sold', $content->ID);
                  $ids = get_field('code_id', $content->ID);
                  $creditLine = get_field('credit_line', $content->ID);
                  $hidePurchaseButton = get_field('hide_purchase_button', $content->ID);
               ?>
            <article class="c-works__card"">
						<figure class="c-works__figure">
							<a href="<?= $link ?>">
								<img src="<?= $image ?>" alt="<?= $title ?>" class="c-works__image">
							</a>
						</figure> 
						<a href="<?= $link; ?>">
							<h2 class="c-works__title"><?= $title; ?></h2>
						</a>
						<div class="c-works__name"><?= $fullName ?></div>
						<div class="c-works__date"><?= $date ?></div>
						<div class="c-works__medium"><?= $mediumText ?></div>
						
						<?php if($sold === 'available') : ?>
							<div class="c-works__price"><span><?= $price ?></span></div>
						<?php endif; ?>

						
						<?php if( $sold == 'sold' ) {
							$availabilityMarker = 'c-sale-marker--sold';
							$availabilityTitle = 'Sold';
						} elseif( $sold == 'hold' ) {
							$availabilityMarker = 'c-sale-marker--hold';
							$availabilityTitle = 'Hold';
						} else {
							$availabilityMarker = 'c-sale-marker--available';
							$availabilityTitle = 'Available';
						} ?>

						<?php if( $sold == !NULL ) : ?>
							<div class="c-works__availability">
								<span class="c-sale-marker <?= $availabilityMarker ?>"></span><span><?= $availabilityTitle ?></span>
							</div>
						<?php endif; ?>
					</article>
               <?php endforeach; ?>

				</div>
			</div>
		</div>
	</div>
</section>