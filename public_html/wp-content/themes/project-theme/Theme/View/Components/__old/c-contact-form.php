<section class="u-section u-bg-color--bw-white">
    <div class="u-l-container--center u-l-container" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding--small">
            <?php 
                echo do_shortcode('[gravityform id="' . $data['id'] . '" title="' . $data['title'] . '" description="' . $data['description'] . '" ajax="' . $data['ajax'] . '"]');
             ?>
        </div>
    </div>
</section>