$(document).ready(function(){
    $('.owl-carousel-home').owlCarousel({
        stagePadding: 50,
        center: true,
        items: 1,
        loop: false,
        dots: false,
        nav: true,
        navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
  });