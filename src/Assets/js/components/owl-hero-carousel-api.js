$(".owl-hero-carousel").owlCarousel({
  loop: true,
  margin: 0,
  nav: true,
  responsiveClass: true,
  autoplay: false,
  autoplayTimeout: 5000,
  autoplayHoverPause: false,
  responsive: {
    0: {
      items: 1,
      nav: true,
    },
    600: {
      items: 1,
      nav: false,
    },
    1000: {
      items: 1,
      nav: true,
      loop: false,
    },
  },
});
