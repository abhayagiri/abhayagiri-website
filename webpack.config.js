const { resolve } = require('path');
const webpack = require('webpack');
const postcssNested = require('postcss-nested');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const HtmlWebpackHarddiskPlugin = require('html-webpack-harddisk-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

const appPath = resolve(__dirname, 'new');
const publicPath = resolve(__dirname, 'public');

/* These compnents no longer need the /new prefix */
const readyPrefixes = [
    'gallery',
    'talks'
];

let config = {

    context: __dirname,

    entry: {
        app: resolve(appPath, 'app.js')
    },

    output: {
        path: publicPath,
        publicPath: '/',
        filename: 'new/bundle.js'
    },

    module: {
        rules: [
            {
                test: /\.css$/,
                exclude: /node_modules/,
                use: [
                    'style-loader',
                    'css-loader',
                    {
                        loader: 'postcss-loader',
                        options: {
                            plugins: [postcssNested]
                        }
                    }
                ],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: ['es2015', 'es2016', 'es2017', 'react'],
                            plugins: ['transform-class-properties', 'transform-object-rest-spread']
                        }
                    }
                ]
            },
            {
                test: /\.(jpe?g|png|gif)$/,
                use: 'url-loader'
            }
        ]
    },

    plugins: [
        new HtmlWebpackPlugin({
            template: resolve(appPath, 'index.html'),
            filename: 'new/index.html',
            alwaysWriteToDisk: true
        }),
        new HtmlWebpackHarddiskPlugin()
    ],

    devtool: 'eval',

    devServer: {
        contentBase: resolve(__dirname, 'public'),
        proxy: {
            '/': {
                target: 'http://localhost:8000/',
                bypass: function(req, res, proxyOptions) {
                    const url = req.url;
                    for (const readyPrefix of readyPrefixes) {
                        if (url === `/${readyPrefix}` ||
                            url === `/th/${readyPrefix}` ||
                            url.startsWith(`/${readyPrefix}/`) ||
                            url.startsWith(`/th/${readyPrefix}/`)) {
                            return url;
                        }
                    }
                    if (url === '/new' || url.startsWith('/new/')) {
                        return url;
                    } else {
                        // Proxy everything else to PHP
                        return false;
                    }
                }
            }
        },
        historyApiFallback : {
            index: 'new/index.html'
        },
        compress: true,
        port: 9000
    },

    resolve: {
        extensions: ['.js', '.jsx'],
        modules: ['new', 'node_modules']
    }
};

if (process.env.NODE_ENV === 'production') {

    config.plugins.push(
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify('production')
        })
    );

    config.plugins.push(
        new CleanWebpackPlugin([
            publicPath + '/new/*.*'
        ], {
            exclude: 'new/.gitignore'
        })
    );

    config.plugins.push(
        new webpack.optimize.ModuleConcatenationPlugin()
    );

    config.plugins.push(
        new webpack.optimize.UglifyJsPlugin()
    );

    config.plugins.push(
        new ExtractTextPlugin({
            filename: 'new/bundle-[chunkhash].css'
        })
    );

    config.plugins.push(
        new ManifestPlugin({
            fileName: 'new/manifest.json'
        })
    );

    config.module.rules[0] = {
        test: /\.css$/,
        exclude: /node_modules/,
        use: ExtractTextPlugin.extract({
            fallback: 'style-loader',
            use: [
                'css-loader',
                {
                    loader: 'postcss-loader',
                    options: {
                        plugins: [postcssNested]
                    }
                }
            ]
        })
    };

    config.output.filename = 'new/bundle-[chunkhash].js';
    config.devtool = false;

}

// console.log(config);

module.exports = config;
