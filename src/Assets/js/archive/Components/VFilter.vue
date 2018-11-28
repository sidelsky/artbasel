<template>

      <div class="c-meta__data">
          <form>
              <div class="c-meta__section">
                <h3 class="c-meta__title">Sort By</h3>
                <p>
                  <label class="c-meta__label">
                    <input
                      class="c-meta__radio"
                      type="radio"
                      checked
                      name="sortby"
                      value="artist-a-z"
                      @change="$emit('order', { order: 'asc', key: 'surname' })"
                    >
                    <span>Artist A-Z</span>
                  </label>
                </p>
                <p>
                  <label class="c-meta__label">
                    <input
                      class="c-meta__radio"
                      type="radio"
                      name="sortby"
                      value="price-low-to-high"
                      @change="$emit('order', { order: 'asc', key: 'price' })"
                    >
                    <span>Price: low to high</span>
                  </label>
                </p>
                <p>
                  <label class="c-meta__label">
                    <input
                      class="c-meta__radio"
                      type="radio"
                      name="sortby"
                      value="price-high-to-low"
                      @change="$emit('order', { order: 'desc', key: 'price' })"
                    >
                    <span>Price: high to low</span>
                  </label>
                </p>
              </div>

              <div class="c-meta__section">
                <h3 class="c-meta__title">By Medium</h3>

                <p v-for="(item, index) in filters.medium" :key="item.label">
                  <label class="c-meta__label">
                    <input class="c-meta__radio"
                    type="radio"
                    name="bymedium"
                    :checked="index === 0"
                    :value="item.value"
                    @change="$emit('input', { type: 'medium', key: item.value })
                  ">
                    <span>{{ item.label }}</span>
                  </label>
                </p>
              </div>

              <div class="c-meta__section">
                <h3 class="c-meta__title">By Price</h3>
                <p v-for="(item, index) in filters.priceRange" :key="item.label">
                  <label class="c-meta__label">
                    <input class="c-meta__radio"
                    type="radio"
                    name="byprice"
                    :checked="index === 0"
                    :value="item.value"
                    @change="$emit('input', { type: 'priceRange', key: item.value })
                  ">
                    <span>{{ item.label }}</span>
                  </label>
                </p>
              </div>

              <div class="c-meta__section">
                <h3 class="c-meta__title">By Decade</h3>
                <p v-for="(item, index) in filters.decade" :key="item.label">
                  <label class="c-meta__label">
                    <input class="c-meta__radio"
                    type="radio"
                    name="bydecade"
                    :checked="index === 0"
                    :value="item.value"
                    @change="$emit('input', { type: 'decade', key: item.value })
                  ">
                    <span>{{ item.label }}</span>
                  </label>
                </p>
              </div>
          </form>
      </div>
</template>

<script>
export default {
  props: {
    filters: Object
  }
}
</script>
