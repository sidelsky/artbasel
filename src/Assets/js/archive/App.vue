<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
        <VFilter :filters="filters" @input="handleFilterClick"/>
        <VWorkList :works="filteredWorks" v-if="filteredWorks.length" />
        <h3 v-else>No results found..</h3>
      </div>
  </div>
</template>
<script>
import VWorkList from './components/VWorkList'
import VFilter from './components/VFilter'

export default {
  data () {
    return {
      selectedFilters: {}
    }
  },
  methods: {
    isEmpty (obj) {
      for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
          return false
        }
      }
      return true
    },
    handleFilterClick (payload) {
      let { type, key } = payload
      this.$set(this.selectedFilters, type, key)
    }
  },
  computed: {
    filteredWorks () {
      let filters = this.selectedFilters

      if (!this.isEmpty(filters)) {
        return this.works.filter(work => {
          let filterConditions = []
          for (let key in filters) {
            let render = work[key] === filters[key] || filters[key] === 'all' ? true : false
            filterConditions.push(render)
          }

          if (filterConditions.includes(false)) {
            return false
          } else {
            return true
          }
        })
      } else {
        return this.works
      }
    }
  },
  props: {
    works: Array,
    filters: Object
  },
  components: {
    VWorkList,
    VFilter
  }
}
</script>
