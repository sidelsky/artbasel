const inquireBtns = [
  ...document.querySelectorAll("[data-id='inquire-button']"),
];
const formModal = document.getElementById("inquireModal");
const inquireFormTextArea = document.getElementById("input_7_5");
const closeBtn = document.getElementById("closeBtn");
const inquireBtnsLength = inquireBtns.length;

if (inquireBtnsLength) {
  for (let index = 0; index < inquireBtnsLength; index++) {
    let inquireBtn = inquireBtns[index];
    let btnValue = inquireBtn.value;

    inquireBtn.addEventListener("click", () => {
      inquireFormTextArea.value = btnValue;
      formModal.style.display = "block";
    });

    closeBtn.addEventListener("click", () => {
      formModal.style.display = "none";
    });
  }
}
