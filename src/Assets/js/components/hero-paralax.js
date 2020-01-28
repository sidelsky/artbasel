var w = window,
    body = document.getElementById('body'),
    headerHeight,
    title,
    distance,
    top;
 
    //Hero Paralax
    function heroParalax() {
         
        distance = 5;
        title = document.getElementById("title");
        top = w.scrollY;
 
        if(title) {
            title.style.transform = `translate3d(0, ${top / distance}px, 0)`;
        }
 
    }
 
    w.onscroll = function() {
        heroParalax();
    };