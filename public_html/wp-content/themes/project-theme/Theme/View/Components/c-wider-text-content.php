<?php if( $data['widerTextContent'] ) : ?>

	<section class="u-section">
		<div class="u-l-container --full-width l-content__block__text-content--wide">  
            <div class="l-content__block__text-content l-content__block__body-text">
               <h2 class="l-content__block__title <?= $args['altFontClass'] ? 'l-content__block__title--alt-font' : ''?>"><?= $data['widerTextTitle'] ?></h2>
               <?= $data['widerTextContent'] ?>
            </div>
		</div>
   </section>

<?php endif; ?>