
<script>
(function(){

  var shareButtons = document.querySelectorAll(".share-btn");

  if (shareButtons) {
      [].forEach.call(shareButtons, function(button) {
      button.addEventListener("click", function(event) {
 				var width = 650,
            height = 450;

        event.preventDefault();

        window.open(this.href, 'Share Dialog', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width='+width+',height='+height+',top='+(screen.height/2-height/2)+',left='+(screen.width/2-width/2));
      });
    });
  }

})();
</script>

<style>
// Base (specific to demo, not needed for buttons)
body {
  padding: 10px 20px;
}

h1,
h2,
h3,
h4,
h5,
h6 {
  margin: 10px 10px 10px 0;
  color: #222;
  font-family: "Helvetica Neue", Arial, sans-serif;
}

p {
  color: black;
  font-family: "Helvetica Neue", Arial, sans-serif;
}

.heading-sizes {
	margin-top: 30px;
}

.divider {
	height: 5px;
}

// Icons
@font-face {
	font-family: 'share-buttons';
	// Fonts are served from Share Buttons v1.0.0 repository via RawGit
	src:url('https://cdn.rawgit.com/sunnysingh/share-buttons/v1.0.0/build/fonts/share-buttons.eot?gpra60');
	src:url('https://cdn.rawgit.com/sunnysingh/share-buttons/v1.0.0/build/fonts/share-buttons.eot?#iefixgpra60') format('embedded-opentype'),
		url('https://cdn.rawgit.com/sunnysingh/share-buttons/v1.0.0/build/fonts/share-buttons.woff?gpra60') format('woff'),
		url('https://cdn.rawgit.com/sunnysingh/share-buttons/v1.0.0/build/fonts/share-buttons.ttf?gpra60') format('truetype'),
		url('https://cdn.rawgit.com/sunnysingh/share-buttons/v1.0.0/build/fonts/share-buttons.svg?gpra60#share-buttons') format('svg');
	font-weight: normal;
	font-style: normal;
}

.share-btn-icon {
	font-family: 'share-buttons';
	speak: none;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 1;

	/* Better Font Rendering =========== */
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.share-btn-email .share-btn-icon:before {
	content: "\e945";
}

.share-btn-more .share-btn-icon:before {
	content: "\ea82";
}

.share-btn-googleplus .share-btn-icon:before {
	content: "\ea88";
}

.share-btn-facebook .share-btn-icon:before {
	content: "\ea8c";
}

.share-btn-twitter .share-btn-icon:before {
	content: "\ea91";
}

.share-btn-github .share-btn-icon:before {
	content: "\eab4";
}

.share-btn-tumblr .share-btn-icon:before {
	content: "\eabb";
}

.share-btn-reddit .share-btn-icon:before {
	content: "\eac7";
}

.share-btn-linkedin .share-btn-icon:before {
	content: "\eac8";
}

.share-btn-delicious .share-btn-icon:before {
	content: "\eacc";
}

.share-btn-stumbleupon .share-btn-icon:before {
	content: "\eace";
}

.share-btn-pinterest .share-btn-icon:before {
	content: "\ead0";
}

.share-btn,
.share-btn * {
  box-sizing: border-box;
}

// Reset box-sizing
.share-btn,
.share-btn *,
.share-btn *:before,
.share-btn *:after {
	box-sizing: border-box;
}

// All share button styles are enclosed in .share-btn
.share-btn {
	@color-bg: #e0e0e0;
	@color: #111;

	position: relative;
	display: inline-block;
	height: 24px;
	margin: 0;
	padding: 2px 8px;
	line-height: 1.53;
	letter-spacing: .04em;
	vertical-align: top;
	font-size: 12px;
	font-weight: bold;
	font-family: "Helvetica Neue", Arial, sans-serif;
	color: @color;
	background: @color-bg;
	border: 1px solid darken(@color-bg, 10%);
	border-radius: 2px;
	text-decoration: none;
	transition: all 0.2s ease;

	&:hover,
	&:focus {
		background: darken(@color-bg, 5%);
		border-color: darken(@color-bg, 15%);
		text-decoration: none;
		color: @color;
	}

	&:active {
		background: darken(@color-bg, 10%);
		border-color: darken(@color-bg, 20%);
		text-decoration: none;
		color: @color;
	}

	&.share-btn-sm {
		height: 20px;
		font-size: 10px;
		padding: 0 8px;
		line-height: 1.6;
	}

	&.share-btn-lg {
		height: 28px;
		font-size: 16px;
		line-height: 1.4;
	}

	// More button
	.share-btn-text-sr {
		// Screen readers only
		position: absolute;
		width: 1px;
		height: 1px;
		padding: 0;
		margin: -1px;
		overflow: hidden;
		clip: rect(0,0,0,0);
		border: 0;
	}

	// Branded

	&.share-btn-branded {
		color: #fff;
	}

	&.share-btn-branded.share-btn-twitter {
		@color-brand: #55acee;
		background: @color-brand;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-facebook {
		@color-brand: #3b5998;
		background: @color-brand;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-googleplus {
		@color-brand: #dd4b39;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-tumblr {
		@color-brand: #35465c;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-reddit {
		@color-brand: #ff4500;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-linkedin {
		@color-brand: #0976b4;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-delicious {
		@color-brand: #3399ff;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-stumbleupon {
		@color-brand: #eb4924;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	&.share-btn-branded.share-btn-pinterest {
		@color-brand: #cc2127;
		background: @color-brand;
		color: #fff;
		border-color: darken(@color-brand, 5%);
		&:hover, &:focus {
			background: darken(@color-brand, 5%);
			border-color: darken(@color-brand, 10%);
		}
		&:active {
			background: darken(@color-brand, 10%);
			border-color: darken(@color-brand, 15%);
		}
	}

	// Inverse
	&.share-btn-inverse {
		color: #fff - @color;
		background: #fff - @color-bg;
		border-color: darken(#fff - @color-bg, 10%);
		&:hover,
		&:focus {
			background: darken(#fff - @color-bg, 5%);
			border-color: darken(#fff - @color-bg, 15%);
			color: #fff - @color;
		}
		&:active {
			background: darken(#fff - @color-bg, 10%);
			border-color: darken(#fff - @color-bg, 20%);
			color: #fff - @color;
		}
	}

	// Share icon
	&.share-btn-twitter .share-btn-icon,
	&.share-btn-googleplus .share-btn-icon,
	&.share-btn-tumblr .share-btn-icon,
	&.share-btn-linkedin .share-btn-icon,
	&.share-btn-pinterest .share-btn-icon,
	&.share-btn-stumbleupon .share-btn-icon,
	&.share-btn-delicious .share-btn-icon,
	&.share-btn-more .share-btn-icon {
		position: relative;
		top: 1px;
	}

	// Super hacky but needed until a better fix
	@-moz-document url-prefix() {
		&.share-btn-twitter .share-btn-icon,
		&.share-btn-googleplus .share-btn-icon,
		&.share-btn-tumblr .share-btn-icon,
		&.share-btn-linkedin .share-btn-icon,
		&.share-btn-pinterest .share-btn-icon,
		&.share-btn-stumbleupon .share-btn-icon,
		&.share-btn-delicious .share-btn-icon,
		&.share-btn-more .share-btn-icon {
			top: 0;
		}
	}

	&.share-btn-more.share-btn-lg .share-btn-icon {
		top: 2px;
	}

	// Text
	.share-btn-text {
		padding-left: 2px;
	}
}</style>
