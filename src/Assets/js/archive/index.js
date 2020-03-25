import Vue from 'vue'
//import WorksPage from './WorksPage.vue'
import WorkSinglePage from './WorkSinglePage.vue'
Vue.config.productionTip = false
Vue.config.devtools = true

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