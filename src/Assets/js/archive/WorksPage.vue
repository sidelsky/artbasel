<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
        <!-- to go here -->
        <div class="c-meta">
          <button class="c-meta__filter-button" @click="showFilter = !showFilter">
            <span v-if="showFilter">Hide Filter</span>
            <span v-else>Show Filter</span>
          </button>
          <VFilter
            :filters="filters"
            @input="handleFilterClick"
            @order="handleSortClick"
            v-show="showFilter"
          />
        </div>

        <VWorkList
          :works="sortedWorks"
          v-if="filteredWorks.length"
        />
        <h3 v-else>No results found.</h3>
      </div>
      
      <!-- Cookie Law -->
      <cookie-law theme="hauser-theme"> 
        <div slot-scope="props" class="c-cookiepop">
          <p>
            This site uses cookies to improve user experience. By clicking 'Accept' or by continuing to use this site, you consent to our use of cookies. Click ‘Learn more’ for information on how we use cookies and how you can control them.
          </p>
          <div class="hw-button-wrapper">
            <button class="skew" @click="props.close"><span><a href="https://www.vip-hauserwirth.com/site-terms-of-use/">Learn more</a></span></button>
          </div>
          <div class="hw-button-wrapper">
            <button class="skew" @click="props.accept"><span>I accept</span></button>
          </div>
        </div>
      </cookie-law>

  </div>
</template>

<script>
import _ from 'lodash'
import VWorkList from './components/VWorkList'
import VFilter from './components/VFilter'
import CookieLaw from 'vue-cookie-law'

export default {
  data () {
    return {
      selectedFilters: {},
      showFilter: false,
      sort: {
        order: 'asc',
        key: 'surname'
      }
    }
  },
  mounted () {

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
    },
    handleFilterClick (payload) {
      let { type, key } = payload
      this.$set(this.selectedFilters, type, key)
    }
  },
  computed: {
    calculatedWorks () {
      return this.works.map(work => {
        return {
          ...work,
          //price: parseInt(work.price, 10)
        }
      })
    },
    filteredWorks () {
      let filters = this.selectedFilters
      let sort = this.sort

      if (!this.isEmpty(filters)) {
        return this.calculatedWorks.filter(work => {
          let filterConditions = []
          for (let key in filters) {
            let render = work[key] === filters[key] || filters[key] === 'all' ? true : false

            if (key === 'priceRange' && filters[key] !== 'all' && !work.price) {
              render = false
            }

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
        sortedWorks = _.reverse(sortedWorks)
      }

      sortedWorks = sortedWorks.filter(work => {
        if (this.sort.key === 'price' && !work.price) {
          return false
        }

        return true
      })

      return sortedWorks
    }
  },
  props: {
    works: Array,
    filters: Object
  },
  components: {
    VWorkList,
    VFilter,
    CookieLaw
  }
}
</script>
