/*! Zoom Image 1.0.0 | MIT *
 * https://github.com/jpcurrier/zoom-image !*/
(function($) {
  $.fn.zoomImage = function(options) {
    // default options
    var settings = $.extend(
      {
        touch: true,
      },
      options
    );

    return this.each(function() {
      var $image = $(this);

      if (settings.touch || !("ontouchstart" in document.documentElement)) {
        $image.on("mousemove", function(e) {
          // image + cursor data
          var bounds = {
              width: $image.outerWidth(),
              height: $image.outerHeight(),
            },
            xPercent = (e.pageX - $image.offset().left) / bounds.width,
            yPercent = (e.pageY - $image.offset().top) / bounds.height,
            zoom = new Image();
          zoom.src = $image
            .children()
            .css("background-image")
            .replace(/.*\s?url\([\'\"]?/, "")
            .replace(/[\'\"]?\).*/, "");

          var maxPan = {
            left: -(zoom.naturalWidth - bounds.width),
            top: -(zoom.naturalHeight - bounds.height),
          };

          $image.children().css({
            "background-position":
              xPercent * maxPan.left + "px " + yPercent * maxPan.top + "px",
          });
        });
      }
    });
  };
})(jQuery);
