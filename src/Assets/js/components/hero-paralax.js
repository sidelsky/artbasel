var w = window,
  distance,
  header = document.getElementById("header"),
  body = document.getElementById("body"),
  headerHeight,
  top;

headerHeight = header.offsetHeight;

//Animated Header
function animatedHeader() {
  if (w.pageYOffset >= headerHeight) {
    body.classList.add("is-scrolled");
  } else {
    body.classList.remove("is-scrolled");
  }
}

function heroParalax() {
  distance = 5;
  top = w.scrollY;

  const titleElems = [...document.querySelectorAll("[data-id='title']")];
  const titleElemsLength = titleElems.length;

  for (let index = 0; index < titleElemsLength; index++) {
    const titleElem = titleElems[index];
    titleElem.style.transform = `translate3d(0, ${top / distance}px, 0)`;
  }
}

w.onscroll = function() {
  animatedHeader();
  heroParalax();
};
