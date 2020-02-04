// Get the modal
var modal = document.getElementById("weChatModal");

// Get the button that opens the modal
var btn = document.getElementById("weChatBtn");

// Get the <span> element that closes the modal
//var span = document.getElementsByClassName("close")[0];

var close = document.getElementById("close");

// When the user clicks on the button, open the modal
btn.onclick = function(event) {
  modal.style.display = "block";
  console.log('click');
  
  event.preventDefault();
}

// When the user clicks on <span> (x), close the modal
close.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}