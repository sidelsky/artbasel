<?php if( $data['showFeature'] ) : ?>
	<section class="u-section u-bg-color--bw-white">
	<div class="u-l-container--center" data-in-viewport>
		<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding">
			
			<?php if( $data['featuresTitle'] ) { ?>
				<h2 class="c-site-headings--h2 c-site-headings--h2--feature"><?= $data['featuresTitle']; ?></h2>
			<?php } ?>

			<div class="c-features">
				<?php foreach($data['items'] as $feature) { ?>
				<?php
					//Get the image
					$image = $feature['featureIcon'];      
					//Get the image file path
					$imageFileType = new SplFileInfo($image);
					//Get extension 
					$extensionTypeIs = $imageFileType->getExtension();
					//Icon class
					$iconClass = '';
					//Check if has svg extention
					if( $extensionTypeIs == 'svg' ) {?>
						<div class="c-feature-card">
							<span class="c-feature-card__icon">
								<img src="<?= $feature['featureIcon']; ?>" alt="<?= $feature['alt']; ?> Icon">
							</span>
							<h3 class="c-site-headings--h3 c-feature-card__title"><?= $feature['featureTitle'] ?></h3>
							<?= $feature['featureDescription'] ?>
						</div>
					<?php } else {?>
						<div class="c-feature-card">
							<span class="c-feature-card__image">
								<img src="<?= $feature['featureIcon']; ?>" alt="<?= $feature['alt']; ?> logo">
							</span>
							<blockquote class="c-feature-card__blockquote">
								<?= $feature['featureDescription'] ?>
								<cite class="c-site-headings--h3 c-feature-card__title">
									<?= $feature['featureTitle'] ?>
								</cite>
							</blockquote>
						</div>
					<?php }?>

				<?php } ?>
			</div>

		</div>
	</div>
	</section>
<?php endif; ?>