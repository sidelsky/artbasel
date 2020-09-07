<section class="u-section" style="margin-bottom: 30px"> 
   <div class="c-kuula ">
      <button class="c-kuula__button" id="touchButton" data-id="touch-button">
         <svg class="c-kuula__touch-icon">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-touch" viewBox="0 0 32 32"></use>
         </svg>
      </button>
      <div class="c-kuula__image" style="background-image: url('<?= $data['kuula_image']['url'] ?>')" id="coverVR" data-id="touch-cover"></div>
      <?= $data['kuula_vr'] ?>
   </div>
</section>