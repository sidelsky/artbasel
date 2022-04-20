<?php if( $data['widerTextContent'] ) : ?>
	<section class="u-section u-l-vertical-padding--margin-40">
		<div class="u-l-container<?= $args['alignLeft'] ? '--1150' : '--shallow'?> u-l-horizontal-padding--medium l-content__block__text-content--wide <?= $args['alignLeft'] ? 'u-l-container--no-margin' : ''?>">
          <div class="l-content__block__text-content l-content__block__body-text anchorlink" id="<?= $data['widerTextAnchor'] ?>">
						<?php if( $data['widerTextAnchor'] ) : ?>
							<a href="#anchor-top" class="anchor-top-link">
								<img src="/wp-content/themes/project-theme/assets/build/img/up-arrow.png" height="35" width="35" alt="back to links" />
					  	</a>
						<?php } ?>
            <?php if( $data['widerTextTitle'] ) { ?>
       				<h2 class="l-content__block__title <?= $args['altFontClass'] ? 'l-content__block__title--alt-font' : ''?>"><?= $data['widerTextTitle'] ?></h2>
            <?php } ?>


							 <?= $data['widerTextContent'] ?>
            </div>
		</div>
   </section>
<?php endif; ?>
