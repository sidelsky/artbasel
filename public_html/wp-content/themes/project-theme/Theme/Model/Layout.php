<?php
namespace Theme\Model;

use App\WordPress\WordPress;
use \WP_Query;
use Theme\Model\TitleBreak;
use Theme\Model\TextContent;
use Theme\Model\ImageContent;

class Layout {

   public function __construct()
   {

      $this->layoutBuilder = get_field('content');
      $this->layoutOutput = [];
      
      if ( is_array($this->layoutBuilder) && count($this->layoutBuilder) > 0 ) {

      foreach($this->layoutBuilder as $layout) {
         $layoutName = $layout['acf_fc_layout'];
         $currentLayout;
         
         //Add another module here
         switch ($layoutName) {

            //Get Title break
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
            
            //Get Image
			   case 'full_width_image':
               $fullWidthImage = new FullWidthImage($layout);
               $currentLayout = $fullWidthImage->getFullWidthImage();
            break;
            
            //Get Blockquote
			   case 'blockquote':
               $blockquote = new Blockquote($layout);
               $currentLayout = $blockquote->getBlockquote();
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