$(document).ready(function() {
  $(".owl-carousel-home").owlCarousel({
    margin: 20,
    center: true,
    dots: false,
    nav: true,
    responsiveClass: true,
    lazyLoad: false,
    loop: true,
    autoHeight: true,
    onInitialize: hasBeenInitialized,
    navText: [
      "<div class='nav-btn prev-slide'></div>",
      "<div class='nav-btn next-slide'></div>"
    ],
    responsive: {
      0: {
        items: 1
      },

      600: {
        items: 1
      },

      1024: {
        items: 1
      },

      1366: {
        items: 1
      }
    }
  });

  function hasBeenInitialized() {
    $("body").addClass("owl-is-loaded");
  }
});
