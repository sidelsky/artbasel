<?php if( $data['show'] ) : ?>
    <section class="u-section u-bg-color--bw-white">
        <div class="u-l-container--center u-l-container--small" data-in-viewport>
            <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding--small">
                <?php foreach($data['deviceList'] as $download) { ?>
                    <div class="c-download-card">
                        <?= $download['deviceName']; ?>
                        <a href="<?= $download['storeUrl'] ?>" target="_blank">
                            <div class="c-download-card__device">
                                <img class="c-download-card__feature-image" src="<?= $download['deviceFeatureImage']; ?>">
                                <img class="c-download-card__device-type" src="<?= $download['typeOfDevice']; ?>">
                            </div>
                            <img class="c-download-card__store-logo" src="<?= $download['appStoreLogo']; ?>">
                        </a>
                        <!-- START: Details -->
                        <div class="c-download-card__details">
                            <h4>Minimum Requirements</h4>
                            <ul class="c-download-card__list">
                                <?php foreach($download['downloadDetails'] as $requirement) { ?>
                                    <li><?= $requirement['requirement'] ?></li>
                                <?php } ?>  
                            </ul>
                        </div>
                        <!-- END: Details -->
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php endif; ?>