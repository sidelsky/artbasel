<?php
/**
 * Template name: Industry Recognition
 */
use App\Helper\Render;
use Theme\Model\Awards;
//use Theme\Model\AdditionalText;

$render = new Render;
$awards = new Awards();
//$additionalText = new AdditionalText();

include("header.php"); ?>
    
    <?php
        //Additional Text
        // $template = 'c-additional-text';
        // $data = $additionalText->getData();
        // echo $render->view('Components/' . $template, $data);

        include('partials/content.php');

        //Awards
        $template = 'c-awards';
        $data = $awards->getData();
        echo $render->view('Components/' . $template, $data);
    ?>

<?php include("footer.php"); ?>
