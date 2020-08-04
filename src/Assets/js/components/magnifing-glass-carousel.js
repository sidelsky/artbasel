$(function() {
  const next = document.getElementById("next-slide");
  const prev = document.getElementById("prev-slide");

  // Initiate carousel
  $(".owl-carousel-magnify").owlCarousel({
    items: 1,
    loop: true,
    margin: 30,
    //stagePadding: 40,
    dots: false,
    nav: false,
    //  navText: [
    //    "<div class='nav-btn prev-slide'></div>",
    //    "<div class='nav-btn next-slide'></div>",
    //  ],

    onTranslated: function() {
      // Update Magnify when slide changes
      $zoom.destroy().magnify();
    },
  });
  // Initiate zoom
  var $zoom = $(".zoom").magnify();

  next.addEventListener("click", () => {
    $(".owl-carousel-magnify").trigger("next.owl.carousel");
  });

  prev.addEventListener("click", () => {
    $(".owl-carousel-magnify").trigger("prev.owl.carousel");
  });
});
