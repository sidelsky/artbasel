import Siema from 'siema';

// Siema 1.3.0 introuduced a basic callbacks
// https://github.com/pawelgrzybek/siema/releases/tag/v.1.3.0

// function onInitCallback() {
//    console.log('Siema initialised bro :)');
// }

// function onChangeCallback() {
//    console.log(`The index of current slide is: ${this.currentSlide}`);
// }


let carousel = document.getElementById("carousel");

if (carousel) {

   setTimeout(function () {

      const mySiema = new Siema({
         loop: true,
         duration: 200,
         easing: 'ease-out',
         perPage: 1,
         startIndex: 0,
         draggable: true,
         multipleDrag: true,
         threshold: 20
         //onInit: onInitCallback,
         //onChange: onChangeCallback,
      });

      //setInterval(() => mySiema.next(), 4500);

      const prev = document.querySelector('.prev');
      const next = document.querySelector('.next');

      prev.addEventListener('click', () => mySiema.prev());
      next.addEventListener('click', () => mySiema.next());


      //document.addEventListener("DOMContentLoaded", () => myEvent());


      let stateCheck = setInterval(() => {
         if (document.readyState === 'complete') {
            clearInterval(stateCheck);
            // document ready
            console.log('Loaded');
            mySiema.resizeHandler();
         }
      }, 100);

      console.log('Timer Called');

   }, 1500);


   window.addEventListener("resize", mySiema.resizeHandler());


}