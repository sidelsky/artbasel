const carousel = $(".owl-image-content-carousel");
const carousels = [...carousel];
const carouselsLength = carousels.length;

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

  //End
}

/*! Zoom Image 1.0.0 | MIT *
 * https://github.com/jpcurrier/zoom-image !*/
(function($) {
  $.fn.zoomImage = function(options) {
    // default options
    var settings = $.extend(
      {
        touch: true,
      },
      options
    );

    return this.each(function() {
      var $image = $(this);

      if (settings.touch || !("ontouchstart" in document.documentElement)) {
        $image.on("mousemove", function(e) {
          // image + cursor data
          var bounds = {
              width: $image.outerWidth(),
              height: $image.outerHeight(),
            },
            xPercent = (e.pageX - $image.offset().left) / bounds.width,
            yPercent = (e.pageY - $image.offset().top) / bounds.height,
            zoom = new Image();
          zoom.src = $image
            .children()
            .css("background-image")
            .replace(/.*\s?url\([\'\"]?/, "")
            .replace(/[\'\"]?\).*/, "");

          var maxPan = {
            left: -(zoom.naturalWidth - bounds.width),
            top: -(zoom.naturalHeight - bounds.height),
          };

          $image.children().css({
            "background-position":
              xPercent * maxPan.left + "px " + yPercent * maxPan.top + "px",
          });
        });
      }
    });
  };
})(jQuery);

$(".zoom-image").zoomImage({
  touch: true,
});

// (function() {
//   // Get all images with the `detail-view` class
//   var zoomBoxes = document.querySelectorAll(".detail-view");

//   // Extract the URL
//   zoomBoxes.forEach(function(image) {
//     var imageCss = window.getComputedStyle(image, false),
//       imageUrl = imageCss.backgroundImage.slice(4, -1).replace(/['"]/g, "");

//     // Get the original source image
//     var imageSrc = new Image();

//     imageSrc.onload = function() {
//       var imageWidth = imageSrc.naturalWidth,
//         imageHeight = imageSrc.naturalHeight,
//         ratio = imageHeight / imageWidth;

//       // Adjust the box to fit the image and to adapt responsively
//       var percentage = ratio * 100 + "%";
//       image.style.paddingBottom = percentage;

//       // Zoom and scan on mousemove
//       image.onmousemove = function(e) {
//         // Get the width of the thumbnail
//         var boxWidth = image.clientWidth,
//           // Get the cursor position, minus element offset
//           x = e.pageX - this.offsetLeft,
//           y = e.pageY - this.offsetTop,
//           // Convert coordinates to % of elem. width & height
//           xPercent = x / (boxWidth / 100) + "%",
//           yPercent = y / ((boxWidth * ratio) / 100) + "%";

//         // Update styles w/actual size
//         Object.assign(image.style, {
//           backgroundPosition: xPercent + " " + yPercent,
//           backgroundSize: imageWidth + "px",
//         });
//       };

//       // Reset when mouse leaves
//       image.onmouseleave = function(e) {
//         Object.assign(image.style, {
//           backgroundPosition: "center",
//           backgroundSize: "cover",
//         });
//       };
//     };
//     imageSrc.src = imageUrl;
//   });
// })();
