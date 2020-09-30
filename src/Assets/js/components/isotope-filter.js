var Isotope = require("isotope-layout");

(function($) {
  var $resetBtnMobile = $(".c-filter__reset--mobile");
  var $resetBtnDesktop = $(".c-filter__reset--desktop");

  var $Items = $(".filter-item");
  var $numberOfItems = $Items.length;

  if ($numberOfItems) {
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
      //If no number of results found show message
      if (grid.filteredItems.length > 0) {
        $(".c-filter__no-results").hide();
      } else {
        $(".c-filter__no-results").show();
      }
    }

    checkResults();

    // store filter for each group
    var filters = {};

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

      // if number of results matches show
      if (grid.filteredItems.length !== $numberOfItems) {
        $resetBtnMobile.show();
        if (!isMobile() && $(window).width() > 768) {
          $resetBtnMobile.hide();
          $resetBtnDesktop.show();
        }
      }

      scrollToTheTop(85);
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

      scrollToTheTop(85);
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
      $resetBtnDesktop.hide();
      $resetBtnMobile.hide();
      $(".c-filter__no-results").hide();
      scrollToTheTop(85);
    });

    function scrollToTheTop($val) {
      $("html, body").animate(
        {
          scrollTop: $("#section-top").offset().top - $val,
        },
        700
      );

      setTimeout(function() {
        $(".parallax-window").parallax("refresh");
        $(window)
          .trigger("resize")
          .trigger("scroll");
      }, 200);
    }

    // flatten object by concatting values
    function concatValues(obj) {
      var value = "";
      for (var prop in obj) {
        value += obj[prop];
      }
      return value;
    }
  }
})(jQuery);
