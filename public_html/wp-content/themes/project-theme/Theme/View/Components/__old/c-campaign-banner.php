<?php if( $data['showCampaignBanner'] ) : ?>
	<section class="u-section u-bg-color--bw-white">
	<div class="u-l-container--center" data-in-viewport>
		<div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding--small">

            <div class="c-campaign-banner">
				<span class="c-campaign-banner--cell">
				<span class="c-campaign-banner__title"><a href="<?= $data['link']; ?>"><?= $data['title']; ?></a></span>
				</span>
				<span class="c-campaign-banner--cell">
					<a href="<?= $data['link']; ?>" class="u-button u-button u-button--small c-campaign-banner__button">Find Out More</a>
				</span>
            </div>    

		</div>
	</div>
	</section>
<?php endif; ?>