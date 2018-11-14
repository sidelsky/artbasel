<?php

use App\Helper\Render;
//use Theme\Model\Layout;

$render = new Render;
//$layout = new Layout;

//$allLayouts = $layout->getLayout();

//print_r($allLayouts);

include("header.php"); ?>
    
    <?php /*

        foreach($allLayouts as $value) {

            $templateName = '';
            
            switch ($value['layoutName']) {
                
                //Get the Promos
                case 'promo_module':
                    $templateName = 'c-promo';
                    break;

                //Get the Features
                case 'features_module':
                    $templateName = 'c-feature';
                    break;

                //Get the Price Plans
                case 'price_plans':
                    $templateName = 'c-plan';
                    break;

                //Get the Downloads
                case 'downloads':
                    $templateName = 'c-downloads';
                    break;
                
                //Get the Downloads
                case 'campaign_banner':
                    $templateName = 'c-campaign-banner';
                    break;
        }

            echo $render->view('Components/' . $templateName, $value);
        }
    */ ?>
<?php include("footer.php"); ?>
