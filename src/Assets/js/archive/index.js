import Vue from 'vue'
import WorksPage from './WorksPage.vue'
import WorkSinglePage from './WorkSinglePage.vue'
Vue.config.productionTip = false


if (window.WORKS) {
  new Vue({
    components: {
      WorksPage
    },
    template: '<WorksPage :works="works" :filters="filters" />',
    data() {
      return {
        works: window.WORKS,
        filters: window.FILTERS
      }
    }
  }).$mount('#app')
}

if (window.GALLERY) {
  new Vue({
    components: {
      WorkSinglePage
    },
    template: '<WorkSinglePage :gallery="gallery" :work="work" />',
    data() {
      return {
        gallery: window.GALLERY,
        work: window.WORK
      }
    }
  }).$mount('#app')
}