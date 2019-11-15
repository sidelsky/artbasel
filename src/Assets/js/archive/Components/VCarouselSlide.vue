<template>
  <div
    :class="[
      'v-m-carousel-slide'
    ]">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'VCarouselSlide',
  props: {
  }
}
</script>

<style lang="scss">
.v-m-carousel-slide {
  //padding: 1rem;
  user-select: none;

  img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center top;
  }
}
</style>
