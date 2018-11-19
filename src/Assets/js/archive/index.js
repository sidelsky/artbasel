import Vue from 'vue'
import App from './App.vue'

if (window.WORKS) {

  Vue.config.productionTip = false

  new Vue({
    components: { App },
    template: '<App :works="works" :filters="filters" />',
    data () {
      return {
        works: window.WORKS,
        filters: window.FILTERS
      }
    }
  }).$mount('#app')
}
