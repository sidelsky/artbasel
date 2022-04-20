<?php
namespace Theme\Model;

use App\WordPress\WordPress;
use \WP_Query;

use Theme\Model\TitleBreak;
use Theme\Model\TextContent;
use Theme\Model\ImageContent;
use Theme\Model\WiderTextContent;
use Theme\Model\ScrollToNavigationIds;
use Theme\Model\ArtFairs;
use Theme\Model\Blockquote;
use Theme\Model\VideoCentered;
use Theme\Model\TwoImages;
use Theme\Model\FullWidthImages;

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

            //Get Full width image
			   case 'full_width_image':
               $fullWidthImage = new FullWidthImages($layout);
               $currentLayout = $fullWidthImage->getFullWidthImage();
            break;

            //Get Blockquote
			   case 'blockquote':
               $blockquote = new Blockquote($layout);
               $currentLayout = $blockquote->getBlockquote();
            break;

            //Wider text content
			   case 'wider_text_content':
               $widerTextContent = new WiderTextContent($layout);
               $currentLayout = $widerTextContent->getWiderTextContent();
            break;

            //Scroll to navigation
			   case 'scroll_to_navigation':
               $scrollToNavigationIds = new ScrollToNavigationIds($layout);
               $currentLayout = $scrollToNavigationIds->getScrollToNavigationIds();
            break;

            //Art fairs
			   case 'art_fairs':
               $artFairs = new ArtFairs($layout);
               $currentLayout = $artFairs->getArtFairs();
            break;

            //Video centered
			   case 'video_centered':
               $videoCentered = new VideoCentered($layout);
               $currentLayout = $videoCentered->getVideoCentered();
            break;

            //Two images
			   case 'two_images_module':
               $twoImages = new TwoImages($layout);
               $currentLayout = $twoImages->getTwoImages();
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
