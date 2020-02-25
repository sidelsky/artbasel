<section class="u-section u-bg-color--bw-white">
    <div class="u-l-container--center u-l-container" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding--small">

            <?php foreach($data['currentViewing'] as $viewingRoom) { ?>
                <?= $viewingRoom['currentViewingRoomImage']; ?>
                <?= $viewingRoom['currentViewingRoomPretitle']; ?>
                <?= $viewingRoom['currentViewingRoomTitle']; ?>
                <?= $viewingRoom['currentViewingRoomLink']; ?>
            <?php } ?>
        </div>
    </div>
</section>