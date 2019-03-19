<?php

    use App\Helper\Render;

    $render = new Render;

    include("header.php");

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

    // $title = get_the_title( $this->get_the_ID() );
    // $title = esc_html( strip_tags( $title ) ); //ADD - strip tags before sending to template

    // return $title;

      $artwork[] = [
        'id' => get_the_id(),
        'title' => get_the_title(),
        'subPostTitle' => get_field('sub_post_title'),
        'surname' => get_field('surname'),
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
        'link' => get_the_permalink()
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

	$gallery = get_field('image_gallery', 6);
	$images = [];

?>

<?php if($gallery) : ?>
	<section class="u-section" id="carousel">
		<div class="zu-l-container--center" data-in-viewport>
      <div class="zu-l-container zu-l-container--row">
      
        <div class="swiper-container">
          <div class="swiper-wrapper">
          <?php foreach ($gallery as $galleryImage) :  ?>
            <div class="swiper-slide">
              <figure class="swiper-slide__image" style="background-image:url('<?php echo $galleryImage['sizes']['large']; ?>');"></figure>
            </div>
          <?php endforeach; ?>
          </div>
          
          <!-- Add Pagination -->
          <div class="swiper-pagination"></div>
          
        </div>

        <?php /*
				<ul class="c-carousel-controls">
					<li class="v-m-carousel__control v-m-carousel__control--prev">
						<button class="disabled carousel-button prev swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 14" id="next" width="100%" height="100%"><path fill="currentColor" d="M6.5 7L0 14v-2l4.5-4.941L0 2V0z"></path></svg></button>
					</li>
					<li class="v-m-carousel__control v-m-carousel__control--next">
						<button class="carousel-button next swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 14" id="next" width="100%" height="100%"><path fill="currentColor" d="M6.5 7L0 14v-2l4.5-4.941L0 2V0z"></path></svg></button>
					</li>
        </ul>
        */ ?>

			</div>
		</div>
	</section>
<?php endif; ?>
		  
 
<section class="u-section ">
  <div id="app">
  </div>
</section>

<?php include("footer.php"); ?>