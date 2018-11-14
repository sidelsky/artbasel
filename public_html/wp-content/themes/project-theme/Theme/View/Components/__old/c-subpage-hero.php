<section class="u-section">
	<div class="u-l-container--center c-subpage-hero">   
		<div class="u-l-container u-l-container--shallow u-l-horizontal-padding">
			<div class="align-center">
				<h1 class="c-site-headings c-site-headings--uppercase c-site-headings--h1 c-site-headings--h1--small"><?= $data['title'] ?></h1>
					<div class="c-hero__content">
					<p><?= $data['content'] ?></p>

					<?php if( is_404() ) : ?>
						<h1 class="c-site-headings c-site-headings--uppercase c-site-headings--h1 c-site-headings--h1--small">OOPS!</h1>
						<p>Error 404.</p>
					<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="hero-curve"  style="background-image:url(<?php bloginfo('template_directory'); ?>/assets/build/img/curve-background.png)"></div>
</section>