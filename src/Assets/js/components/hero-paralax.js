
var w = window,
distance,
top;

function heroParalax() {

    distance = 5;
    top = w.scrollY;

    // const parent = document.querySelector('.canvas');
    // const parentTop = parent.parentNode.offsetTop;
    // console.log(parentTop);
    
    const titleElems = [...document.querySelectorAll("[data-id='title']")]
    const titleElemsLength = titleElems.length;

    for (let index = 0; index < titleElemsLength; index++) {
        const titleElem = titleElems[index];
        titleElem.style.transform = `translate3d(0, ${top / distance}px, 0)`;
    }

}

w.onscroll = function() {
    heroParalax();
};