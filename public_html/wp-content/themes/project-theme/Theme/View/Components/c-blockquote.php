<?php if( $data['blockquote'] ) : ?>

	<section class="u-section c-blockquote u-l-vertical-padding--margin-40 <?= $args['altFontClass'] ? 'c-blockquote--alt-font' : ''?>">
		<div class="u-l-container--blockquote">
			<blockquote>
				<?php if( $data['blockquote_thumbnail']['url'] ) {?> 
					<img src="<?= $data['blockquote_thumbnail']['url'] ?>" alt="<?= esc_attr( $data['blockquote_thumbnail']['alt'] ); ?>">   
				<?php } ?>
            <span><?= $data['blockquote'] ?> <cite><?= $data['cite'] ?></cite></span>
			</blockquote>    
		</div>
   </section>
   
<?php endif; ?>