const { resolve } = require('path');
const webpack = require('webpack');
const postcssNested = require('postcss-nested');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const HtmlWebpackHarddiskPlugin = require('html-webpack-harddisk-plugin');

const appPath = resolve(__dirname, 'new');
const publicPath = resolve(__dirname, 'public');

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
                            presets: ['react', 'es2015', 'es2016', 'es2017']
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
                    if (req.url === '/new' || req.url.startsWith('/new/')) {
                        return req.url; // Serve with new
                    } else {
                        return false; // Continue to proxy everything else to PHP
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
            filename: 'new/bundle-[chunkhash].css',
            disable: false,
            allChunks: true
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

console.log(config);

module.exports = config;
