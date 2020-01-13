<?php
	
	$post_id_dev = 2435;
	$post_id_prod = 2716;

	$localhost = 'artbasilvip:8888';

	if ($_SERVER['HTTP_HOST'] == $localhost) {
		$post_id = $post_id_dev;
	} else {
		$post_id = $post_id_prod;
	}   

    $post = get_post($post_id, ARRAY_A);
    //$title = $post['post_title'];
	//$content = $post['post_content'];

	$show_email_submission = get_field('show_email_submission', $post_id);
	$title = get_field('title_email_sub', $post_id);
	$subtitle = get_field('subtitle_email_sub', $post_id);
	$show_countdown = get_field('show_countdown', $post_id);
	$countdown_end_date = get_field('countdown_end_date', $post_id);
	$consent = get_field('consent', $post_id);

?>

<?php if($show_email_submission) : ?>

	<section class="c-login c-login--background-dark">

			<header class="c-header c-header--modal">
				<a href="<?= $baseUrl ?>" class="c-header__link">
					<svg class="c-header__icon">
						<use xlink:href='#shape-hauserwirth-logo'></use>
					</svg>
				</a>
			</header>

		<div class="u-l-container--center" data-in-viewport>
			<div class="u-l-container u-l-container--row u-l-vertical-padding--small">
				
				
				<h1 class="c-site-headings c-site-headings--h1--email-sub c-site-headings--h1 c-site-headings--h1--light c-text-align-centre"><?= $title; ?></h1>
				
				<?php if($show_countdown) : ?>
					<div id="clockdiv" class="c-clock c-text-align-centre"> 
						<div> 
							<span class="days" id="day"></span> 
							<div class="smalltext">Days</div> 
						</div> 
						<div> 
							<span class="hours" id="hour"></span> 
							<div class="smalltext">Hours</div> 
						</div> 
						<div> 
							<span class="minutes" id="minute"></span> 
							<div class="smalltext">Minutes</div> 
						</div> 
						<div> 
							<span class="seconds" id="second"></span> 
							<div class="smalltext">Seconds</div> 
						</div> 
					</div>
				<?php endif; ?>
					
				<p id="demo"></p>

				<h3 class="c-site-headings c-site-headings--h3 c-text-align-centre"><?= $subtitle; ?></h3>
					<div class="tml tml-login" id="theme-my-login1">
						<?php echo do_shortcode('[gravityform id="4" title="false" description="false"]'); ?>
						<p class="c-footer"><small><?= $consent; ?></small></p>

						<footer class="u-section c-footer c-footer--modal">
				<div class="u-l-container">
					<p class="c-footer__copyright"><?php echo $themeData['copyright']['details'];?> &nbsp; | &nbsp; <a href="https://www.vip-hauserwirth.com/site-terms-of-use/" target="_blank">Terms &amp; Conditions</a><br><small>The artworks described above are subject to changes in availability and price without prior notice. Prices excl. VAT</small></p>
				</div>
			</footer>
			
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<script> 
	var show_countdown = "<?php echo $show_countdown ?>";
	//var deadline = new Date("dec 31, 2020 15:37:25").getTime();
	var deadline = new Date("<?php echo $countdown_end_date ?>").getTime();

	if(show_countdown) {
	
		var x = setInterval(function() { 
		
		var now = new Date().getTime(); 
		var t = deadline - now; 
		var days = Math.floor(t / (1000 * 60 * 60 * 24)); 
		var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60)); 
		var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60)); 
		var seconds = Math.floor((t % (1000 * 60)) / 1000); 

		document.getElementById("day").innerHTML = days; 
		document.getElementById("hour").innerHTML = hours; 
		document.getElementById("minute").innerHTML = minutes;  
		document.getElementById("second").innerHTML = seconds;

		if (t < 0) { 
				clearInterval(x); 
				document.getElementById("demo").innerHTML = "TIME UP"; 
				document.getElementById("day").innerHTML ='0'; 
				document.getElementById("hour").innerHTML ='0'; 
				document.getElementById("minute").innerHTML ='0' ;  
				document.getElementById("second").innerHTML = '0'; } 
		}, 1000); 

	}
</script>