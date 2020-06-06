<?php if( $data['titleBreakTitle'] ) : ?>
	<section class="u-section">
		<div class="<?= $args['padding'] ? 'u-l-horizontal-padding--medium' : ''?> u-l-container--full-width u-l-vertical-padding--small">
			<div class="l-content__title-break <?= $args['altFontClass'] ? 'l-content__title-break--alt-font' : '' ?>">
				<?= $data['titleBreakTitle']; ?>
			</div>    
		</div>
	</section>
<?php endif; ?>

<?php if( $args['title'] ) : ?>
	<section class="u-l-container--full-width u-l-horizontal-padding--medium u-l-vertical-padding--small c-online-exhibitions__btn-title-wrap">
		<div class="l-content__title-break c-online-exhibitions__btn-title <?= $args['altFontClass'] ? 'l-content__title-break--alt-font' : '' ?>"><?= $args['title'] ?></div>
		
		<?php if( $args['showControls'] ) : ?>
			<div class="c-online-exhibitions__btn-container">
				<div id="prev-slide" class="c-online-exhibitions__btn-prev"></div>  
				<div id="next-slide" class="c-online-exhibitions__btn-next"></div>
			</div>
		<?php endif; ?> 	

	</section>
<?php endif; ?>