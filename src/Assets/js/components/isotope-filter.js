// external js: isotope.pkgd.js

//var Isotope = require("isotope-layout");

var Isotope = require("isotope-layout");

var $grid = new Isotope("[data-isotope]", {
  itemSelector: ".filter-item",
  layoutMode: "fitRows",
  getSortData: {
    name: ".name",
  },
});

// init Isotope
// var $grid = $(".gridz").isotope({
//   itemSelector: ".color-shape",
// });

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
  $grid.arrange({
    filter: filterValue,
  });

  $resetBtn.show();
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
  $grid.arrange({
    sortBy: sortValue,
    sortAscending: sortDirection,
  });
  $(".c-filter__container--clear").show();
});

/**
 * Reset filters
 */
$(".button--reset").on("click", function() {
  // reset filters
  filters = {};
  $grid.arrange({
    filter: "*",
  });
  $(".c-filter__container--clear").hide();
});

// flatten object by concatting values
function concatValues(obj) {
  var value = "";
  for (var prop in obj) {
    value += obj[prop];
  }
  return value;
}
