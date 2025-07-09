const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const {	WebpackManifestPlugin } = require('webpack-manifest-plugin');

module.exports = {
	entry: {
		'theme-style': './assets/scss/style.scss',
		'editor-style': './assets/scss/editor.scss',
		'admin-style': './assets/scss/admin.scss',
		'theme-scripts': './assets/js/main.js',
		'editor-scripts': './assets/js/editor.js',
		'admin-scripts': './assets/js/admin.js'
	},
	output: {
		path: path.resolve(__dirname, 'assets/build'),
		filename: '[name].[contenthash].js',
		clean: true,
		publicPath: '', // Ensures relative paths (important for WordPress)
		assetModuleFilename: 'assets/[name][ext]' // Optional: put fonts/images in assets folder
	},
	module: {
		rules: [
			// JS Loader
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
			// SCSS Loader
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'postcss-loader',
					'sass-loader'
				]
			},
			// (Optional) Assets loader for images/fonts if you want to use import
			{
				test: /\.(png|jpe?g|gif|svg|woff2?|eot|ttf|otf)$/i,
				type: 'asset/resource'
			}
		]
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: '[name].[contenthash].css'
		}),
		new WebpackManifestPlugin({
			fileName: 'manifest.json'
		})
	],
	devtool: 'source-map'
};
