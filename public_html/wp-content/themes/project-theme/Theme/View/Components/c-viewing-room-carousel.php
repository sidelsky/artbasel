<div class="c-viewing-rooms-header owl-carousel owl-hero-carousel">
   <?php foreach($data['currentViewing'] as $viewingRoom) { ?>

      <div class="c-viewing-rooms-header c-header-background-image" style="background-image: url('<?= $viewingRoom['currentViewingRoomImage'] ?>')">
        <span class="c-header-background-image__shading" style="background-color: rgba(0,0,0, <?= get_field('image_shading_cover') ?>"></span>
            <div class="canvas parallax-window__content parallax-window__content" data-id="title">
                <div>
                    <h1 class="c-site-headings c-site-headings--h1--hero c-text-align-centre "><?= $viewingRoom['currentViewingRoomPretitle'] ?></h1>
                    <h2 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?= $viewingRoom['currentViewingRoomTitle'] ?></h2>

                    <span class="c-works__href-wrap c-works__href-wrap--center">

                    <?php if($viewingRoom['currentViewingRoomLinkDescription']) { ?>

                        <span class="c-works__href-wrap c-works__href-wrap--center l-content__block--link">
        
                        <?php if( $viewingRoom['currentViewingRoomLinkDescription'] && !$viewingRoom['currentViewingRoomLink'] ) {
                            echo $viewingRoom['currentViewingRoomLinkDescription'];
                        } ?>
        
                        <?php if( $viewingRoom['currentViewingRoomLink'] && $viewingRoom['currentViewingRoomLinkDescription'] ) { ?>
                            <a href="<?= $viewingRoom['currentViewingRoomLink'] ?>" class="c-works__href c-works__href--no-arrow"><?= $viewingRoom['currentViewingRoomLinkDescription'] ?></a> 
                        <?php } ?>

                        </span>

                    <?php } ?>
                        
                </div>
            </div>
        </div>

<?php } ?>
</div>