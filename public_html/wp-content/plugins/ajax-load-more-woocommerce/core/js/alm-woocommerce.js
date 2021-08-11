/*
 * Ajax Load More - WooCommerce
 * connekthq.com/plugins/ajax-load-more/add-ons/woocommerce/
 * Copyright Connekt Media - http://connekthq.com
 * Author: Darren Cooney
 * Twitter: @KaptonKaos, @connekthq
 */

var alm_woo = {};

(function () {
	/* Set Up Vars */
	alm_woo.init = true;
	alm_woo.paging = false;
	alm_woo.previousUrl = window.location.href;
	alm_woo.isAnimating = false;
	alm_woo.defaultPage = 1;
	alm_woo.fromPopstate = false;
	alm_woo.HTMLHead = document.getElementsByTagName("head")[0].innerHTML;
	alm_woo.timer = null;
	alm_woo.isIE = navigator.appVersion.indexOf("MSIE 10") !== -1 ? true : false;

	/**
	 * alm_woo
	 * Triggered from core ajax-load-more.js
	 *
	 * @param {Object} alm
	 * @since 1.0
	 */
	window.almWooCommerce = function (alm) {
		// Exit if not WooCommerce
		if (!alm.addons.woocommerce) {
			return false;
		}

		// First run only
		if (alm_woo.init) {
			// Settings
			alm_woo.scrolltop = alm.addons.woocommerce_settings.settings.scrolltop
				? parseInt(alm.addons.woocommerce_settings.settings.scrolltop)
				: 50; // Scrolltop
			alm_woo.controls =
				alm.addons.woocommerce_settings.settings.controls === "true"
					? true
					: false; // Enable back/fwd button controls

			// Get Woo container.
			alm_woo.container = document.querySelector(
				alm.addons.woocommerce_settings.container
			);

			// Get first instance of `.alm-woocommerce`.
			alm_woo.first = alm_woo.container
				? alm_woo.container.querySelector(".alm-woocommerce:first-child")
				: "";

			alm_woo.paged_urls = alm.addons.woocommerce_settings.paged_urls; // All Paged URLS
			alm_woo.paging = alm.addons.paging;
		}

		// Delay for effects
		setTimeout(function () {
			alm_woo.init = false;
		}, 50);
	};

	/**
	 * onScroll
	 * Update browser URL on scroll
	 *
	 * @since 1.0
	 */
	alm_woo.onScroll = function () {
		var scrollTop = window.pageYOffset;

		if (!alm_woo.isAnimating && !alm_woo.paging && !alm_woo.init) {
			if (alm_woo.timer) {
				window.clearTimeout(alm_woo.timer);
			}

			alm_woo.timer = window.setTimeout(function () {
				// Get container scroll position
				var fromTop = scrollTop + alm_woo.scrolltop;
				var posts = document.querySelectorAll(".alm-woocommerce");
				var url = window.location.href;

				// Loop all posts
				var current = Array.prototype.filter.call(posts, function (n, i) {
					if (typeof ajaxloadmore.getOffset === "function") {
						var divOffset = ajaxloadmore.getOffset(n);
						if (divOffset.top < fromTop) {
							return n;
						}
					}
				});

				// Get the data attributes of the current element
				var currentPost = current[current.length - 1];
				var permalink = currentPost ? currentPost.dataset.url : "";
				var page = currentPost ? currentPost.dataset.page : "";
				var pageTitle = currentPost ? currentPost.dataset.pageTitle : "";

				// If first page
				if (page === undefined || page === "") {
					// Get first instance of `.alm-woocommerce`.
					alm_woo.first = alm_woo.container
						? alm_woo.container.querySelector(
								".alm-woocommerce:first-child"
						  )
						: "";
					if (alm_woo.first) {
						page = alm_woo.first.dataset.page;
						permalink = alm_woo.first.dataset.url;
						pageTitle = alm_woo.first.dataset.pageTitle;
					}
				}

				if (url !== permalink) {
					alm_woo.setURL(page, permalink, pageTitle);
				}
			}, 15);
		}
	};
	window.addEventListener("touchstart", alm_woo.onScroll);
	window.addEventListener("scroll", alm_woo.onScroll);

	/**
	 * alm_woo
	 * Set the browser URL to current permalink then scroll user to post
	 *
	 * @param {String} page
	 * @param {String} permalink
	 * @param {String} pageTitle
	 * @since 1.0
	 */
	alm_woo.setURL = function (page, permalink, pageTitle) {
		var state = {
			page: page,
			permalink: permalink,
		};

		if (permalink !== alm_woo.previousUrl && !alm_woo.fromPopstate) {
			if (typeof window.history.pushState === "function" && !alm_woo.isIE) {
				// If pushstate is enabled
				if (alm_woo.controls) {
					// pushstate
					history.pushState(state, "", permalink);
				} else {
					// replaceState
					history.replaceState(state, "", permalink);
				}

				// Callback Function (URL Change)
				if (typeof almUrlUpdate === "function") {
					window.almUrlUpdate(permalink, "woocommerce");
				}
			}

			// Update document title
			document.title = pageTitle;

			alm_woo.previousUrl = permalink;
		}

		alm_woo.getRelLinks(page);
		alm_woo.fromPopstate = false;
	};

	/**
	 * onpopstate
	 * Fires when users click back or forward browser buttons
	 *
	 * @since 1.0
	 */
	alm_woo.onpopstate = function (event) {
		// if wrapper doesnt have data-woo="true" don't fire popstate
		var woo = document.querySelector('.alm-listing[data-woo="true"]');

		if (woo) {
			if (!alm_woo.paging) {
				// Not Paging
				alm_woo.fromPopstate = true;
				alm_woo.getPageState(event.state);
			} else {
				// Paging
				if (
					typeof almSetCurrentPage === "function" &&
					typeof almGetParentContainer === "function" &&
					typeof almGetObj === "function"
				) {
					var current = event.state,
						obj = window.almGetParentContainer(),
						alm = window.almGetObj();

					// Check for null state value
					current =
						current === null ? alm_woo.defaultPage : event.state.page;

					// Set popstate flag to true - don't trigger pushstate on url update
					alm_woo.fromPopstate = true;

					if (typeof almSetCurrentPage === "function") {
						// Paging addon function
						window.almSetCurrentPage(current, obj, alm);
					}
				}
			}
		}
	};

	/**
	 * popstate
	 * Window popstate eventlistener
	 *
	 * @since 1.0
	 */
	window.addEventListener("popstate", function (event) {
		if (typeof window.history.pushState == "function") {
			alm_woo.onpopstate(event);
		}
	});

	/**
	 * getPageState
	 * Get the current page number
	 *
	 * @param {Object} data
	 * @since 1.0
	 */
	alm_woo.getPageState = function (data) {
		var page;
		if (data === null || data === "") {
			// Will be null with preloaded, so set -1
			page = -1;
		} else {
			page = data.page;
		}

		if (alm_woo.container) {
			alm_woo.scrollToPage(page);
		}
	};

	/**
	 * scrollToPage
	 * Scroll page to current element wrapper
	 *
	 * @param {Number} page
	 * @since 1.0
	 */
	alm_woo.scrollToPage = function (page) {
		// Current page
		page =
			page === undefined || page === "" || page === "-1" || page === -1
				? alm_woo.first.dataset.page
				: page;

		if (alm_woo.isAnimating) {
			// Exit if animating
			return false;
		}
		alm_woo.isAnimating = true;

		var target = document.querySelector(
			'.alm-woocommerce[data-page="' + page + '"]'
		);

		if (target) {
			var offset =
				typeof ajaxloadmore.getOffset === "function"
					? ajaxloadmore.getOffset(target).top
					: target.offsetTop;
			var top = offset - alm_woo.scrolltop + 5;

			// Move window to position
			setTimeout(function () {
				// Delay fixes browser popstate issues
				window.scrollTo(0, top);
				alm_woo.fromPopstate = false;
			}, 5);
		}

		setTimeout(function () {
			alm_woo.isAnimating = false;
		}, 250);
	};

	/**
	 * getRelLinks
	 * Set the meta rel links to page <head />.
	 *
	 * @param {Number} page
	 * @since 1.0
	 */
	alm_woo.getRelLinks = function (page) {
		page = parseInt(page);
		alm_woo.setRelLink(page - 1, "prev");
		alm_woo.setRelLink(page + 1, "next");
	};

	/**
	 * setRelLink
	 * Set the <link /> tag for next and prev rel links
	 *
	 * @param {Number} page
	 * @param {Number} start
	 * @since 1.0
	 */
	alm_woo.setRelLink = function (page, type) {
		var rel = document.querySelector('link[rel="' + type + '"]');

		// If 'next' and last page, remove the link rel.
		if (type === "next" && page > alm_woo.paged_urls.length) {
			alm_woo.removeRelLink(rel);
			return false;
		}

		// If 'prev' and page is 1
		if (type === "prev" && page == 0) {
			alm_woo.removeRelLink(rel);
			return false;
		}

		// Create <link rel />
		var link = alm_woo.paged_urls[page - 1];
		if (rel) {
			// if exists, just update the href value
			rel.href = link;
		} else {
			// doesn't exist. Create it
			rel = document.createElement("link");
			rel.href = link;
			rel.rel = type;
			document.getElementsByTagName("head")[0].appendChild(rel);
		}
	};

	/**
	 * removeRelLink
	 * remove the <link /> tag
	 *
	 * @param {Element} rel
	 * @since 1.0
	 */
	alm_woo.removeRelLink = function (rel) {
		if (rel) {
			rel.parentNode.removeChild(rel); // If exists
		}
	};
})();
