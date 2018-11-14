<section class="u-section u-bg-color--bw-white">
    <div class="u-l-container u-l-container--shallow" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding">
            <?php foreach($data['awardsList'] as $award) { ?>
                <div class="c-awards">
                    <div class="canvas">
                        <span>
                            <h4 class="c-awards__title"><?= $award['awardsTitle']; ?></h4>
                            <p class="c-awards__description"><?= $award['awardDescription']; ?></p>
                        </span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>