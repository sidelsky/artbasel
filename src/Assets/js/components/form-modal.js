// // Get the modal
// var purchaseModal = document.getElementById("purchaseModal");
// var inquireModal = document.getElementById("inquireModal");

// // Get the button that opens the modal
// var purchaseBtn = document.getElementById("purchaseBtn");
// var inquireBtn = document.getElementById("inquireBtn");

// // Get the <span> element that closes the modal
// var closep = document.getElementsByClassName("closep")[0];
// var closei = document.getElementsByClassName("closei")[0];

// // When the user clicks on the button, open the modal
// purchaseBtn.onclick = function() {
//     purchaseModal.style.display = "block";
// }

// inquireBtn.onclick = function() {
//     inquireModal.style.display = "block";
// }

// // When the user clicks on <span> (x), close the modal
// closep.onclick = function() {
//     purchaseModal.style.display = "none";
// }

// closei.onclick = function() {
//     inquireModal.style.display = "none";
// }

// // When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//   if (event.target == purchaseModal) {
//     purchaseModal.style.display = "none";
//   }
//   if (event.target == inquireModal) {
//     inquireModal.style.display = "none";
//   }
// }


/** count */
const purchaseButtons = [...document.querySelectorAll("[data-id='purchaseBtn']")]
const purchaseButtonsLength = purchaseButtons.length;

for (let index = 0; index < purchaseButtonsLength; index++) {
  const purchaseButton = purchaseButtons[index];
  const purchaseModal = document.getElementById(`purchaseModal_${index}`);
  //debugger
  purchaseButton.addEventListener('click', () => {
    purchaseModal.style.display = "block";
  })

  Array.apply(null, purchaseModal.querySelectorAll('.close')).forEach(closeButton => closeButton.addEventListener('click', () => {purchaseModal.style.display = 'none'}) )

}


/** count */
const inquireButtons = [...document.querySelectorAll("[data-id='inquireBtn']")]
const inquireButtonsLength = inquireButtons.length;

for (let index = 0; index < inquireButtonsLength; index++) {
  const inquireButton = inquireButtons[index];
  const inquireModal = document.getElementById(`inquireModal_${index}`);
  //debugger
  inquireButton.addEventListener('click', () => {
    inquireModal.style.display = "block";
  })

  Array.apply(null, inquireModal.querySelectorAll('.close')).forEach(closeButton => closeButton.addEventListener('click', () => {inquireModal.style.display = 'none'}) )

}