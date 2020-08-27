const owlCarMag = $(".owl-carousel-magnify");

if (owlCarMag) {
  // Initiate carousel

  owlCarMag.owlCarousel({
    items: 1,
    loop: true,
    margin: 30,
    dots: false,
    nav: false,
    touchDrag: false,
    onTranslated: function() {
      // Update Magnify when slide changes
      $zoom.destroy().magnify();
    },
  });

  // Initiate zoom
  var $zoom = $(".zoom").magnify({
    speed: 200,
    zoom: 1,
  });

  const next = document.getElementById("next-slide");
  const prev = document.getElementById("prev-slide");

  if (next) {
    next.addEventListener("click", () => {
      owlCarMag.trigger("next.owl.carousel");
    });
  }

  if (prev) {
    prev.addEventListener("click", () => {
      owlCarMag.trigger("prev.owl.carousel");
    });
  }
}
