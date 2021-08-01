<?php
use App\Helper\WordpressHelper;
$wordpress = new WordpressHelper;
?>

</main>


<!-- The Modal -->
<div id="weChatModal" class="modal wechat__modal">
	<!-- Modal content -->
	<div class="modal-content wechat__content">
		<span class="close" id="close">&times;</span>
		<p class="wechat__title">Follow us on WeChat</p>
		<img src="https://www.hauserwirth.com/images/wechat.jpg" alt="WeChat">
		<small>Scan the image to begin</small>
	</div>
</div>

<?php include("partials/artbasel-footer-details.php"); ?>

</div>

<?php wp_footer(); ?>
<script src='/wp-content/themes/project-theme/assets/build/app.js'></script>
<!-- sticky header scroll -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script>
// Cache selectors
var lastId,
 topMenu = $("#sticky-contents"),
 topMenuHeight = topMenu.outerHeight()+1,
 // All list items
 menuItems = topMenu.find("a"),
 // Anchors corresponding to menu items
 scrollItems = menuItems.map(function(){
   var item = $($(this).attr("href"));
    if (item.length) { return item; }
 });


// Bind to scroll
$(window).scroll(function(){
   // Get container scroll position
   var fromTop = $(this).scrollTop()+topMenuHeight+60;

   // Get id of current scroll item
   var cur = scrollItems.map(function(){
     if ($(this).offset().top < fromTop)
       return this;
   });
   // Get the id of the current element
   cur = cur[cur.length-1];
   var id = cur && cur.length ? cur[0].id : "";

   if (lastId !== id) {
       lastId = id;
       // Set/remove active class
       menuItems
         .parent().removeClass("active")
         .end().filter("[href=#"+id+"]").parent().addClass("active");
   }
});

</script>

<script>
// fade featured h2 in / out on scroll
$(document).ready(function() {
$(window).scroll( function(){
$('.hideme').each( function(i){
var bottom_of_object = $(this).offset().top + $(this).outerHeight();
var bottom_of_window = $(window).scrollTop() + $(window).height();
if( bottom_of_window > bottom_of_object ){
$(this).addClass('showme');
}
if( bottom_of_window < bottom_of_object ){
$(this).removeClass('showme');
}
});
});
});
</script>


<!-- page animations -->
<link rel="stylesheet" href="/wp-content/themes/project-theme/assets/build/animate.css" media="screen" />

<script type="text/javascript" src="/wp-content/themes/project-theme/assets/build/wow.min.js"></script>
<script type="text/javascript">// <![CDATA[
 new WOW().init();
// ]]></script>
	</body>
</html>
