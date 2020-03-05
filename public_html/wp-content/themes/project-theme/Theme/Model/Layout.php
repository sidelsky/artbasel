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
      $post_id_dev = 2794;
      $post_id_prod = 3058;
  
      $localhost = 'artbasilvip:8888';
  
      if ($_SERVER['HTTP_HOST'] == $localhost) {
          $post_id = $post_id_dev;
      } else {
          $post_id = $post_id_prod;
      }  
      
      $this->layoutBuilder = get_field('content', $post_id);
      $this->layoutOutput = [];
      
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
                
        }
         $this->layoutOutput[] = $currentLayout;
      }

   }
   
   public function getLayout() {
      return $this->layoutOutput;
   }
}