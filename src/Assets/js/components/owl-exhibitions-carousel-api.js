const CAROUSEL = document.querySelectorAll("[data-id='exhibitions-carousel']");
const CAROUSELLENGTH = CAROUSEL.length;

if (CAROUSELLENGTH) {
  const NEXT = document.getElementById("next-slide");
  const PREV = document.getElementById("prev-slide");

  $(".owl-exhibitions-carousel").owlCarousel({
    loop: false,
    margin: 30,
    dots: false,
    //nav: true,
    //dotsContainer: '#myDots',
    //navContainer: "#MyNavs",
    responsive: {
      0: {
        items: 1,
      },
      600: {
        items: 2,
      },
      1000: {
        items: 3,
      },
    },
  });

  NEXT.addEventListener("click", () => {
    $(".owl-exhibitions-carousel").trigger("next.owl.carousel");
  });

  PREV.addEventListener("click", () => {
    $(".owl-exhibitions-carousel").trigger("prev.owl.carousel");
  });
}
