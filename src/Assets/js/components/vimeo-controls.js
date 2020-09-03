const iframes = [
  ...document.querySelectorAll("[data-id='vimeo-content'] iframe"),
];
const playButtons = [...document.querySelectorAll("[data-id='playBtn']")];
const covers = [...document.querySelectorAll("[data-id='cover']")];

const prevSlide = document.getElementById("prev-slide");
const nextSlide = document.getElementById("next-slide");

const iframesLength = iframes.length;
const playButtonsLength = playButtons.length;
const coversLength = covers.length;

if (playButtonsLength) {
  for (let index = 0; index < iframesLength; index++) {
    let iframe = iframes[index];
    let playButton = playButtons[index];
    let cover = covers[index];
    let player = new Vimeo.Player(iframe);

    playButton.addEventListener("click", () => {
      cover.style.display = "none";
      playButton.style.display = "none";
      player.play();
    });

    player.on("pause", function() {
      cover.style.display = "block";
      playButton.style.display = "block";
    });

    if (prevSlide && nextSlide) {
      prevSlide.addEventListener("click", () => {
        cover.style.display = "block";
        playButton.style.display = "block";
        player.pause();
      });

      nextSlide.addEventListener("click", () => {
        cover.style.display = "block";
        playButton.style.display = "block";
        player.pause();
      });
    }
  }
}
