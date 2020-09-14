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
			<div class="c-online-exhibitions__btn-container c-filter__wrap">
				<!-- Artist name filter -->
				<div class="c-filter__container filters" data-id="filter-select" >
					<div class="c-filter__title"><span>Artist</span>
						<ul tabindex="0" class="button-group c-filter__select-menu" data-id="group">
							<?php
								foreach($allLayouts as $value) {
									$contents = $value['works_content'];
									foreach($contents as $content) {
										$valueToLower = strtolower( $content->post_title );
										$key = str_replace(" ", "-", $valueToLower);
										$keyStrp = str_replace(",", "", $key);
										echo '<li data-filter=".' . $keyStrp . '" class="c-filter__item">' . $content->post_title . '</li>';
									}
								} 
							?>
						</ul>
					</div>
				</div>

				<!-- Medium filter -->
				<div class="c-filter__container filters" data-id="filter-select" >
					<div class="c-filter__title"><span>Medium</span>
						<ul tabindex="0" class="button-group c-filter__select-menu" data-id="group">
							<?php
								$field = get_field_object('field_5f59ebf6d4758');		
								$values = $field['choices'];
								foreach( $values as $value ):
									$valueToLower = strtolower( $value );
									$key = str_replace(" ", "-", $valueToLower);
									echo '<li data-filter=".' . $key . '" class="c-filter__item">' . $value . '</li>';
								endforeach;
							?>
						</ul>
					</div>
				</div>


				<!-- Sort filter -->
				<div class="c-filter__container filters" data-filter-select >
					<div class="c-filter__title c-filter__title--sort">
						<span>Sort A - Z</span>
					</div>
				</div>

			</div>
		<?php endif; ?> 	

	</section>
<?php endif; ?>