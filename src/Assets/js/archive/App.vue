<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
        <VFilter
          :filters="filters"
          @input="handleFilterClick"
          @order="handleSortClick"
        />
        <VWorkList
          :works="sortedWorks"
          :pageNumberOverride="pageNumberOverride"
          v-if="filteredWorks.length"
        />
        <h3 v-else>No results found..</h3>
      </div>
  </div>
</template>

<script>
import _ from 'lodash'
import VWorkList from './components/VWorkList'
import VFilter from './components/VFilter'

export default {
  data () {
    return {
      pageNumberOverride: null,
      selectedFilters: {},
      sort: {
        order: 'asc',
        key: 'title'
      }
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
    handleSortClick (payload) {
      let { order, key } = payload
      this.sort = payload
      this.pageNumberOverride = 0
    },
    handleFilterClick (payload) {
      let { type, key } = payload
      this.$set(this.selectedFilters, type, key)
      this.pageNumberOverride = 0
    }
  },
  computed: {
    calculatedWorks () {
      return this.works.map(work => {
        return {
          ...work,
          price: parseInt(work.price, 10)
        }
      })
    },
    filteredWorks () {
      let filters = this.selectedFilters

      if (!this.isEmpty(filters)) {
        return this.calculatedWorks.filter(work => {
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
        return this.calculatedWorks
      }
    },
    sortedWorks () {
      let sortedWorks = _.sortBy(this.filteredWorks, this.sort.key)

      if (this.sort.order === 'desc') {
        return _.reverse(sortedWorks)
      }

      return sortedWorks
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
