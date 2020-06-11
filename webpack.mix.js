const mix = require('laravel-mix');
const { webpackConfig } = require('laravel-mix');
// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
// const IgnorePlugin = require('webpack').IgnorePlugin;
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

mix.options({
    hmrOptions: {
        host: "localhost",
        port: '8081'
    },
});

if (mix.dev)
mix.webpackConfig({
    // mode: "development",
    devtool: "inline-source-map",
    devServer: {
        disableHostCheck: true,
        headers: {
            'Access-Control-Allow-Origin': '*'
        },
        host: "localhost",
        port: '8081'
    },
    plugins: [
        // new BundleAnalyzerPlugin()
        // new IgnorePlugin(/^\.\/locale$/, /moment$/)
    ],
    resolve: {
        alias: {
            moment: 'moment/src/moment',
        }
    }
});

mix.js('resources/js/app.js', 'public/js')
    .extract(['vue', 'moment', 'bootstrap-datepicker', 'timepicker', 'axios', 'jquery'])
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}