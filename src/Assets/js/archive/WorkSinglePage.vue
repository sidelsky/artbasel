<template>
  <div class="u-l-container--center" data-in-viewport>
    <div class="u-l-container--full-width u-l-container--row">
      <!-- to go here -->
      <div class="c-lightbox" v-if="showLightbox" @click="showLightbox = false">
        <button @click="showLightbox = false" class="c-lightbox__close">
          <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 31.112 31.112">
            <polygon
              points="31.112,1.414 29.698,0 15.556,14.142 1.414,0 0,1.414 14.142,15.556 0,29.698 1.414,31.112 15.556,16.97
            	29.698,31.112 31.112,29.698 16.97,15.556 "
            />
          </svg>
        </button>
        <img :src="currentImage" class="c-lightbox__image" />
      </div>

      <article class="c-work-single--full-width">
        <div class="c-work-single__column">
          <figure class="c-work-single__figure">
            <carousel
              :nav="false"
              :dots="true"
              :responsive="{0:{items:1},600:{items:1},768:{items:1}}"
            >
              <template slot="prev" class="bob">
                <span class="prev"></span>
              </template>
              <div v-for="item in gallery" :key="item">
                <img :src="item" class="c-work-single__image" v-on:click="handleItemClick(item)" />
              </div>
              <template slot="next" class="bob">
                <span class="next"></span>
              </template>
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
            <h2 class="c-works__title">
              <span v-html="work.title"></span>
            </h2>
            <h2 class="c-works__title">
              <span v-html="work.subPostTitle"></span>
            </h2>
            <div class="c-works__name">{{ work.fullName }}</div>
            <div class="c-works__date">{{ work.date }}</div>
            <div class="c-works__medium">
              <span v-html="work.mediumText"></span>
            </div>
            <div class="c-works__dimensions">
              <span v-html="work.dimensions"></span>
            </div>
            <div class="c-works__price">
              <span v-html="formattedPrice"></span>
            </div>

            <div class="c-works__availability">
              <span v-if="work.sold === 'sold'" class="c-sale-marker c-sale-marker--sold"></span>
              <span v-if="work.sold === 'hold'" class="c-sale-marker c-sale-marker--hold"></span>
              <span v-if="work.sold === 'available'" class="c-sale-marker c-sale-marker--available"></span>
              <span class="c-sale-marker__copy" v-html="formattedSold"></span>
            </div>

            <span v-bind:hidden="work.hidePurchaseButton ? true : false">
              <button
                v-if="work.sold === 'sold' "
                id="purchaseBtn_0"
                data-id="purchaseBtn"
                class="c-button c-button--dark"
                disabled
              >Purchase</button>
              <button
                v-if="work.sold === 'hold' "
                id="purchaseBtn_0"
                data-id="purchaseBtn"
                class="c-button c-button--dark"
                disabled
              >Purchase</button>
              <button
                v-if="work.sold === 'available' "
                id="purchaseBtn_0"
                data-id="purchaseBtn"
                class="c-button c-button--dark"
              >Purchase</button>
            </span>

            <button
              id="inquireBtn_0"
              data-id="inquireBtn"
              class="c-button c-button--light"
            >Inquire to learn more</button>

            <span class="c-works__href-wrap c-works__href-wrap--back c-works__href-wrap--center">
              <svg class="u-icon c-works__icon c-works__icon--back">
                <use
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  xlink:href="#shape-link-arrow-white"
                  viewBox="0 0 32 32"
                />
              </svg>
              <a href="javascript:history.go(-1);" class="c-works__href">Back</a>
            </span>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";

const formatter = new Intl.NumberFormat("en-US", {
  style: "currency",
  currency: "USD",
  currencyDisplay: "code",
  useGrouping: true,
  minimumFractionDigits: 2
});

export default {
  components: {
    carousel
    //CookieLaw
  },
  data() {
    return {
      currentSlide: 0,
      totalSlideNumber: 0,
      currentImage: "",
      showLightbox: false
    };
  },
  props: {
    gallery: Array,
    work: Object
  },
  computed: {
    formattedSold() {
      if (this.work.sold == "available") {
        return "Available";
      } else if (this.work.sold == "hold") {
        return "Hold";
      } else {
        return "Sold";
      }
    },
    formattedPrice() {
      if (this.work.sold == "available") {
        if (this.work.price) {
          return this.work.price;
        } else {
          return "";
        }
      }
    }
  },
  methods: {
    handleItemClick(item) {
      this.currentImage = item;
      this.showLightbox = true;
    },
    previous() {
      if (this.currentSlide > 0) {
        this.currentSlide -= 1;
      } else if (this.loop) {
        this.currentSlide = this.totalSlideNumber - 1;
      }
    },
    next() {
      if (this.currentSlide < this.totalSlideNumber - 1) {
        this.currentSlide += 1;
      } else if (this.loop) {
        this.currentSlide = 0;
      }
    }
  }
};
</script>