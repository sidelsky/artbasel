<?php

/**
 * Template Name: Multi artist
 */

use App\Helper\Render;
use Theme\Model\Layout;

$render = new Render;
$layout = new Layout;

$allLayouts = $layout->getLayout();

include("header.php"); ?>

<section class="c-ma-email-sub u-l-horizontal-padding--large u-l-vertical-padding--small">
    <div class="c-ma-email-sub__wrapper">
        <div class="c-ma-email-sub__column">
            <span>Be the first to receive updates on<br>Artists Choices Online Viewing Room presentations</span>
        </div>
        <div class="c-ma-email-sub__column">
            <?php echo do_shortcode('[gravityform id="4" title="false" description="false"]'); ?>
        </div>
    </div>
</section>
    
    <section class="l-content">
        <?php

            foreach($allLayouts as $value) {

                $templateName = '';
                
                switch ($value['layoutName']) {
                    
                    //Get Title break
                    case 'title_break':
                        $templateName = 'c-title-break';
                        break;

                    //Get Text content
                    case 'text_content':
                        $templateName = 'c-text-content';
                        break;

                    //Get Image content
                    case 'image_content':
                        $templateName = 'c-image-content';
                        break;
            }

                echo $render->view('Components/' . $templateName, $value);
            }

        ?>
    </section>
<?php include("footer.php"); ?>
