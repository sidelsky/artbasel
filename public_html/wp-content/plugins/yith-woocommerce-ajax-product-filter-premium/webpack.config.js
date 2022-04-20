const path = require( 'path' );

module.exports = {
	devtool    : 'source-map',
	entry      : {
		shortcodes: "./assets/js/shortcodes/index.js",
	},
	mode: 'production',
	module: {
		rules: [
			{
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			}
		]
	},
	optimization: {
		minimize: false,
	},
	output     : {
		filename: "yith-wcan-[name].js",
		path: path.resolve( __dirname, 'assets/js' )
	},
};
