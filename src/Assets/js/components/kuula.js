let showAll;

const touchButtons = [...document.querySelectorAll("[data-id='touch-button']")];
const touchCovers = [...document.querySelectorAll("[data-id='touch-cover']")];

// let theTouchButtons = document.querySelectorAll("[data-id='touch-button']");
// let theTouchCovers = document.querySelectorAll("[data-id='touch-cover']");

const touchButtonsLength = touchButtons.length;
//const touchCoversLength = touchCovers.length;
showAll = () => {
  for (let index = 0; index < touchButtons.length; index++) {
    touchButtons[index].style.display = "block";
    touchCovers[index].style.display = "block";
  }
};

for (let index = 0; index < touchButtonsLength; index++) {
  let touchButton = touchButtons[index];
  let touchCover = touchCovers[index];

  touchButton.addEventListener("click", () => {
    showAll();
    touchButton.style.display = "none";
    touchCover.style.display = "none";
  });
}
