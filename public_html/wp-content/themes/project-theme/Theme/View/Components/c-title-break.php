<?php
use App\Helper\Render;
use Theme\Model\LayoutVr;

$render = new Render;
$layout = new LayoutVr;

$allLayouts = $layout->getLayout();

if( $data['titleBreakTitle'] ) : ?>
	<section class="u-section">
		<div class="<?= $args['padding'] ? 'u-l-horizontal-padding--medium' : ''?> u-l-container<?= $args['fullWidth'] ? '--full-width' : ''?> u-l-vertical-padding--small">
			<div class="l-content__title-break <?= $args['altFontClass'] ? 'l-content__title-break--alt-font' : '' ?>">
				<?= $data['titleBreakTitle']; ?>
			</div>    
		</div>
	</section>
<?php endif; ?>

<?php if( $args['title'] ) : ?>
	<section class="u-l-container--full-width u-l-horizontal-padding--medium u-l-vertical-padding--small c-online-exhibitions__btn-title-wrap" <?= $args['backgroundColor'] ? ' style="background-color:#'.$args['backgroundColor'].'" ' : '' ?>">
		<div class="l-content__title-break c-online-exhibitions__btn-title <?= $args['altFontClass'] ? 'l-content__title-break--alt-font' : '' ?>"><?= $args['title'] ?></div>
		
		<?php 
		/** 
		 * Show controls 
		 * */
		if( $args['showControls'] ) : ?>
			<div class="c-online-exhibitions__btn-container">
				<div id="prev-slide" class="c-online-exhibitions__btn-prev"></div>  
				<div id="next-slide" class="c-online-exhibitions__btn-next"></div>
			</div>
		<?php endif; ?> 

		<?php 
		/** 
		 * Show filters
		 **/
		if( $args['filters'] ) : ?>
			<div class="c-online-exhibitions__btn-container c-filter__wrap filters"">
				
			<!-- Artist name filter -->
				<div class="c-filter__container" data-filter-select >
					<div class="c-filter__title"><span class="c-filter__title__span">Artist</span>
						<ul tabindex="0" class="button-group c-filter__select-menu" data-filter-group="artist">
							<?php
							echo '<li data-items data-filter class="c-filter__item item">All Artists</li>';
								foreach($allLayouts as $value) {
									$contents = $value['works_content'];	

									foreach($contents as $content) {
										
										$surname = get_field('surname', $content->ID);
										$fullName = get_field('full_name', $content->ID);
										
										$valueToLower = strtolower( $surname );
										$key = str_replace(" ", "-", $valueToLower);
										$keyStrp = str_replace(",", "", $key);
										echo '<li data-filter=".' . $keyStrp . '" class="c-filter__item item">' . $fullName . '</li>';
									}
								} 
							?>
						</ul>
					</div>
				</div>

				<!-- Medium filter -->
				<div class="c-filter__container" data-filter-select >
					<div class="c-filter__title"><span class="c-filter__title__span">Medium</span>
						<ul tabindex="0" class="button-group c-filter__select-menu" data-filter-group="medium">
							<?php
								$field = get_field_object('field_5f59ebf6d4758');		
								$values = $field['choices'];

								ksort($values);

								foreach( $values as $key => $value ):
									$valueToLower = strtolower( $value );
									$key = str_replace(" ", "-", $valueToLower);
									echo '<li data-filter=".' . $key . '" class="c-filter__item item">' . $value . '</li>';
								endforeach;
							?>
						</ul>
					</div>
				</div>

				<!-- Sort filter -->
				<div class="c-filter__container" data-filter-select >
					<div id="sorts" class="c-filter__title c-filter__title--sort" data-filter-group="a-z">
						<span class="sort c-filter__title__span c-filter__sort--a-z" data-sort-value="name" data-sort-direction="asc">Sort A - Z</span>
    					<span class="sort c-filter__title__span c-filter__sort--z-a" data-sort-value="name" data-sort-direction="desc">Sort Z - A</span>
					</div>
				</div>

				<!-- Sort filter -->
				<div class="c-filter__container">
					<div class="c-filter__title">
						<span class="button--reset c-filter__container--clear c-filter__reset">Clear filters</span>
					</div>
				</div>


			</div>
		<?php endif; ?> 	

	</section>
<?php endif; ?>