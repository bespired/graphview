let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/vue/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.webpackConfig({
	resolve: {
		alias: {
			'@Fonts':      path.resolve(__dirname, 'resources/assets/fonts/'),
			'@Classes':    path.resolve(__dirname, 'resources/assets/vue/classes/'),
			'@Components': path.resolve(__dirname, 'resources/assets/vue/components/'),
		},
	}
});

mix.copyDirectory('public', '../../../../public/vendor/bespired/graphview');