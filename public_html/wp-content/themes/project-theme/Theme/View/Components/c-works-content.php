<?php
$showWorksFilters = $data['show_works_filters']; 
$worksFilterTitle = $data['works_filter_title']; 

$postContent = $data['works_content'];
foreach( $postContent as $content ):
	$theSurnames = get_field('surname', $content->ID);
	$surnames[] = $theSurnames;
	$theFullNames = get_field('full_name', $content->ID);
	$fullNames[] = $theFullNames;
endforeach;

?>
<section class="u-section">
	<?php 
		if( $showWorksFilters ) : ?>
			<section class="u-l-container--full-width u-l-horizontal-padding--medium u-l-vertical-padding--small c-online-exhibitions__btn-title-wrap stickyTb" >
				<h3 class="l-content__title-break c-online-exhibitions__btn-title"><?= $worksFilterTitle ?></h3>
				
				<div class="c-online-exhibitions__btn-container c-filter__wrap filters">

						<!-- Filter button — mobile only -->
						<div class="c-filter__container c-filter__container--mobile show-filters-btn" data-filter-select >
							<div class="c-filter__title"><span class="c-filter__title__span">Filter</span></div>
						</div>

						<!-- Artist name filter -->
						<div class="c-filter__container c-filter__container--desktop" data-filter-select >
							<div class="c-filter__title"><span class="c-filter__title__span">Artist</span>
								<ul tabindex="1" class="button-group c-filter__select-menu" data-filter-group="artist">
									<?php
									echo '<li data-items data-filter class="c-filter__item item">All Artists</li>';
									sort($surnames);
									foreach($surnames as $index => $value) {
										$key = str_replace(" ", "-", $surnames[$index]);
										$surnameToLower = strtolower( $key );
										echo '<li data-filter=".' . $surnameToLower . '" class="c-filter__item item">' . $fullNames[$index] . '</li>';
									}
									?>
								</ul>
							</div>
						</div>

						<!-- Medium filter -->
						<div class="c-filter__container c-filter__container--desktop" data-filter-select >
							<div class="c-filter__title"><span class="c-filter__title__span">Medium</span>
								<ul tabindex="2" class="button-group c-filter__select-menu" data-filter-group="medium">
									<?php
										$field = get_field_object('field_5f59ebf6d4758');
										$choices = $field['choices'];
										foreach( $choices as $choice => $label ):
											echo '<li data-filter=".' . $choice . '" class="c-filter__item item">' . $label . '</li>';
										endforeach;
									?>
								</ul>
							</div>
						</div>

						<!-- Sort filter -->
						<div class="c-filter__container c-filter__container" data-filter-select >
							<div id="sorts" tab-index="3" class="c-filter__title c-filter__title--sort" data-filter-group="a-z">
								<span class="sort c-filter__title__span c-filter__sort--a-z" data-sort-value="name" data-sort-direction="asc">Sort A - Z</span>
								<span class="sort c-filter__title__span c-filter__sort--z-a" data-sort-value="name" data-sort-direction="desc">Sort Z - A</span>
							</div>
						</div>

						<!-- Clear filter -->
						<span class="button--reset c-filter__container--clear c-filter__reset">Clear filters</span>
					</div>
			</section>

			<div class="mobile-filters filters">
				<!-- Artist name filter -->
						<div class="c-filter__container c-filter__container--mobile" >
							<div class="c-filter__title"><span class="c-filter__title__span c-filter__title__span--mobile" >Artist</span>
								<ul tabindex="1" class="button-group c-filter__select-menu" data-filter-group="artist">
									<?php
									echo '<li data-items data-filter class="c-filter__item item">All Artists</li>';
									sort($surnames);
									foreach($surnames as $index => $value) {
										$key = str_replace(" ", "-", $surnames[$index]);
										$surnameToLower = strtolower( $key );
										echo '<li data-filter=".' . $surnameToLower . '" class="c-filter__item item">' . $fullNames[$index] . '</li>';
									}
									?>
								</ul>
							</div>
						</div>

						<!-- Medium filter -->
						<div class="c-filter__container c-filter__container--mobile" >
							<div class="c-filter__title"><span class="c-filter__title__span c-filter__title__span--mobile" >Medium</span>
								<ul tabindex="2" class="button-group c-filter__select-menu" data-filter-group="medium">
									<?php
										$field = get_field_object('field_5f59ebf6d4758');
										$choices = $field['choices'];
										foreach( $choices as $choice => $label ):
											echo '<li data-filter=".' . $choice . '" class="c-filter__item item">' . $label . '</li>';
										endforeach;
									?>
								</ul>
							</div>
						</div>
			</div>
			
	<?php endif; ?>

	<div class="u-l-container--center">
		<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
			<div class="c-works">
				
				<div class="c-filter__no-results">
					Sorry, no results found. <span class="button--reset">Clear filters</span>
				</div>

				<div class="c-works__list" data-isotope >

				<?php

					$postContent = $data['works_content']; 
					
					// If Show filters if on c-title-break then shuffle Works content
					if( $showWorksFilters ) {
						shuffle($postContent);
					}

					foreach( $postContent as $content ) :

                  $key = get_the_id($content->ID);
                  $title = get_the_title($content->ID);
                  $link = get_the_permalink($content->ID);
                  $subPostTitle = get_field('sub_post_title', $content->ID);
                  $surname = get_field('surname', $content->ID);
                  $fullName = get_field('full_name', $content->ID);
						$image = get_the_post_thumbnail_url($content->ID, 'thumbnail');
						//$image = the_post_thumbnail('thumbnail', $content->ID);
                  $date = get_field('date', $content->ID);
                  $description = get_field('description', $content->ID);
                  $medium = get_field('medium', $content->ID);
                  //$mediumText = get_field('medium_free_text', $content->ID);
                  $decade = get_field('decade', $content->ID);
                  $dimensions = get_field('dimensions', $content->ID);
                  $price = get_field('price', $content->ID);
                  $priceRange = get_field('price_range', $content->ID);
                  $sold = get_field('sold', $content->ID);
                  $ids = get_field('code_id', $content->ID);
                  $creditLine = get_field('credit_line', $content->ID);
						$hidePurchaseButton = get_field('hide_purchase_button', $content->ID);
						$surnameToLower = strtolower( $surname );
					
					?>
            	<article class="c-works__card filter-item <?= $surnameToLower ?> <?php foreach( $medium as $value ): echo $value['value'] . ' '; endforeach; ?>" data-subject="<?= $surnameToLower ?>" data-type="<?php foreach( $medium as $value ): echo $value['value'] . ' '; endforeach; ?>" >
						<figure class="c-works__figure">
							<a href="<?= $link ?>">
								<img src="<?= $image ?>" alt="<?= $title ?>" class="c-works__image">
							</a>
						</figure> 

						<span class="name" style="display:none"><?= $surname ?></span>

						<a href="<?= $link; ?>">
							<h2 class="c-works__title"><?= $title; ?></h2>
						</a>
						
						<?php if( $fullName && $showWorksFilters ) { ?>
							<div class="c-works__name"><?= $fullName ?></div>
						<?php } ?>

						<?php if( $date ) { ?>
							<div class="c-works__date"><?= $date ?></div>
						<?php } ?>

						<?php if( $mediumText ) { ?>
							<div class="c-works__medium"><?= $mediumText ?></div>
						<?php } ?>
						
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