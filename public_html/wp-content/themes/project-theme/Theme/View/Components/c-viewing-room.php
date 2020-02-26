
<?php foreach($data['currentViewing'] as $viewingRoom) { ?>
    <div class="u-section c-paralax-header c-paralax-header--desktop">
        <div class="c-header-background-image" style="background-image: url('<?= $viewingRoom['currentViewingRoomImage']; ?>')">
            <div class="parallax-window__content title" id="title" data-id="title">
                <h1 class="c-site-headings  c-site-headings--h1 c-site-headings--h1--hero c-text-align-centre "><?= $viewingRoom['currentViewingRoomPretitle']; ?></h1>
                <h2 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?= $viewingRoom['currentViewingRoomTitle']; ?></h2>
                <!-- <div class="u-l-container--narrow c-site-headings c-site-headings--h1--small c-site-headings--text-align-center"><?= $content; ?></div> -->
                <span class="c-works__href-wrap c-works__href-wrap--center">
                    <a href="<?= $viewingRoom['currentViewingRoomLink']; ?>" class="c-works__href">Enter viewing room</a>
                    <svg class="u-icon c-works__icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow-white" viewBox="0 0 32 32"></use>
                    </svg>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
