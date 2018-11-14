<?php
use App\Helper\Render;
$render = new Render;
?>

<section class="u-section">
   <div class="u-l-container--center c-hero" <?= $data['backgroundImage'] ? 'style="background-image:url('. $data['backgroundImage'] .')"' : ''; ?> data-in-viewport>   
      
      <div class="u-l-container u-l-horizontal-padding">
         <div class="u-column u-column--two-thirds" id="title">
            <h1 class="c-site-headings c-site-headings--uppercase c-site-headings--underline c-site-headings--h1"><?= $data['title'] ?></h1>
            <div class="c-hero__content c-hero__content--large">
               <?= $data['content'] ?>
               <? /* $render->view('Components/c-button'); */ ?>
               <!-- <div class="store-icons"> -->
                  <?php
                  /**
                   * App & Play icons
                   */
                  foreach ($data['store-icons'] as $value) {
                  if( $value['url'] ) {
                        echo '<a href="'. $value['url'] .'" target="_blank" class="store-icons__href">';
                  } else {
                        echo '<a class="store-icons__href">';
                  }
                        echo '<img src="'. get_bloginfo('template_url') . '/assets/build/img/' . $value['icon'] .'" class="store-icons__icon" alt="'. $value['title'] .'">';
                  echo '</a>';
                  }
                  ?>
               <!-- </div> -->
         </div>
      </div>
      </div>
      
<!-- <div class="c-hero__scroll-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="32" viewBox="0 0 18 29"><path d="M0 2v-4z" id="a"/><g stroke="#fff"><rect fill="none" x=".5" y=".5" width="16.4" height="28" rx="8.2"/><path d="M8.6 5.2v8.4" stroke-dasharray="13.6" stroke-linejoin="round"><animate attributeName="stroke-dashoffset" from="13.6" to="-13.6" dur="1s" repeatCount="indefinite"/></path><animateMotion dur="3s" repeatCount="indefinite"><mpath href="#a"/></animateMotion></g></svg>
</div> -->
            
      
<div class="hero-curve" style="background-image:url(<?php bloginfo('template_directory'); ?>/assets/build/img/curve-background.png)"></div>

</section>