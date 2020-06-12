const inquireBtns = [
  ...document.querySelectorAll("[data-id='inquire-button']"),
];
const formModal = document.getElementById("inquireModal");
//const inquireFormTextArea = document.getElementById("input_7_5");
//const hiddenField = document.getElementById("input_7_7");
const inquireFormTextArea = document.getElementById("input_12_5");
const hiddenField = document.getElementById("input_12_7");
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
  }
}
