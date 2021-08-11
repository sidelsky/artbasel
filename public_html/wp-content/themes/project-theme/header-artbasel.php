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

<!--
		<link data-n-head="true" rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/build/img/favicon/favicon-16x16.png"/>
		<link data-n-head="true" rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/build/img/favicon/favicon-32x32.png"/>
		<link data-n-head="true" rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/assets/build/img/favicon/favicon-96x96.png"/> -->

		<!-- Kuula script
		<script src="https://static.kuula.io/api.js"></script>
		-->

		<!-- Google Tag Manager
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-T2P7DZ2');</script>
	 End Google Tag Manager -->

			<!--	<script src="https://player.vimeo.com/api/player.js"></script>-->

		<?php wp_head(); ?>
		<!-- VPS Server -->

		<link rel='stylesheet' id='artbasel_css-css'  href='/wp-content/themes/project-theme/assets/build/artbasel-style.css' type='text/css' media='all' />
		<link rel='stylesheet' id='artbasel_css-css'  href='/wp-content/themes/project-theme/assets/build/artbasel-responsive.css' type='text/css' media='all' />

	</head>

	<body <?php body_class($isHome); ?> id="body">

	<!-- Google Tag Manager (noscript)
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T2P7DZ2" height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
   End Google Tag Manager (noscript) -->

	<header id="header" class="c-header wow fadeInDown" data-wow-duration=".5s" style="visibility: visible; animation-name: fadeInDown;">

		<div class="c-header__wrap">

 				<!-- START: Hamburger -->
				<a class="c-hamburger js-hamburger">
					<span class="c-hamburger__bar"></span>
				</a>
				<!-- END: Hamburger -->
		</div>


			<a href="<?= $baseUrl ?>" class="c-header__link c-header__link--left">
				<svg class="c-header__icon">
					<use xlink:href='#shape-hauserwirth-logo'></use>
				</svg>
			</a>

			<div class="l-site-header__nav" data-id="site-header-nav" id="header">
				<?php
					$menu_args = [
						'menu' => 'artbasel',
						'container' => '',
						'echo' => true,
						'items_wrap' => '<ul class="c-site-nav__menu">%3$s</ul>'
					];
					echo '<nav class="c-site-nav">';
						wp_nav_menu($menu_args);
					echo '</nav>';
				?>

			</div>
			<?php
									wp_nav_menu( array(
						    'menu'   => 'artbasel-woocommerce',
									'items_wrap' => '<ul class="wc__menu">%3$s</ul>'
						) );
			?>

	</header>

	<!-- Mobile Nav -->
	<div class="c-mobile-navigation">
		<?php
			$menu_args = [
				'menu' => 'artbasel',
				'container' => '',
				'echo' => true,
				'items_wrap' => '<ul class="c-mobile-navigation__menu">%3$s</ul>'
			];
			echo '<nav>';
				wp_nav_menu($menu_args);
			echo '</nav>';
		?>
		<?php
											wp_nav_menu( array(
								    'menu'   => 'artbasel-woocommerce',
											'items_wrap' => '<ul class="wc__menu">%3$s</ul>'
								) );
					?>
	</div>



<main role="main" class="main">
