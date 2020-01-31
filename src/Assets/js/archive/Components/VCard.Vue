<template> 
  <article class="c-works__card">
    <figure class="c-works__figure">
      <a :href="link">
        <img :src="image" :alt="title" class="c-works__image">
      </a>
    </figure>
    <a :href="link">
      <h2 class="c-works__title" v-html="title"></h2>
    </a>
    <h2 class="c-works__title" v-html="subPostTitle"></h2>
    <div class="c-works__name">{{ fullName }}</div>
    <div class="c-works__date">{{ date }}</div>
    <div class="c-works__medium"><span v-html="mediumText"></span></div>
	  <div class="c-works__price"><span v-html="formattedPrice"></span></div>

    <div class="c-works__availability">
      <span
      :class="[
              'c-sale-marker',
              `${ sold ? 'c-sale-marker--sold' : 'c-sale-marker--available'}`
            ]"
      ></span><span v-html="formattedSold"></span>
    </div>

		<button
      id="purchaseBtn_<?= $index ?>" 
      :disabled="sold"
			class="c-button c-button--light"
		>Purchase</button>

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
    fullName: String,
    medium: String,
    mediumChinese: String,
    dimensions: String,
    image: String,
    price: String,
    sold: Boolean,
    link: String,
    mediumText: String,
    ids: String
  },
    computed: {
    formattedSold() {
      if( this.sold != 1 ) {
        return 'Available'
      } else {
        return 'Sold'
      }
    },
    formattedPrice() {
      if( this.sold != 1 ) {
		  return this.price 
      } else {
		  return "&nbsp;"
	  }
    }
  }
}
</script>