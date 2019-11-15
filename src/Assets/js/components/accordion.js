export default class Accordion {
    
    constructor() {
        this.accordionTrigger = document.getElementsByClassName('accordion');
        
        for (let i = 0; i < this.accordionTrigger.length; i++) {
            let currentElement = this.accordionTrigger[i];
            currentElement.addEventListener('click', (e) => this.handleClick(e) );
        }
        
    }
    
    handleClick(e) {
        e.target.parentElement.classList.toggle('active')
    }

}




/*
    var acc = document.getElementsByClassName("accordion"),
    i,
    panel;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.parentElement.classList.toggle("active");
        });
    }
*/