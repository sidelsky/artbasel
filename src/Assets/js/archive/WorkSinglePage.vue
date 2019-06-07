<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
        <!-- to go here -->
        <div class="c-lightbox" v-if="showLightbox" @click="showLightbox = false">
          <button @click="showLightbox = false" class="c-lightbox__close">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 31.112 31.112">
              <polygon points="31.112,1.414 29.698,0 15.556,14.142 1.414,0 0,1.414 14.142,15.556 0,29.698 1.414,31.112 15.556,16.97
            	29.698,31.112 31.112,29.698 16.97,15.556 "/>
            </svg>
          </button>
          <img :src="currentImage" class="c-lightbox__image">
        </div>
        <article class="c-work-single">

            <div class="c-work-single__column">
              <figure class="c-work-single__figure">
                <VCarousel :currentSlide="currentSlide" @calculatedTotalSlides="(total) => this.totalSlideNumber = total">
                  <template v-if="!gallery.length">
                    <VCarouselSlide>
                      <img :src="work.image" class="c-work-single__image" @click="handleItemClick(work.image)">
                    </VCarouselSlide>
                  </template>
                  <template v-else>
                    <VCarouselSlide v-for="item in gallery" :key="item">
                      <img :src="item" class="c-work-single__image" @click="handleItemClick(item)">
                    </VCarouselSlide>
                  </template>
                </VCarousel>
              </figure>
            </div>

            <div class="c-work-single__column">
                <h2 class="c-works__title"><span v-html="work.title"></span></h2>
                <h2 class="c-works__title"><span v-html="work.subPostTitle"></span></h2>
                <div class="c-works__date">{{ work.date }}</div>
                <div class="c-works__medium"><span v-html="work.mediumText"></span></div>
                <div class="c-works__medium"><span v-html="work.mediumChinese"></span></div>
                <div class="c-works__dimensions"><span v-html="work.dimensions"></span></div>
                  <div class="c-works__price">
                  <span
                  :class="[
                          'c-sold',
                          `${ work.sold ? 'c-sold--active' : ''}`
                        ]"
                  ></span>{{ formattedPrice }}</div>
                <span class="c-works__href-wrap">
                  <a :href="`mailto:viewingroom@hauserwirth.com?subject=Inquire to purchase - ${work.title}&body=Hello, I'd like to inquire about: ${work.title}`"
                  class="c-works__href">Inquire to purchase</a>
                  <svg class="u-icon c-works__icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use>
                  </svg>
                </span>


                <ul class="c-carousel-controls" v-if="totalSlideNumber > 1">
                  <li class="v-m-carousel__control v-m-carousel__control--prev" @click="previous()">
                    <button
                      :class="[
                        currentSlide > 0 ? '' : 'disabled',
                        'carousel-button'
                      ]"
                      
                    ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 14" id="next" width="100%" height="100%"><path fill="currentColor" d="M6.5 7L0 14v-2l4.5-4.941L0 2V0z"></path></svg></button>
                  </li>

                  <li class="v-m-carousel__control v-m-carousel__control--next" @click="next()">
                    <button :class="[
                      currentSlide < totalSlideNumber - 1 ? '' : 'disabled',
                      'carousel-button'
                    ]"
                    
                    ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 14" id="next" width="100%" height="100%"><path fill="currentColor" d="M6.5 7L0 14v-2l4.5-4.941L0 2V0z"></path></svg></button>
                  </li>
                </ul>

                <a href="/works" class="c-button">View all available works</a>
                <p style="margin-bottom: 20px;">&nbsp;</p>
                <div class="s-content" v-html="work.content"></div>
            </div>
        </article>
      </div>

     <!-- Cookie Law -->
      <cookie-law theme="hauser-theme"> 
        <div slot-scope="props" class="c-cookiepop">
          <p>
            This site uses cookies to improve user experience. By clicking 'Accept' or by continuing to use this site, you consent to our use of cookies. Click ‘Learn more’ for information on how we use cookies and how you can control them.
          </p>
          <div class="hw-button-wrapper">
            <button class="skew" @click="props.close"><span><a href="https://www.vip-hauserwirth.com/site-terms-of-use/">Learn more</a></span></button>
          </div>
          <div class="hw-button-wrapper">
            <button class="skew" @click="props.accept"><span>I accept</span></button>
          </div>
        </div>
      </cookie-law>

  </div>
</template>

<script>
import VCarousel from './components/VCarousel'
import VCarouselSlide from './components/VCarouselSlide'
import CookieLaw from 'vue-cookie-law'

const formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  currencyDisplay: 'code',
  useGrouping: true,
  minimumFractionDigits: 2
})

export default {
  components: {
    VCarousel,
    VCarouselSlide,
    CookieLaw
  },
  data () {
    return {
      currentSlide: 0,
      totalSlideNumber: 0,
      currentImage: '',
      showLightbox: false
    }
  },
  props: {
    gallery: Array,
    work: Object
  },
  computed: {
    formattedPrice() {
      if( this.work.sold != 1 ) {
      
        if ( this.work.price ) {
          return formatter.format(this.work.price)
        } else {
          return ''
        }

      } else {
        return 'Sold'
      }
    }
  },
  methods: {
    handleItemClick (item) {
      this.currentImage = item
      this.showLightbox = true
    },
    previous () {
      if (this.currentSlide > 0) {
        this.currentSlide -= 1
      } else if (this.loop) {
        this.currentSlide = this.totalSlideNumber - 1
      }
    },
    next () {
      if (this.currentSlide < this.totalSlideNumber - 1) {
        this.currentSlide += 1
      } else if (this.loop) {
        this.currentSlide = 0
      }
    }
  }
}
</script>
<style lang="css">
  .c-carousel-controls .disabled {
    cursor: default;
    opacity: .3 !important;
    background: none !important;
    border: 1px solid #aaa !important;
  }
</style>
