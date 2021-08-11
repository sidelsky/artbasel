
document.addEventListener("DOMContentLoaded", function(event) {
  document.querySelector('body').insertAdjacentHTML('afterend', '<style>.elementor-widget-container>.dzsap--elementor-widget, .elementor-widget-container>.dzsap-playlist--elementor-widget{ pointer-events: none; } </style>')
  setInterval(()=>{
    if(jQuery && window.dzsap_init_allPlayers){
      window.dzsap_init_allPlayers(jQuery);
      window.dzsag_init('.audiogallery.auto-init', {
        init_each: true
      });
    }
  },2000);
});