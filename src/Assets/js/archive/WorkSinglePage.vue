<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container--full-width u-l-container--row">
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

        <article class="c-work-single--full-width">

			<div class="c-work-single__column">

				<figure class="c-work-single__figure">
					<carousel 
						:autoplay="true"
						:loop="true"
						:nav="false"
						:dots="true"
						:mouseDrag="false"
						:responsive="{0:{items:1},600:{items:1},768:{items:1}}
						">
						<div v-for="item in gallery" :key="item">
							<img :src="item" class="c-work-single__image" v-on:click="handleItemClick(item)">
						</div>
					</carousel>
				</figure>

				<!-- Video content -->
				<div class="video" v-if="work.video">
					<div class="video__wrapper" v-html="work.video"></div>
				</div>

				<!-- WP content -->
				<div class="s-content c-works-content" v-html="work.content"></div>

			</div>

            <div class="c-work-single__column">
              <div class="c-work-single__content">
                <h2 class="c-works__title"><span v-html="work.title"></span></h2>
                <h2 class="c-works__title"><span v-html="work.subPostTitle"></span></h2>
                <div class="c-works__name">{{ work.fullName }}</div>
                <div class="c-works__date">{{ work.date }}</div>
                <div class="c-works__medium"><span v-html="work.mediumText"></span></div>
                <div class="c-works__medium"><span v-html="work.mediumChinese"></span></div>
                <div class="c-works__dimensions"><span v-html="work.dimensions"></span></div>
                <div class="c-works__price"><span>{{ formattedPrice }}</span></div>

					<div class="c-works__availability">
						<span
						:class="[
								'c-sale-marker',
								`${ work.sold ? 'c-sale-marker--sold' : 'c-sale-marker--available'}`
							]"
						></span><span class="c-sale-marker__copy" v-html="formattedSold"></span>
					</div>

					<a v-if="!work.sold" :href="`mailto:viewingroom@hauserwirth.com?subject=Inquire to purchase: ${work.ids}&body=Hello, I'd like to inquire about: ${work.ids}`" class="c-button c-button--dark">Purchase</a>
          <a :href="`mailto:viewingroom@hauserwirth.com?subject=Inquire to purchase: ${work.ids}&body=Hello, I'd like to inquire about: ${work.ids}`" class="c-button c-button--light">Inquire to learn more</a>

          <span class="c-works__href-wrap">
						<a href="/" class="c-works__href">View all works</a>
						<svg class="u-icon c-works__icon">
							<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use>
						</svg>
					</span>

				</div>
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
//import VCarousel from './components/VCarousel'
//import VCarouselSlide from './components/VCarouselSlide'
import carousel from 'vue-owl-carousel'
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
	  carousel,
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
    formattedSold() {
      if( this.work.sold != 1 ) {
        return 'Available'
      } else {
        return 'Sold'
      }
    },
    formattedPrice() {
      if( this.work.sold != 1 ) {
        if ( this.work.price ) {
        //return formatter.format(this.work.price)
        return this.work.price
      } else {
        return ''
      }
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