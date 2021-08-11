

export function view_initializeMasonry  (selfInstance) {


  var $ = jQuery;
  var auxoptions = {
    columnWidth: 1,
    itemSelector: '.masonry-gallery--item'
  };


  if ($.fn.masonry) {
    selfInstance.$dzsSelectorMain.parent().find('.dzslayouter .items-con').masonry(auxoptions);
  }
}
