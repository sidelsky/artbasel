<template lang="html">
  <div class="c-works">
    <VCard
      v-for="work in works"
      :title="work.title"
      :date="work.date"
      :medium="work.medium"
      :dimensions="work.dimensions"
      :image="work.iamge"
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
  }
}
</script>
