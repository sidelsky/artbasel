<template lang="html">
  <div class="c-works">
    <VCard
      v-for="work in works"
      :key="work.title"
      :title="work.title"
      :date="work.date"
      :medium="work.medium"
      :dimensions="work.dimensions"
      :image="work.image"
      :price="work.price"
      :link="work.link"
    />
  </div>
</template>

<script>
import VCard from './VCard'

export default {
  props: {
    works: Array
  },
  components: {
    VCard
  }
}
</script>
