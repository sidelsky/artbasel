<div class="social-links u-bg-color--primary-base u-l-horizontal-padding">
    <div class="u-l-container u-section-underline social-links__row">
        <span class="social-links__title">Connect with Us</span>
        <?php foreach ($themeData['social'] as $item) { ?>
            <a class="social-links__link" href="<?= $item['url']; ?>" target="<?= $item['target']; ?>">
                <svg class="u-icon social-links__icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-<?= $item['icon']; ?>" viewBox="0 0 32 32"></use>
                </svg>
            </a>
        <?php }?>
    </div>	
</div>