<template lang="html">
  <div class="c-works">

    <div class="c-works__list" name="fade" ref="cardlist">
      <VCard
        v-for="work in paginatedData"
        :key="work.id"
        :surname="work.surname"
        :title="work.title"
        :date="work.date"
        :medium="work.medium"
        :dimensions="work.dimensions"
        :image="work.image"
        :price="work.price"
        :link="work.link"
        :mediumText="work.mediumText"
      />
    </div>

    <div class="c-pagination c-pagination--bottom">
      <button @click="prevPage" :disabled="pageNumber === 0">Previous</button>
      <span>{{pageNumber + 1}}/{{ pageCount }}</span>
      <button @click="nextPage" :disabled="pageNumber >= pageCount -1">Next</button>
    </div>
  </div>
</template>

<script>
import VCard from './VCard'
import getCoords from '../../helpers/get-coords'

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
      listOffset: 0,
      size: 12
    }
  },
  watch: {
    works (newVal) {
      this.pageNumber = 0
    }
  },
  mounted () {
    this.listOffset = getCoords(this.$refs.cardlist)
  },
  methods: {
    nextPage () {
      window.scrollTo(0, this.listOffset.top - 100)
      this.pageNumber += 1
    },
    prevPage () {
      window.scrollTo(0, this.listOffset.top - 100)
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
