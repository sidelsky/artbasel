<?php if( $data['blockquote'] ) : ?>

	<section class="u-section c-blockquote">
		<div class="u-l-container--full-width u-l-horizontal-padding--medium u-l-vertical-padding--small">
			<blockquote class="l-content__title-break--alt-font">
            <img src="<?= $data['blockquote_thumbnail']['url'] ?>" alt="<?= esc_attr( $data['blockquote_thumbnail']['alt'] ); ?>">   
            <span><?= $data['blockquote'] ?><br><cite><?= $data['cite'] ?></cite></span>
			</blockquote>    
		</div>
   </section>
   
<?php endif; ?>