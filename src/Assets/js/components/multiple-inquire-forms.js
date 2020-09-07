const inquireBtns = [
  ...document.querySelectorAll("[data-id='inquire-button']"),
];
const formModal = document.getElementById("inquireModal");
const inquireFormTextArea = document.getElementsByTagName("textarea")[0];
const hiddenField = document.getElementsByTagName("input")[3];
const closeBtn = document.getElementById("closeBtn");
const inquireBtnsLength = inquireBtns.length;

if (inquireBtnsLength) {
  for (let index = 0; index < inquireBtnsLength; index++) {
    let inquireBtn = inquireBtns[index];
    let btnValue = inquireBtn.dataset.messageValue;
    let idCode = inquireBtn.dataset.idCode;

    inquireBtn.addEventListener("click", () => {
      inquireFormTextArea.value = btnValue;
      hiddenField.value = idCode;
      formModal.style.display = "block";
    });

    closeBtn.addEventListener("click", () => {
      formModal.style.display = "none";
    });

    //detect Escape key press
    document.addEventListener("keydown", function(event) {
      if (event.keyCode === 27) {
        formModal.style.display = "none";
      }
    });
  }
}
