let Rellax = require("rellax");

var rellax = new Rellax(".rellax", {
  speed: 2,
  center: false,
  wrapper: null,
  round: true,
  vertical: true,
  horizontal: false,
  //wrapper: ".rellax-wrapper",
  // center: true
  callback: function(position) {
    // callback every position change
    //console.log(position);
    //videoParalax();
  },
  breakpoints: [375, 678, 1024, 1280],
});

// 'phone': 375px,
//   'tablet': 768px,
//     'desktop': 1024px,
//       'desktop-large': 1280px

// var rellaxFixed = new Rellax(".rellax-fixed", {
//   speed: -2,
//   center: true,
//   wrapper: null,
//   round: true,
//   vertical: true,
//   horizontal: false,
//   container: 'rellax-container',
//   // center: true
//   callback: function (position) {
//     // callback every position change
//     //console.log(position);
//   },
//   breakpoints: [576, 768, 1024],
// });

// var rellax = new Rellax(".rellax", {
//   // add "blur" as you scroll down
//   callback: function(instance) {
//     var visiblePercentage = 1,
//       topOffset = instance.blocks[0].node.getBoundingClientRect().top, // pixels which aren't visible anymore
//       parentHeight = instance.blocks[0].node.clientHeight;

//     if (topOffset < 0)
//       visiblePercentage = (parentHeight + topOffset) / parentHeight;

//     instance.blocks[0].node.style.filter = `blur(${10 -
//       10 * visiblePercentage}px)`;
//   },
// });

// var waypoint = new Waypoint({
//   element: document.getElementById("video"),
//   handler: function() {},
// });

// function videoParalax() {
//   let w = window;
//   let intViewportHeight = window.innerHeight;
//   let distance = 1;
//   let top = w.scrollY / 1500;

//   console.log(intViewportHeight / 2);

//   //'transform': 'scale(' + (1.5 - $(window).scrollTop() / 250) + ')'

//   const videoElems = [...document.querySelectorAll("[data-id='video']")];
//   const videoElemsLength = videoElems.length;

//   for (let index = 0; index < videoElemsLength; index++) {
//     const videoElem = videoElems[index];
//     //videoElem.style.transform = `translate3d(0, ${top / distance}px, 0)`;
//     //videoElem.style.width = `${top / distance}px`;
//     videoElem.style.transform = `scale(${top / distance})`;
//   }
// }
