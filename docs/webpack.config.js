const ExtractTextPlugin = require('extract-text-webpack-plugin');
const PurgecssPlugin = require('purgecss-webpack-plugin');
const glob = require("glob-all");
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const path = require('path');

class TailwindExtractor {
    static extract(content) {
        return content.match(/[A-z0-9-:\/]+/g);
    }
}

module.exports = {
    entry: './index.js',
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'styles.css',
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [
                        { loader: 'css-loader', options: { importLoaders: 1 } },
                        'postcss-loader'
                    ]
                })
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('styles.css'),
        // new PurgecssPlugin({
        //     paths: glob.sync([
        //         path.join(__dirname, "_site/**/*.html"),
        //     ]),
        //     extractors: [{
        //         extractor: TailwindExtractor,
        //         extensions: ["html"]
        //     }]
        // }),
        new OptimizeCssAssetsPlugin(),
    ]
}