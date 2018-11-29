<template>
  <article class="c-works__card">
    <figure class="c-works__figure">
      <a :href="link">
        <img :src="image" :alt="title" class="c-works__image">
      </a>
    </figure>
    <h2 class="c-works__title">{{ title }}</h2>
    <div class="c-works__date">{{ date }}</div>
    <div class="c-works__medium">{{ mediumText }}</div>
    <div class="c-works__dimensions">{{ dimensions }}</div>
    <div class="c-works__price">{{ formattedPrice }}</div>
    <span class="c-works__href-wrap"><a :href="link" class="c-works__href">Learn more</a><svg class="u-icon c-works__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#shape-link-arrow" viewBox="0 0 32 32"></use></svg></span>
    <span class="c-works__href-wrap">
      <a
        :href="`mailto:viewingroom@hauserwirth.com?subject=Inquire to purchase - ${title}&body=Hello, I'd to inquire about ${title}`"
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
  minimumFractionDigits: 2
})

export default {
  props: {
    title: String,
    date: String,
    medium: String,
    dimensions: String,
    image: String,
    price: Number,
    link: String,
    mediumText: String
  },
  computed: {
    formattedPrice() {
      if( this.price ) {
      return formatter.format(this.price)
      } else {
        return 'Price on request'
      }
    }
  }
}
</script>
