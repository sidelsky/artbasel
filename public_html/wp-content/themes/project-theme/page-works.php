
<?php
/**
 * Template Name: Works
 */

		use App\Helper\Render;

		$render = new Render;

		include("header.php");

		/* Include login modal */
		include("login.php");

?>


<script>

<?php

		$loop = new WP_Query(
				[
					'post_type' => 'works',
					'posts_per_page' => -1,
					'meta_key' => 'surname',
					'orderby' => 'meta_value',
					'order' => 'ASC'
				]
		 );

		$artwork = [];

		while ( $loop->have_posts() ) : $loop->the_post();
		get_the_id();
		// $title = get_the_title( $this->get_the_ID() );
		// $title = esc_html( strip_tags( $title ) ); //ADD - strip tags before sending to template

		// return $title;

			$artwork[] = [
				'key' => get_the_id(),
				'title' => get_the_title(),
				'subPostTitle' => get_field('sub_post_title'),
				'surname' => get_field('surname'),
				'fullName' => get_field('full_name'),
				'image' => get_the_post_thumbnail_url(),
				'date' => get_field('date'),
				'description' => get_field('description'),
				'medium' => get_field('medium'),
				'mediumText' => get_field('medium_free_text'),
				'mediumChinese' => get_field('medium_chinese'),
				'decade' => get_field('decade'),
				'dimensions' => get_field('dimensions'),
				'price' => get_field('price'),
				'priceRange' => get_field('price_range'),
				'link' => get_the_permalink(),
				'sold' => get_field('sold'),
				'ids'  => get_field('code_id')
			];

		endwhile;

		echo 'var WORKS = ' . json_encode($artwork) . ';';

		function createFilters($array) {
			$newArray = [
				[
					'label' => 'All',
					'value' => 'all'
				]
			];

			foreach ($array as $key => $value) {
				$newArray[] = [
					'label' => $value,
					'value' => $key
				];
			}

			return $newArray;
		};

		$priceRange = createFilters(get_field_object('price_range')['choices']);
		$medium = createFilters(get_field_object('medium')['choices']);
		$decade = createFilters(get_field_object('decade')['choices']);

		$filters = [
			'priceRange' => $priceRange,
			'medium' => $medium,
			'decade' => $decade
		];

		echo 'var FILTERS = ' . json_encode($filters);
?>
		
</script>


<?php 
/**
 * Hero paralax image
 */
$gallery = get_field('image_gallery', 6);
$images = [];

if($gallery) : ?>

	<!-- Parallax mobile -->
	<!-- <section class="u-section c-paralax-header c-paralax-header--mobile">
		<?php foreach ($gallery as $galleryImage) :  ?>
			<img src="<?php echo $galleryImage['sizes']['large']; ?>">
		<?php endforeach; ?>
	</section> -->
	
	<!-- Parallax desktop -->
	<section class="u-section c-paralax-header c-paralax-header--desktop">
		<?php foreach ($gallery as $galleryImage) :  ?>
			<div class="c-header-background-image" style="background-image: url('<?php echo $galleryImage['sizes']['large']; ?>')">
				<?php
					$front_page_id = '6';
					$currentPost_id = get_the_ID();
					$content = get_post_field('post_content', $front_page_id);
		
					if( is_front_page() || is_page('Works preview') ) { ?>
						 
						<div class="parallax-window__content">
							<h1 class="c-site-headings  c-site-headings--h1 c-site-headings--h1--hero c-text-align-centre "><?php echo get_the_title( $front_page_id ); ?></h1>
							<h2 class="c-site-headings c-site-headings--h1--sub c-site-headings--text-align-center"><?php echo get_field( 'works_title_chinese', $front_page_id ); ?></h2>
							<?php if($content ) : ?>
								<div class="u-l-container--narrow c-site-headings c-site-headings--h1--small c-site-headings--text-align-center"><?= $content; ?></div>
							<?php endif; ?>
						</div>
 
					<?php } ?>
				<!-- <div class="parallax-window" data-parallax="scroll" data-image-src="<?php echo $galleryImage['sizes']['large']; ?>"></div> -->
			</div>
			<?php endforeach; ?>
	</section>

<?php endif; ?>
			
 
<section class="u-section ">
	<div id="app">
	</div>
</section>

<?php 
/**
 * Footer paralax image
 */

$gallery = get_field('footer_gallery', 6);
$images = [];

if($gallery) : ?>

	<!-- Parallax mobile -->
	<section class="u-section c-paralax-header c-paralax-header--mobile">
		<?php foreach ($gallery as $galleryImage) :  ?>
			<img src="<?php echo $galleryImage['sizes']['large']; ?>">
		<?php endforeach; ?>
	</section>
	
	<!-- Parallax desktop -->
	<section class="u-section c-paralax-header c-paralax-header--desktop">
		<div>
			<?php foreach ($gallery as $galleryImage) :  ?>
				<div class="parallax-window parallax-window__footer" data-parallax="scroll" data-image-src="<?php echo $galleryImage['sizes']['large']; ?>"></div>
			<?php endforeach; ?>
		</div>
	</section>

<?php endif; ?>

<?php 
/**
 * Footer content
 */
?>
<section class="u-section">
	<div class="u-l-container--center">
		<div class="u-l-container u-l-container--shallow u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
			<div class="s-content c-works__footer c-works__footer__hr">
			<?php
				// query for the about page
				$your_query = new WP_Query( 'pagename=works-list-footer' );
				// "loop" through query (even though it's just one page) 
				while ( $your_query->have_posts() ) : $your_query->the_post();
					the_content();
				endwhile;
				// reset post data (important!)
				wp_reset_postdata();
			?>
			</div>
		</div>
	</div>
</section>

<?php include("footer.php"); ?>