<?php
    if( is_page('june-art-fair') ) {
        $boxShadow = 'box-shadow: inset 0px 0px 40px 20px rgba(146,192,132,1);';
    }
?>

<section class="c-viewing-rooms-header owl-carousel owl-hero-carousel">

   <?php foreach($data['currentViewing'] as $viewingRoom) { ?>

      <div class="c-viewing-rooms-header c-header-background-image" role="img" aria-label="<?= esc_attr( $viewingRoom['currentViewingRoomImage']['alt'] ); ?>" style="background-image: url('<?= $viewingRoom['currentViewingRoomImage']['url'] ?>')">
        <span class="c-header-background-image__shading" style="background-color: rgba(0,0,0, <?= get_field('image_shading_cover') ?>); <?= $boxShadow ?>"></span>
            <div class="canvas parallax-window__content parallax-window__content" data-id="title">
                <div>
                    <h1 class="c-site-headings c-site-headings--h1--hero c-text-align-centre">
                        <?= $viewingRoom['currentViewingRoomPretitle'] ?>
                        <?php if( $viewingRoom['currentViewingRoomPretitle'] ) {
                            echo '<br>';
                        }?>
                        <span class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center  <?= $args['altFontClass'] ? 'c-site-headings--h1--alt-font' : '' ?>"><?= $viewingRoom['currentViewingRoomTitle'] ?></span>
                    </h1>

                    <span class="c-works__href-wrap c-works__href-wrap--center">

                    <?php if($viewingRoom['currentViewingRoomLinkDescription']) { ?>

                        <span class="c-works__href-wrap c-works__href-wrap--center">
        
                        <?php if( $viewingRoom['currentViewingRoomLinkDescription'] && !$viewingRoom['currentViewingRoomLink'] ) {
                            echo '<span class="l-content__block--link">' . $viewingRoom['currentViewingRoomLinkDescription'] . '</span>';
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
</section>