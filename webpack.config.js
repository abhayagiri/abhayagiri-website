'use strict';

const path = require('path');
const webpack = require('webpack');
const postcssNested = require('postcss-nested');

/*-----------------------------
Client Config
-----------------------------*/
module.exports = {
    name: 'client',
    context: __dirname,

    entry: ['./new/app.js'],

    output: {
        path: path.join(__dirname, './public/'),
        filename: 'js/client.js'
    },
    module: {
        rules: [
            {
                test: /\.css$/,
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
                exclude: [/node_modules/],
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: ['react', 'es2015', 'es2016', 'es2017']
                        }
                    }
                ]
            }
        ]
    },

    plugins: [
    ],

    devtool: 'eval',

    devServer: {
        contentBase: path.join(__dirname, "public"),
        proxy:{
            '/api':'http://localhost:8000/'
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