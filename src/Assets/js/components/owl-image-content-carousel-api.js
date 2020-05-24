const carousel = $(".owl-image-content-carousel");
const carousels = [...carousel];
const carouselsLength = carousels.length;

carousel.owlCarousel({
  loop: true,
  dots: false,
  items: 1,
  nav: true,
  lazyLoad: true,
  onInitialize: hasBeenInitialized,
  navText: [
    "<div id='prev-slide' class='c-online-exhibitions__btn-prev'></div>",
    "<div id='next-slide' class='c-online-exhibitions__btn-next'></div>",
  ],
});

function hasBeenInitialized() {
  const fullScreenButtons = [
    ...document.querySelectorAll("[data-id='fullScreenBtn']"),
  ];
  const fullScreenButtonsLength = fullScreenButtons.length;

  const closeFullScreenButtons = [
    ...document.querySelectorAll("[data-id='closefullscreenBtn']"),
  ];

  const carouselContents = [
    ...document.querySelectorAll("[data-id='carousel-content']"),
  ];

  const carouselContentsLength = carouselContents.length;

  for (let index = 0; index < fullScreenButtonsLength; index++) {
    const fullScreenButton = fullScreenButtons[index];
    const closeFullScreenButton = closeFullScreenButtons[index];

    const addContent = () => {
      // Loop over ALL carousels to find class
      for (let index = 0; index < carouselContentsLength; index++) {
        const carouselContent = carouselContents[index];
        carouselContent.classList.add("carousel-is-active");
        carouselContent.classList.remove("active-height");
      }
    };

    const removeContent = () => {
      // Loop over ALL carousels to find class
      for (let index = 0; index < carouselContentsLength; index++) {
        const carouselContent = carouselContents[index];
        carouselContent.classList.remove("carousel-is-active");
        carouselContent.classList.add("active-height");
      }
    };

    addContent();

    //Full screen click
    fullScreenButton.addEventListener("click", () => {
      fullScreenButton.parentNode.classList.add("modal-active");
      fullScreenButton.style.display = "none";
      closeFullScreenButton.style.display = "block";
      removeContent();
      carousel.trigger("refresh.owl.carousel");
    });

    //Close full screen click
    closeFullScreenButton.addEventListener("click", () => {
      fullScreenButton.parentNode.classList.remove("modal-active");
      fullScreenButton.style.display = "block";
      closeFullScreenButton.style.display = "none";
      addContent();
      carousel.trigger("refresh.owl.carousel");
    });
  }
}
