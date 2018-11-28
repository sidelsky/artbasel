<?php if( $data['showPromo'] ) : ?>
  <section class="u-section">
    <div class="u-l-container--center" data-in-viewport>   
        <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding">   
        
        <!-- Content -->
          <div class="u-column u-column--half-width <?= $data['position'] ? '' : 'u-column--half-width--right'; ?>">
              <h2 class="c-site-headings c-site-headings--h2"><?= $data['title'] ?></h2>
              <?php foreach($data['items'] as $promo) { ?>
                <div class="c-promo-card">
                    
                    <div class="c-promo-card__icon">
                      <img src="<?= $promo['promoIcon'] ?>" alt="<?= $promo['promoTitle'] ?>">
                    </div>

                    <div class="c-promo-card__content">
                      <?php if( $promo['promoTitle'] ) : ?>
                          <h3 class="c-site-headings c-site-headings--h3"><?= $promo['promoTitle'] ?></h3>
                      <?php endif; ?>
                      <?= $promo['promoDescription'] ?>
                    </div>

                </div>
              <?php } ?>
          </div>

            <!-- Image -->
            <?php if( $data['image'] ) : ?>
              <div class="u-column u-column--image u-column--half-width <?= $data['position'] ? '' : 'u-column--half-width--left'; ?>">
                <img src="<?= $data['image'] ?>" >
              </div>   
          <?php endif; ?>

        </div>
    </div>
  </section>
<?php endif; ?>