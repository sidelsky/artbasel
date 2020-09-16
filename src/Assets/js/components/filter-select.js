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

/** Filters - select style */
// const selects = [...document.querySelectorAll("[data-id='filter-select']")];
// const selectsLength = selects.length;

// const groups = [...document.querySelectorAll("[data-id='group']")];

// const listItems = [...document.querySelectorAll("[data-id='items']")];
// const listItemsLength = listItems.length;

// const titles = [...document.querySelectorAll("[data-id='title']")];

// for (let index = 0; index < listItemsLength; index++) {
//   const listItem = listItems[index];
//   console.log(listItem);
// }

// for (let index = 0; index < selectsLength; index++) {
//   const select = selects[index];
//   const group = groups[index];
//   const title = titles[index];
//   console.log(title);

//   select.addEventListener("click", () => {
//     if (group.style.display === "block") {
//       group.style.display = "none";
//     } else {
//       group.style.display = "block";
//     }
//   });

//   group.addEventListener("mouseleave", () => {
//     group.style.display = "none";
//   });
// }
