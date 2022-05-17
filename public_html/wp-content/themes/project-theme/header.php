<?php
use App\Helper\Page;
use App\Helper\Render;
use App\Helper\WordpressHelper;

$page = new Page;
$render = new Render;
$wordpress = new WordpressHelper;

//phpinfo();

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

		<!-- Kuula script -->
		<script src="https://static.kuula.io/api.js"></script>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-T2P7DZ2');</script>
		<!-- End Google Tag Manager -->

		<script src="https://player.vimeo.com/api/player.js"></script>

<script type='text/javascript' src='https://vip-hauserwirth.com/wp-content/themes/project-theme/assets/build/app-1.js' id='app-js'></script>

		<?php wp_head(); ?>
		<!-- VPS Server -->
	</head>



	<?php
		if( is_page('works-preview') ) {
			$isHome = 'home';
		} else {
			$isHome = '';
		}
	?>
	<body <?php body_class($isHome); ?> id="body">

	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T2P7DZ2" height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php
		/**
		 * Theme Data
		 */
		include('Theme/Data/en.php');
		$homeUrl = get_home_url();
		$worksUrl = '/';
		if( is_page( 'works' ) || is_single( ) ) {
			$baseUrl = $worksUrl;
		} else {
			$baseUrl = $homeUrl;
		}

		/**
		 * Check for Parallax alpha template
		 */
		if( is_page_template( 'parallax-template.php' ) ) {
			$logo = '#shape-hauserwirth-logo-white';
		} else {
			$logo = '#shape-hauserwirth-logo';
		}
	?>
	<header class="c-header" id="header">

		<div class="c-header__wrap">

			<a href="<?= $baseUrl ?>" class="c-header__link c-header__link--white">
				<svg class="c-header__icon">
					<use xlink:href='<?= $logo ?>'></use>
				</svg>
			</a>

			<!-- Show only on parallax-template.php when page is scrolled -->
			<a href="<?= $baseUrl ?>" class="c-header__link c-header__link--black">
				<svg class="c-header__icon">
					<use xlink:href="#shape-hauserwirth-logo"></use>
				</svg>
			</a>



			<?php if( !is_post_type_archive('private-room') ) : ?>
				<!-- START: Hamburger -->
				<a class="c-hamburger js-hamburger">
					<span class="c-hamburger__bar"></span>
				</a>
				<!-- END: Hamburger -->
			<?php endif; ?>

		</div>

		<?php if( !is_post_type_archive('private-rooms') && !is_singular('private-rooms')) : ?>

			<a href="<?= $baseUrl ?>" class="c-header__link c-header__link--left">
				<svg class="c-header__icon">
					<use xlink:href='#shape-hauserwirth-logo'></use>
				</svg>
			</a>

			<div class="l-site-header__nav" data-id="site-header-nav" id="header">
				<?php
					$menu_args = [
						'menu' => 'Primary navigation',
						'container' => '',
						'echo' => true,
						'items_wrap' => '<ul class="c-site-nav__menu">%3$s</ul>'
					];
					echo '<nav class="c-site-nav">';
						wp_nav_menu($menu_args);
					echo '</nav>';
				?>
			</div>
		<?php endif; ?>

		<?php
			if ( is_singular('private-rooms') ) {
				include("partials/scroll-to-navigation.php");
			}
		?>

	</header>

	<!-- Mobile Nav -->
	<div class="c-mobile-navigation">
		<?php
			$menu_args = [
				'menu' => 'Primary navigation',
				'container' => '',
				'echo' => true,
				'items_wrap' => '<ul class="c-mobile-navigation__menu">%3$s</ul>'
			];
			echo '<nav>';
				wp_nav_menu($menu_args);
			echo '</nav>';
		?>
	</div>


	<?php
	$front_page_id = '6';
	$currentPost_id = get_the_ID();
	$content = get_post_field('post_content', $front_page_id);

	if( is_front_page() || is_page('Works preview') ) { ?>
	<!-- NULL -->
	<?php } else { ?>

		<?php if( is_singular('works') || !is_page_template('Viewing room template') ) : ?>

			<?php else : ?>
			<section class="u-section ">
				<div class="u-l-container--center" data-in-viewport>
					<div class="u-l-container--center u-l-horizontal-padding <?= is_front_page() || is_page('Works preview') === ( TRUE ) ? 'u-l-vertical-padding u-l-vertical-padding--bottom' : 'u-l-vertical-padding u-l-vertical-padding--small' ?>">
						<h1 class="c-site-headings c-site-headings--h1 <?= is_page_template('thanks.php') === ( TRUE ) ? 'c-text-align-centre c-site-headings--h2--thanks' : '' ?>">
							<?= get_the_title( $currentPost_id ); ?>
						</h1>
						<h1 class="c-site-headings c-site-headings--h1">
							<?= get_field( 'sub_post_title', $currentPost_id ); ?>
						</h1>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php } ?>

<main role="main" class="main">
