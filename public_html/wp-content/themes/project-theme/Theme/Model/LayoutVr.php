<?php
namespace Theme\Model;

use App\WordPress\WordPress;
use \WP_Query;
use Theme\Model\TitleBreak;
use Theme\Model\TextContent;
use Theme\Model\ImageContent;
use Theme\Model\WorksContent;
use Theme\Model\WiderTextContent;

class LayoutVr {

   public function __construct()
   {

      $this->layoutBuilder = get_field('content_builder');
      $this->layoutOutput = [];

      if ( is_array($this->layoutBuilder) && count($this->layoutBuilder) > 0 ) {
            
      foreach($this->layoutBuilder as $layout) {
         $layoutName = $layout['acf_fc_layout'];
         $currentLayout;
         
         //Add another module here
         switch ($layoutName) {

            //Get Text content
            case 'title_break':
               $titleBreak = new TitleBreak($layout);
               $currentLayout = $titleBreak->getTitleBreak();
				break;

            //Get Text content
            case 'text_content':
               $textContent = new TextContent($layout);
               $currentLayout = $textContent->getTextContent();
				break;
            
			   //Get Image
			   case 'image_content':
               $imageContent = new ImageContent($layout);
               $currentLayout = $imageContent->getImageContent();
            break;

         	//Get Works
			   case 'works_content':
               $worksContent = new WorksContent($layout);
               $currentLayout = $worksContent->getWorksContent();
            break;

            //Wider text comp
			   case 'wider_text_content':
               $widerTextContent = new WiderTextContent($layout);
               $currentLayout = $widerTextContent->getWiderTextContent();
            break;

            //Kuula
			   case 'kuula':
               $kuula = new Kuula($layout);
               $currentLayout = $kuula->getKuula();
            break;
            
                
        }
         $this->layoutOutput[] = $currentLayout;
      }

   }

   }
   
   public function getLayout() {
      return $this->layoutOutput;
   }
}