const path = require('path');

// css extraction and minification
const globImporter = require('node-sass-glob-importer');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

// clean out build dir in-between builds
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

// console.log(glob);

module.exports = [
	{
		entry: {
			'blocks': [
				'./blocks/blocks.js'
			],
			'main': [
				'./assets/js/src/main.js',
				'./assets/css/src/main.scss'
			]
		},
		output: {
			filename: './assets/js/build/[name].min.[fullhash].js',
			path: path.resolve(__dirname)
		},
		module: {
			rules: [
				// js babelization
				{
					test: /\.(js|jsx)$/,
					exclude: /node_modules/,
					loader: 'babel-loader',
				},
				// sass compilation
				{
					test: /\.(sass|scss)$/,
					use: [
						{
							loader: MiniCssExtractPlugin.loader,
						},
						{
							loader: 'css-loader',
						},
						{
							loader: 'sass-loader',
							options: {
								sassOptions: {
									importer: globImporter()
								}
							}
						}
					]
				},
				// loader for webfonts (only required if loading custom fonts)
				{
					test: /\.(woff|woff2|eot|ttf|otf)$/,
					type: 'asset/resource',
					generator: {
						filename: './assets/css/build/font/[name][ext]',
					}
				},
				// loader for images and icons (only required if css references image files)
				{
					test: /\.(png|jpg|gif)$/,
					type: 'asset/resource',
					generator: {
						filename: './assets/css/build/img/[name][ext]',
					}
				},
			]
		},
		plugins: [
			// clear out build directories on each build
			new CleanWebpackPlugin({
				cleanOnceBeforeBuildPatterns: [
					'./assets/js/build/*',
					'./assets/css/build/*'
				]
			}),
			// css extraction into dedicated file
			new MiniCssExtractPlugin({
				filename: './assets/css/build/main.min.[fullhash].css'
			}),
		],
		optimization: {
			// minification - only performed when mode = production
			minimizer: [
				// js minification - special syntax enabling webpack 5 default terser-webpack-plugin 
				`...`,
				// css minification
				new CssMinimizerPlugin(),
			]
		},
	}
];