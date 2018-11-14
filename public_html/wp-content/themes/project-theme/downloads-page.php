<?php
/**
 * Template name: Downloads
 */
use App\Helper\Render;
use Theme\Model\Downloads;

$render = new Render;
$downloads = new Downloads();

include("header.php"); ?>
    
    <?php
        $template = 'c-downloads';
        $data = $downloads->getData();
        echo $render->view('Components/' . $template, $data);
    ?>
    
<?php include("footer.php"); ?>
