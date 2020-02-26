
var w = window,
distance,
top;

function heroParalax() {

    distance = 5;
    top = w.scrollY;

    const titleElems = [...document.querySelectorAll("[data-id='title']")]
    const titleElemsLength = titleElems.length;

    console.log(titleElems);

    for (let index = 0; index < titleElemsLength; index++) {
        const titleElem = titleElems[index];
        titleElem.style.transform = `translate3d(0, ${top / distance}px, 0)`;
    }

}

w.onscroll = function() {
    heroParalax();
};