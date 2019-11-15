<?php
use App\Helper\Page;
use App\Helper\Render;
use App\Helper\WordpressHelper;
//use Theme\Model\Hero;
//use Theme\Model\SubpageHero;
$page = new Page;
$render = new Render;
//$hero = new Hero;
//$subpageHero = new SubpageHero;
$wordpress = new WordpressHelper;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<title><?= $page->getPageTitle(); ?></title>

		<meta name="theme-color" content="#333">
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<link data-n-head="true" rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/build/img/favicon/favicon-16x16.png"/>
		<link data-n-head="true" rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/build/img/favicon/favicon-32x32.png"/>
		<link data-n-head="true" rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/assets/build/img/favicon/favicon-96x96.png"/>
		
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?> id="body">

	<?php
		/**
		 * Theme Data
		 */
		include('Theme/Data/en.php');
		$homeUrl = get_home_url();
		$worksUrl = '/works';
		if( is_archive( 'works' ) || is_single( ) ) {
			$baseUrl = $worksUrl;
		} else {
			$baseUrl = $homeUrl;
		}
	?>


	<header class="c-header">
		<a href="<?= $baseUrl ?>" class="c-header__link">
			<svg class="c-header__icon">
				<use xlink:href='#shape-hauserwirth-logo'></use>
			</svg>
		</a>
	</header>
 
	
			<?php
			$front_page_id = '6';
			$currentPost_id = get_the_ID();
			$content = get_post_field('post_content', $front_page_id);
			if( is_front_page() ) { ?>
			<section class="u-section ">
				<div class="u-l-container--center" data-in-viewport>
					<div class="u-l-container u-l-container--row u-l-horizontal-padding <?= is_front_page() === ( TRUE ) ? 'u-l-vertical-padding u-l-vertical-padding--bottom' : 'u-l-vertical-padding u-l-vertical-padding--small' ?>">
						<?php the_title('<h1 class="c-site-headings  c-site-headings--h1 c-text-align-centre ">','</h1>'); ?>
					</div>
				</div>
			</section>

			<?php } elseif( is_archive( 'works' ) ) { ?>
			<section class="u-section parallax-window__content">
				<div class="u-l-container--center" data-in-viewport>
					<div class="u-l-container u-l-container--row u-l-horizontal-padding <?= is_front_page() === ( TRUE ) ? 'u-l-vertical-padding u-l-vertical-padding--bottom' : 'u-l-vertical-padding u-l-vertical-padding--small' ?>">
					<h1 class="c-site-headings  c-site-headings--h1 c-text-align-centre ">
						<?php echo get_the_title( $front_page_id ); ?><br>
						<span class="c-site-headings c-site-headings--h1--small"><?php echo get_field( 'works_title_chinese', $front_page_id ); ?></span>
					</h1>
						<?php if($content ) : ?>
							<div class="c-works-content"><?php $content; ?></div>
						<?php endif; ?>
					</div>
				</div>
			</section>
			<?php } else { ?>

			<section class="u-section ">
				<div class="u-l-container--center" data-in-viewport>
					<div class="u-l-container u-l-container--row u-l-horizontal-padding <?= is_front_page() === ( TRUE ) ? 'u-l-vertical-padding u-l-vertical-padding--bottom' : 'u-l-vertical-padding u-l-vertical-padding--small' ?>">
						<h1 class="c-site-headings  c-site-headings--h1 c-text-align-centre "><?php echo get_the_title( $currentPost_id ); ?></h1>
						<h1 class="c-site-headings  c-site-headings--h1 c-text-align-centre "><?php echo get_field( 'sub_post_title', $currentPost_id ); ?></h1>
					</div>
				</div>
			</section>
			
			<?php } ?>
				



<main role="main" class="main">