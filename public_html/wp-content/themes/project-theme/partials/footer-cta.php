<?php if( !is_page('downloads') ) : ?>
    <div class="footer-cta u-bg-color--primary-base u-l-horizontal-padding">
        <div class="u-l-container u-l-container--row u-section-underline footer-cta__row">
            <div class="footer-cta__column">
                <h2 class="footer-cta__title">Ready to get started?</h2>
                <p class="footer-cta__paragraph">Download the App to start your FREE trial.</p>
            </div>
            <div class="footer-cta__column">
                <?= $render->view('Components/c-button'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>