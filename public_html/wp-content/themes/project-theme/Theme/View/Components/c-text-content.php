<article class="l-content__block <?= $data['darkBackground'] ? 'l-content__block--dark-background' : ''; ?> <?= $data['justifyTextRight'] ? 'l-content__block--align-right' : ''; ?>">
	<div class="canvas l-content__block--center l-content__block--text-content">

		<div>
		
			<?php if($data['textContentTitle']) : ?>
				<h2 class="l-content__block--title">
					<?= $data['textContentTitle']; ?>
				</h2>
			<?php endif; ?>
			
			<?php if($data['textContentDate']) : ?>
				<div class="l-content__block--date">
					<?= $data['textContentDate']; ?>
				</div>
			<?php endif; ?>
			
			<?php if($data['textContentCopy']) : ?>
				<div class="l-content__block--body-text">
					<?= $data['textContentCopy']; ?>
				</div>
			<?php endif; ?>

			<?php if($data['textContentLinkDescription']) : ?>
				<span class="c-works__href-wrap c-works__href-wrap--center l-content__block--link">

				<?php if( $data['textContentLinkDescription'] && !$data['textContentLink'] ) : ?>
					<?= $data['textContentLinkDescription']; ?>
				<?php endif; ?>

					<?php if( $data['textContentLink'] && $data['textContentLinkDescription'] ) : ?>
						<a href="<?= $data['textContentLink']?>" class="c-works__href"><?= $data['textContentLinkDescription']; ?></a>
						<svg class="u-icon c-works__icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow<?= $data['darkBackground'] ? '-white' : ''; ?>" viewBox="0 0 32 32"></use>
						</svg>
					<?php endif; ?>
				</span>
			<?php endif; ?>
			
		</div>
		
	</div>
</article>