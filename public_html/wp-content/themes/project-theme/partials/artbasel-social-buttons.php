
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


@font-face {
	font-family: 'share-buttons';
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

/* Reset box-sizing */
.share-btn,
.share-btn *,
.share-btn *:before,
.share-btn *:after {
	box-sizing: border-box;
}

/* All share button styles are enclosed in .share-btn */
.share-btn {
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

	.share-btn:hover,
	.share-btn:focus {
		background: darken(@color-bg, 5%);
		border-color: darken(@color-bg, 15%);
		text-decoration: none;
		color: @color;
	}

	.share-btn:active {
		background: darken(@color-bg, 10%);
		border-color: darken(@color-bg, 20%);
		text-decoration: none;
		color: @color;
	}

	.share-btn.share-btn-sm {
		height: 20px;
		font-size: 10px;
		padding: 0 8px;
		line-height: 1.6;
	}

	.share-btn.share-btn-lg {
		height: 28px;
		font-size: 16px;
		line-height: 1.4;
	}

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

	.share-btn-more.share-btn-lg .share-btn-icon {
		top: 2px;
	}

	.share-btn-text {
		padding-left: 2px;
	}

</style>
