<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
        <!-- to go here -->
        <article class="c-work-single">
            <div class="c-work-single__column">
              <figure class="c-work-single__figure">
                <VCarousel>
                  <VCarouselSlide v-for="item in gallery">
                    <img :src="item" class="c-work-single__image">
                  </VCarouselSlide>
                </VCarousel>
              </figure>
            </div>
            <div class="c-work-single__column">
                <h2 class="c-works__title">{{ work.title }}</h2>
                <div class="c-works__date">{{ work.date }}</div>
                <div class="c-works__medium">{{ work.medium }}</div>
                <div class="c-works__dimensions">{{ work.dimension }}</div>
                <div class="c-works__price">{{ formattedPrice }}</div>
                <span class="c-works__href-wrap">
                  <a :href="`mailto:errol@squie.com?subject=Inquire to purchase - ${work.titlw }&body=Hello, I'd to inquire about ${work.title}...`"
                  class="c-works__href">Inquire to purchase</a>
                  <svg class="u-icon c-works__icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use>
                  </svg>
                </span>

                <a href="/works" class="c-button">View all available works</a>
            </div>
        </article>
      </div>
  </div>
</template>

<script>
import VCarousel from './components/VCarousel'
import VCarouselSlide from './components/VCarouselSlide'

const formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 2
})

export default {
  components: {
    VCarousel,
    VCarouselSlide
  },
  props: {
    gallery: Array,
    work: Object
  },
  computed: {
    formattedPrice() {
      return formatter.format(this.work.price)
    }
  }
}
</script>
