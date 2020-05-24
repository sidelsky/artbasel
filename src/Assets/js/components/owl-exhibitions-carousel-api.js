const carousel = document.querySelectorAll("[data-id='exhibitions-carousel']");
const carouselLength = carousel.length;

if (carouselLength) {
  const next = document.getElementById("next-slide");
  const prev = document.getElementById("prev-slide");

  const $carousel = $(".owl-exhibitions-carousel");

  $carousel.owlCarousel({
    loop: false,
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

  next.addEventListener("click", () => {
    $(".owl-exhibitions-carousel").trigger("next.owl.carousel");
  });

  prev.addEventListener("click", () => {
    $(".owl-exhibitions-carousel").trigger("prev.owl.carousel");
  });
}
