// Get the modal
var purchaseModal = document.getElementById("purchaseModal");

// Get the button that opens the modal
var purchaseBtn = document.getElementById("purchaseBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
purchaseBtn.onclick = function() {
    purchaseModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    purchaseModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == purchaseModal) {
    purchaseModal.style.display = "none";
  }
}