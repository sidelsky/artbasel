$(document).ready(function(){
    $('.owl-carousel-home').owlCarousel({
        center: true,
        dots: false,
        nav: true,
        responsiveClass: true,
        lazyLoad: true,
        loop: true,
        margin: 20,
        autoHeight: true,
        navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
        responsive:{
            0: {
                items: 1
              },
          
              600: {
                items: 1
              },
          
              1024: {
                items: 1
              },
          
              1366: {
                items: 1
              }
        }
    });
  });