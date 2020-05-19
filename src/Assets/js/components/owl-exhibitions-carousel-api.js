const CAROUSEL = document.querySelectorAll("[data-id='exhibitions-carousel']");
const CAROUSELLENGTH = CAROUSEL.length;

if (CAROUSELLENGTH) {
  const NEXT = document.getElementById("next-slide");
  const PREV = document.getElementById("prev-slide");

  $(".owl-exhibitions-carousel").owlCarousel({
    loop: true,
    margin: 30,
    dots: false,
    stagePadding: 40,
    responsive: {
      0: {
        items: 1,
        stagePadding: 20,
        margin: 20,
      },
      600: {
        items: 2,
        stagePadding: 20,
        margin: 20,
      },
      1000: {
        items: 3,
        margin: 30,
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
