let Rellax = require("rellax");
var rellax = new Rellax(".rellax", {
  speed: -2,
  center: true,
  wrapper: null,
  round: true,
  vertical: true,
  horizontal: false,
  // center: true
  callback: function(position) {
    // callback every position change
    //console.log(position);
  },
  breakpoints: [576, 768, 1024],
});

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
