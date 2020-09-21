var Isotope = require("isotope-layout");

// const grids = [...document.querySelectorAll("[data-isotope]")];
// const gridsLength = grids.length;
// console.log(gridsLength);

// document.addEventListener('click', function(event) {
//     if (event.target.id == 'my-id') {
//       callback();
//     }
// });
// function callback(){
//    ...handler code here
// }

// for (let index = 0; index < gridsLength; index++) {
//   const grid = grids[index];
//   console.log(grid);
// }

var grid = new Isotope("[data-isotope]", {
  itemSelector: ".filter-item",
  layoutMode: "fitRows",
  getSortData: {
    name: ".name",
  },
});

function isMobile() {
  return (
    navigator.userAgent.match(/Android/i) ||
    navigator.userAgent.match(/webOS/i) ||
    navigator.userAgent.match(/iPhone/i) ||
    navigator.userAgent.match(/iPod/i) ||
    navigator.userAgent.match(/iPad/i) ||
    navigator.userAgent.match(/BlackBerry/)
  );
}

// Function to check if filters have some results
function checkResults() {
  var visibleItemsCount = grid.filteredItems.length;
  if (visibleItemsCount > 0) {
    $(".c-filters__no-results").hide();
  } else {
    $(".c-filters__no-results").show();
  }
}

// store filter for each group
var filters = {};

var $resetBtn = $(".c-filter__container--clear");
//$resetBtn.hide();

/**
 * Filter
 */
$(".filters").on("click", ".item", function(event) {
  var $this = $(this);
  // get group key
  var $buttonGroup = $this.parents(".button-group");
  var filterGroup = $buttonGroup.attr("data-filter-group");

  // set filter for group
  filters[filterGroup] = $this.attr("data-filter");

  // combine filters
  var filterValue = concatValues(filters);

  // set filter for Isotope
  grid.arrange({
    filter: filterValue,
  });

  checkResults();

  // if (!isMobile() && $(window).width() > 768) {
  //   //$resetBtn.show();
  // }
});

/**
 * Sort
 */
$("#sorts").on("click", "span", function() {
  /* Get the element name to sort */
  var sortValue = $(this).attr("data-sort-value");

  /* Get the sorting direction: asc||desc */
  var sortDirection = $(this).attr("data-sort-direction");

  /* convert it to a boolean */
  sortDirection = sortDirection == "asc";

  /* pass it to isotope */
  grid.arrange({
    sortBy: sortValue,
    sortAscending: sortDirection,
  });
  //$resetBtn.show();
  if (!isMobile() && $(window).width() > 768) {
    $resetBtn.show();
  }
});

/**
 * Reset filters
 */
$(".button--reset").on("click", function() {
  // reset filters
  filters = {};
  grid.arrange({
    filter: "*",
  });
  $(".c-filter__container--clear").hide();
  $(".c-filters__no-results").hide();
});

// flatten object by concatting values
function concatValues(obj) {
  var value = "";
  for (var prop in obj) {
    value += obj[prop];
  }
  return value;
}
