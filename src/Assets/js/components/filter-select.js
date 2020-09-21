// /**
// * Table data
// */

// var cssClasses = require('./config').cssClasses;

var FilterSelect = function FilterSelect($elem) {
  this.$elem = $elem;
  this.$open = "is-open";
  this.$title = $(".c-filter__title span", this.$elem);
  this.$menu = $(".c-filter__select-menu", this.$elem);
  this.$item = $(".c-filter__item", this.$elem);
  this.$showFiltersBtn = $(".show-filters-btn", this.elem);

  this._attachHandlers();
};

/*  Attach handler event
 -----------------------------------*/
FilterSelect.prototype._attachHandlers = function($elem) {
  var _this = this;

  _this.$title.on("click", function() {
    _this.$elem.toggleClass(_this.$open);
  });

  _this.$item.on("click", function() {
    var $itemContent = $(this).text();
    _this.$title.text($itemContent);
    _this.$elem.toggleClass(_this.$open);
  });

  _this.$menu.on("mouseleave", function() {
    if (_this.$elem.hasClass(_this.$open)) {
      _this.$elem.toggleClass(_this.$open);
    }
  });
};

// /*  Returns a constructor
//  -----------------------------------*/
module.exports = FilterSelect;

var $showFilters = $(".show-filters-btn");
var $mobileFilter = $(".mobile-filters");

$showFilters.on("click", function(i) {
  $mobileFilter.toggle();
});

$(".c-filter__container--mobile").each(function() {
  $(this).on("click", function() {
    $(this).toggleClass("is-active");
  });
});
