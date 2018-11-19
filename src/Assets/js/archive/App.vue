<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
          <!-- START: Meta Column -->
          <div class="c-meta">
              <div class="c-meta__data">
                  <form action="">
                      <div class="c-meta__section">
                        <h3 class="c-meta__title">Sort By</h3>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="artist-a-z"><span>Artist A-Z</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="price-low-to-high"><span>Price: low to high</span></label></p>
                        <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="price-high-to-low"><span>Price: high to low</span></label></p>
                      </div>

                      <div class="c-meta__section">
                        <h3 class="c-meta__title">By Medium</h3>

                        <p v-for="(value, key) in filters.medium">
                          <label class="c-meta__label">
                            <input class="c-meta__radio" type="radio" name="bymedium" :value="key">
                            <span>{{ value }}</span>
                          </label>
                        </p>
                      </div>

                      <div class="c-meta__section">
                        <h3 class="c-meta__title">By Price</h3>
                        <p v-for="(value, key) in filters.priceRange">
                          <label class="c-meta__label">
                            <input class="c-meta__radio" type="radio" name="byprice" :value="key">
                            <span>{{ value }}</span>
                          </label>
                        </p>
                      </div>

                      <div class="c-meta__section">
                        <h3 class="c-meta__title">By Decade</h3>
                        <p v-for="(value, key) in filters.decade">
                          <label class="c-meta__label">
                            <input class="c-meta__radio" type="radio" name="bydecade" :value="key">
                            <span>{{ value }}</span>
                          </label>
                        </p>
                      </div>
                  </form>
              </div>
          </div>
          <!-- END: Meta Column -->
        <VWorkList :works="works" />
      </div>
  </div>
</template>
<script>
import VWorkList from './components/VWorkList'

export default {
  data () {
    return {
      filters: {
        medium: [
          {
            label: 'Painting',
            value: 'painting'
          }
        ],
        priceRange: [

        ],
        decade: [

        ]
      }
    }
  },
  props: {
    works: Array,
    filters: Object
  },
  components: {
    VWorkList
  }
}
</script>
