<article class="l-content__block l-content__block__text-content <?= is_page_template('page-viewing-room.php' || 'page-private-sales-sub.php') ? 'l-content__block--wide-text' : '' ?> <?= $data['darkBackground'] ? 'l-content__block--dark-background' : ''; ?>">
	<div class="canvas l-content__block--center l-content__block__text-content">

	<div>
		<?php if($data['textContentTitle']) : ?>
			<h2 class="l-content__block__title <?= $args['altFontClass'] ? 'l-content__block__title--alt-font' : ''?>">
				<?= $data['textContentTitle']; ?>
			</h2>
		<?php endif; ?>
		
		<?php if($data['textContentDate']) : ?>
			<div class="l-content__block__date">
				<?= $data['textContentDate']; ?>
			</div>
		<?php endif; ?>
		
		<?php if($data['textContentCopy']) : ?>
			<div class="l-content__block__body-text">
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

		<?php if( $data['showEnquireButton'] ) : ?>
			<?php $message =  'I am interested in learning more about ' . $data['idCode'] . '. Please send me further details about this artwork, pricing, and availability.' ?>
			<button class="cta-button" data-id="inquire-button" value="<?= $message ?>" >Inquire</button>
		<?php endif; ?>

	</div>
</article>