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

/** List purchase button */
const ListPurchaseButtons = [...document.querySelectorAll("[data-id='ListPurchaseBtn']")]
const ListPurchaseButtonsLength = ListPurchaseButtons.length;

for (let i = 0; i < ListPurchaseButtonsLength; i++) {
  const ListPurchaseButton = ListPurchaseButtons[i];
  const ListPurchaseModal = document.getElementById(`ListPurchaseModal_${i}`);
  //debugger
  ListPurchaseButton.addEventListener('click', () => {
    ListPurchaseModal.style.display = "block";
  })

  Array.apply(null, ListPurchaseModal.querySelectorAll('.close')).forEach(closeButton => closeButton.addEventListener('click', () => {ListPurchaseModal.style.display = 'none'}) )

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