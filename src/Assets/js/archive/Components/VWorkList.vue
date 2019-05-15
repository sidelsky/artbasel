<template lang="html">
  <div class="c-works">

    <div class="c-works__list" name="fade" ref="cardlist">
      <VCard
        v-for="work in list"
        :key="work.id"
        :surname="work.surname"
        :title="work.title"
        :subPostTitle="work.subPostTitle"
        :date="work.date"
        :medium="work.medium"
        :mediumChinese="work.mediumChinese"
        :dimensions="work.dimensions"
        :image="work.image"
        :price="work.price"
        :sold="work.sold"
        :link="work.link"
        :mediumText="work.mediumText"
      />
    </div>

    <div class="c-pagination c-pagination--bottom" v-if="!allLoaded">
      <div class="c-button" @click="loadMore">Load More</div>
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
      pageNumber: 1,
      size: 12,
      list: []
    }
  },
  watch: {
    works (newVal) {
      this.pageNumber = 0
    }
  },
  mounted () {
    this.loadMore()
    this.listOffset = getCoords(this.$refs.cardlist)
  },
  methods: {
    loadMore () {
      if (this.allLoaded) return
      const chunk = this.size * this.pageNumber
      this.list = this.works.slice(0, chunk)
      this.pageNumber += 1
    }
  },
  computed: {
    allLoaded () {
      return this.list.length === this.works.length
    }
  }
}
</script>
