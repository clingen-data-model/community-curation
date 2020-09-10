const mix = require('laravel-mix');
const { webpackConfig } = require('laravel-mix');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');


mix.options({
    hmrOptions: {
        host: "localhost",
        port: '8081'
    },
    // purifyCss: true,
});

if (mix.dev) {
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
        resolve: {
            alias: {
                moment: 'moment/src/moment',
            }
        }
    });
}
mix.config.webpackConfig.output = {
    chunkFilename: mix.inProduction() ? 'js/modules/[name].[contenthash].js' : 'js/modules/[name].bundle.js',
    publicPath: '/',
}

mix.webpackConfig({
    plugins: [
        new CleanWebpackPlugin({
            // dry: true,
            cleanOnceBeforeBuildPatterns: ['!**/*', 'js/**/*', 'js/modules/*', 'css/**/*']
        }),
    ]
})

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


// console.log(mix.config);
// throw new Error('die!')