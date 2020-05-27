const scrollMenuItems = [
  ...document.querySelectorAll("[data-id='scroll-menu-item']"),
];
const scrollMenuItemsLength = scrollMenuItems.length;

if (scrollMenuItemsLength) {
  for (let index = 0; index < scrollMenuItemsLength; index++) {
    const scrollMenuItem = scrollMenuItems[index];
    const className = "current_page_item";

    scrollMenuItem.addEventListener("click", () => {
      //Remove active class from other nav items
      scrollMenuItems.forEach((element) => {
        element.classList.remove(className);
      });

      scrollMenuItem.classList.add(className);
    });
  }
}
