<template lang="html">
  <div class="c-works">
    <VCard
      v-for="work in paginatedData"
      :key="work.title"
      :title="work.title"
      :date="work.date"
      :medium="work.medium"
      :dimensions="work.dimensions"
      :image="work.image"
      :price="work.price"
      :link="work.link"
    />

    <div class="c-pagination">
      <button @click="prevPage" :disabled="pageNumber === 0">Previous</button>
      <button @click="nextPage" :disabled="pageNumber >= pageCount -1">Next</button>
    </div>
  </div>
</template>

<script>
import VCard from './VCard'

export default {
  components: {
    VCard
  },
  props: {
    works: Array
  },
  data () {
    return {
      pageNumber: 0,
      size: 9
    }
  },
  methods: {
    nextPage () {
      this.pageNumber += 1
    },
    prevPage () {
      this.pageNumber -= 1
    }
  },
  computed: {
    paginatedData () {
      const start = this.pageNumber * this.size
      const end = start + this.size
      return this.works.slice(start, end)
    },
    pageCount () {
      let l = this.works.length
      let s = this.size
      return Math.ceil(l / s)
    }
  }
}
</script>
