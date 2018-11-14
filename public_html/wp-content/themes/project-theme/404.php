<?php

use App\Helper\Render;
//use Theme\Model\Downloads;

$render = new Render;
//$downloads = new Downloads();

include("header.php"); ?>
    
    <?php
        // $template = 'c-downloads';
        // $data = $downloads->getData();
        // echo $render->view('Components/' . $template, $data);
	?>
	

	<section class="u-section u-bg-color--bw-white">
    <div class="u-l-container u-l-container--shallow" data-in-viewport>
        <div class="u-l-container u-l-container--row u-l-horizontal-padding">
            <div class="c-additional-text">
            	<p>We can't seem to find the page you're looking for.</p>
            </div>
        </div>
    </div>
</section>

    
<?php include("footer.php"); ?>

