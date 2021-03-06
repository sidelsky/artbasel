import "./svg-sprite";
import "./archive";
import "./components/jquery";
import "./components/isotope-filter";
//import "rellax";
import "magnify";
import "jquery-zoom";
import "./components/image-zoom";
import "./components/login-form";
import "./components/paralax";
import "./components/smoothscroll-api";
import "./components/owl-carousel";
import "./components/owl-small-carousel-api";
import "./components/owl-hero-carousel-api";
import "./components/owl-exhibitions-carousel-api";
import "./components/hero-paralax";
import "./components/form-modal";
import "./components/we-chat-modal";
import "./components/vimeo-controls";
import "./components/owl-image-content-carousel-api";
import "./components/multiple-inquire-forms";
import "./components/scroll-to-navigation";
import "./components/highlight-scrolltonav";
import "./components/kuula";

//import "./components/noframework.waypoints";
//import "./components/rellax";
import "./components/magnifing-glass-carousel";
import Hamburger from "./components/hamburger";
new Hamburger();

/*------------------------------------*\
	Filter select
\*------------------------------------*/
var $filterSelect = $("[data-filter-select]");
if ($filterSelect.length) {
  var FilterSelect = require("./components/filter-select");
  $filterSelect.each(function(i, elem) {
    new FilterSelect($(elem));
  });
}
