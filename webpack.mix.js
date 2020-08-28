const mix = require('laravel-mix');
const { webpackConfig } = require('laravel-mix');

mix.options({
    hmrOptions: {
        host: "localhost",
        port: '8081'
    },
    // purifyCss: true,
});

if (mix.dev)
mix.webpackConfig({
    mode: "development",
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

mix.config.webpackConfig.output = {
    chunkFilename: 'js/[name].bundle.js',
    publicPath: '/',
}


mix.sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.js('resources/js/app.js', 'public/js')
    // .extract([
    //     'vue', 
    //     'moment', 
    //     'bootstrap-datepicker', 
    //     'timepicker',
    //     'axios', 
    //     'jquery',
    // ])
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}