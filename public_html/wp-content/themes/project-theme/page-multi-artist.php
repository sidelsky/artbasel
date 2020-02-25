<?php

/**
 * Template Name: Multi artist
 */

use App\Helper\Render;
use Theme\Model\Layout;
use Theme\Model\ViewingRoom;

$render = new Render;
$layout = new Layout;
$viewing_room = new ViewingRoom;


$allLayouts = $layout->getLayout();

include("header.php"); ?>


<?php
    $template = 'c-viewing-room';
    $data = $viewing_room->getData();
    echo $render->view('Components/' . $template, $data);
?>

<?php include("partials/ma-email-sub.php"); ?>

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
