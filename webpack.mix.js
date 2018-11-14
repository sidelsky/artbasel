/*
 |--------------------------------------------------------------------------
 | Laravel Mix Config File
 |--------------------------------------------------------------------------
 |
 | Larvel mix is used as a build tool create all front end assets including
 | SCSS, JS, SVG Sprite and browsersync.
 |
 */

// Dependencies
const mix = require('laravel-mix');
const SvgStore = require('webpack-svgstore-plugin');
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");

// Assets Path
const jsSrcPath = 'src/Assets/js/app.js';
const scssSrcPath = 'src/Assets/scss/style.scss';
const themePath = 'public_html/wp-content/themes/project-theme';
const destPath = `${themePath}/assets/build`;

const vhost = "http://safetonet.localhost:8888/";

mix.setPublicPath('public_html');

// Styles
mix.sass(scssSrcPath, destPath)
    .options({
        postCss: [require('lost')(), require('postcss-encode-background-svgs')()],
        processCssUrls: false
    });

// Js
mix.js(jsSrcPath, destPath);

// SVG Sprite
mix.webpackConfig({
    plugins: [
        new SvgStore({
        svgoOptions: {
            plugins: [
                {
                    removeTitle: true
                }
            ]
        },
        prefix: 'shape-'
    }),
    new BrowserSyncPlugin({
        // browse to http://localhost:3000/ during development,
        // ./public directory is being served
        host: "localhost",
        port: 3000,
        //files: ["./**/*.php", "./**/*.scss", "./**/*.js"], // Reaload *.php
        proxy: {
           target: vhost
        }
     }),
    ],
    module: {
        rules: [
            {
                test: /\.scss/,
                loader: 'import-glob-loader'
            },
            {
                test: /(\.vue|\.js)$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            }
        ]
    }
});

// Versioning and Sourcemaps
if (mix.config.production) {
    // Enable cache busting in production
    mix.version();

    // Code Splitting Example - More info on this in the README.md file
    // mix.extract(['vue']);
} else {
    // Enable sourcemap for development
    mix.sourceMaps();
}
