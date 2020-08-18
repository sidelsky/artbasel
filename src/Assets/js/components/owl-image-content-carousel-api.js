const carousel = $(".owl-image-content-carousel");
const carousels = [...carousel];
const carouselsLength = carousels.length;

const jqZoomItem = $(".jq-zoom");

carousel.owlCarousel({
  loop: true,
  dots: false,
  items: 1,
  nav: true,
  lazyLoad: true,
  autoHeight: false,
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

    let goFullScreen = () => {
      fullScreenButton.parentNode.classList.add("modal-active");
      fullScreenButton.style.display = "none";
      closeFullScreenButton.style.display = "block";
      jqZoomItem.removeClass("active");
      removeContent();
      carousel.trigger("refresh.owl.carousel");
    };

    let closeFullScreen = () => {
      fullScreenButton.parentNode.classList.remove("modal-active");
      fullScreenButton.style.display = "block";
      closeFullScreenButton.style.display = "none";
      addContent();
      carousel.trigger("refresh.owl.carousel");
    };

    //Full screen click
    fullScreenButton.addEventListener("click", () => {
      goFullScreen();
    });

    //Close full screen click
    closeFullScreenButton.addEventListener("click", () => {
      closeFullScreen();
    });

    //detect Escape key press
    document.addEventListener("keydown", function(event) {
      if (event.keyCode === 27) {
        closeFullScreen();
      }
    });
  }
}

jqZoomItem.on("click", function() {
  $(this).toggleClass("active");
});

jqZoomItem.zoom({
  on: "click",
});
