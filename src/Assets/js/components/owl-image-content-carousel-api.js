const carousel = $(".owl-image-content-carousel");
const carousels = [...carousel];
const carouselsLength = carousels.length;

const fullScreenButtons = [
  ...document.querySelectorAll("[data-id='fullScreenBtn']"),
];
const fullScreenButtonsLength = fullScreenButtons.length;

const closeFullScreenButtons = [
  ...document.querySelectorAll("[data-id='closefullscreenBtn']"),
];

carousel.owlCarousel({
  loop: true,
  dots: false,
  items: 1,
  nav: true,
  navText: [
    "<div id='prev-slide' class='c-online-exhibitions__btn-prev'></div>",
    "<div id='next-slide' class='c-online-exhibitions__btn-next'></div>",
  ],
});

for (let index = 0; index < fullScreenButtonsLength; index++) {
  const fullScreenButton = fullScreenButtons[index];
  const closeFullScreenButton = closeFullScreenButtons[index];

  fullScreenButton.addEventListener("click", () => {
    fullScreenButton.parentNode.classList.add("modal-active");
    fullScreenButton.style.display = "none";
    closeFullScreenButton.style.display = "block";
    carousel.trigger("refresh.owl.carousel");
  });

  closeFullScreenButton.addEventListener("click", () => {
    fullScreenButton.parentNode.classList.remove("modal-active");
    fullScreenButton.style.display = "block";
    closeFullScreenButton.style.display = "none";
    carousel.trigger("refresh.owl.carousel");
  });
}
