<template>
  <article class="c-works__card">
    <figure class="c-works__figure">
      <a :href="link">
        <img :src="image" :alt="title" class="c-works__image">
      </a>
    </figure>
    <h2 class="c-works__title" v-html="title"></h2>
    <h2 class="c-works__title" v-html="subPostTitle"></h2>
    <div class="c-works__date">{{ date }}</div>
    <!-- <div class="c-works__medium"><span v-html="mediumText"></span></div> -->
    <!-- <div class="c-works__medium"><span v-html="mediumChinese"></span></div> -->
    <!-- <div class="c-works__dimensions"><span v-html="dimensions"></span></div> -->
    <div class="c-works__price">
      <span
      :class="[
              'c-sold',
              `${ sold ? 'c-sold--active' : ''}`
            ]"
      ></span>{{ formattedPrice }}</div>
    <span class="c-works__href-wrap"><a :href="link" class="c-works__href">Learn more</a><svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use></svg></span>
    <span class="c-works__href-wrap">
      <a
        :href="`mailto:viewingroom@hauserwirth.com?subject=Inquire to purchase - ${title}&body=Hello, I'd like to inquire about: ${title}`"
        class="c-works__href"
      >Inquire to purchase</a>
      <svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use></svg>
    </span>
  </article>
</template>

<script>

const formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  currencyDisplay: 'code',
  useGrouping: true,
  minimumFractionDigits: 2
})

export default {
  props: {
    title: String,
    subPostTitle: String,
    date: String,
    medium: String,
    mediumChinese: String,
    dimensions: String,
    image: String,
    price: Number,
    sold: Boolean,
    link: String,
    mediumText: String
  },
  computed: {
    formattedPrice() {
      if( this.sold != 1 ) {
      
        if ( this.price ) {
          return formatter.format(this.price)
        } else {
          return ''
        }

      } else {
        return 'Sold'
      }
    }
  }
}
</script>
