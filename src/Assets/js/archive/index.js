import Vue from 'vue'
import App from './App.vue'

if (window.WORKS) {

  Vue.config.productionTip = false

  new Vue({
    components: { App },
    template: '<App :works="works" />',
    data () {
      return {
        works: window.WORKS
      }
    }
  }).$mount('#app')
}
