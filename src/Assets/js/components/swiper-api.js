var mySwiper = new Swiper('.swiper-container', {
   // Optional parameters
   loop: true,
   slidesPerView: 'auto',
   spaceBetween: 0,
   autoplay: {
      delay: 4500,
      disableOnInteraction: false,
   },

   // If we need pagination
   pagination: {
      el: '.swiper-pagination',
      clickable: true,
   }
})