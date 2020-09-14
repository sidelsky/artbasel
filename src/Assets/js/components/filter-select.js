/** Filters - select style */
const selects = [...document.querySelectorAll("[data-id='filter-select']")];
const selectsLength = selects.length;

const groups = [...document.querySelectorAll("[data-id='group']")];

for (let index = 0; index < selectsLength; index++) {
  const select = selects[index];
  const group = groups[index];

  select.addEventListener("click", () => {
    if (group.style.display === "block") {
      group.style.display = "none";
    } else {
      group.style.display = "block";
    }
  });

  group.addEventListener("mouseleave", () => {
    group.style.display = "none";
  });
}
