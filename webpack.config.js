const Encore = require('@symfony/webpack-encore');
const path = require('path');
const webpack = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured())
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.ts')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableVueLoader(() => {}, {
        runtimeCompilerBuild: false
    })
    .addAliases({
        '@': path.resolve(__dirname, 'assets')
    })
    .addPlugin(new webpack.DefinePlugin({
        'process.env.BASE_URL': JSON.stringify(process.env.BASE_URL)
    }))
;

module.exports = Encore.getWebpackConfig();
